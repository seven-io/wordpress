<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */
class sms77api_Util {
    static function getOptions() {
        return [
            'sms77api_debug' => [0, [], 'boolean'],
            'sms77api_key' => [null, [
                'sanitize_callback' => function($key) {
                    $error = function($msg) {
                        add_settings_error('sms77api_key', 'sms77api_invalid_key', $msg);
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
            'sms77api_msg' => [null],
            'sms77api_receivers' => [null],
        ];
    }

    static function get($endpoint, $apiKey, $data = []) {
        $isJsonEndpoint = 'balance' !== $endpoint;

        $response = wp_remote_get(
            "https://gateway.sms77.io/api/$endpoint?"
            . http_build_query(array_merge($data, ['json' => $isJsonEndpoint ? 1 : 0, 'p' => $apiKey,])),
            ['blocking' => true,]);

        if (is_wp_error($response)) {
            //log_error($response->get_error_message()); //TODO implement logging?
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