<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */
class sms77api_Util {
    const PREFIX = 'sms77api';

    static function getOptions() {
        return [
            self::PREFIX . '_debug' => [0, [], 'boolean'],
            self::PREFIX . '_flash' => [0, [], 'boolean'],
            self::PREFIX . '_label' => [null],
            self::PREFIX . '_key' => [null, [
                'sanitize_callback' => function($key) {
                    $error = function($msg) {
                        add_settings_error(self::PREFIX . '_key',
                            self::PREFIX . '_invalid_key', $msg);
                    };

                    $response = sms77api_Util::get('balance', $key);

                    if (!$response) {
                        return $error('Internal error. Please try again later.');
                    }

                    if ('900' === $response) {
                        return $error('Invalid API key or API down.');
                    }

                    return $key;
                },]],
            self::PREFIX . '_msg' => [null],
            self::PREFIX . '_performance_tracking' => [0, [], 'boolean'],
            self::PREFIX . '_receivers' => [null],
            self::PREFIX . '_unicode' => [0, [], 'boolean'],
            self::PREFIX . '_utf8' => [0, [], 'boolean'],
            self::PREFIX . '_ttl' => [null, [], 'integer'],
        ];
    }

    static function get($endpoint, $apiKey, $data = []) {
        $isJsonEndpoint = 'balance' !== $endpoint;

        $response = wp_remote_get(
            "https://gateway.sms77.io/api/$endpoint?"
            . http_build_query(array_merge($data, ['json' => $isJsonEndpoint ? 1 : 0, 'p' => $apiKey])),
            ['blocking' => true,]);

        if (is_wp_error($response)) {
            error_log($response->get_error_message());
            return null;
        } else {
            $body = $response['body'];

            if ($isJsonEndpoint) {
                $body = (array)json_decode($body);

                if ('sms' === $endpoint) {
                    foreach ($body['messages'] as $k => $msg) {
                        $body['messages'][$k] = (array)$msg;
                    }
                }
            }

            return $body;
        }
    }
}