<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */
class sms77api_Util {
    const WOOC_BULK_FILTER_DATE_ACTIONS = ['created', 'paid', 'completed',];
    const WOOC_BULK_FILTER_DATE_MODIFICATORS = ['>=', '<=', '>', '<', '...',];

    static function hasWooCommerce() {
        return in_array('woocommerce/woocommerce.php',
            apply_filters('active_plugins', get_option('active_plugins')));
    }

    static function getOptions() {
        return [
            'sms77api_debug' => [0, [], 'boolean'],
            'sms77api_delay' => [null],
            'sms77api_flash' => [0, [], 'boolean'],
            'sms77api_label' => [null],
            'sms77api_key' => [null, [
                'sanitize_callback' => function($key) {
                    $error = function($msg) {
                        add_settings_error('sms77api_key',
                            'sms77api_invalid_key', $msg);
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
            'sms77api_performance_tracking' => [0, [], 'boolean'],
            'sms77api_receivers' => [null],
            'sms77api_udh' => [null],
            'sms77api_unicode' => [0, [], 'boolean'],
            'sms77api_utf8' => [0, [], 'boolean'],
            'sms77api_ttl' => [null, [], 'integer'],
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

    static function defaultMessageElements() {
        if (count(isset($_GET['errors']) ? $_GET['errors'] : [])) {
            $errors = implode(PHP_EOL, $_GET['errors']);
            echo "<b>Errors:</b><pre>$errors</pre>";
        }

        if (isset($_GET['response'])) {
            $errors = json_encode($_GET['response'], JSON_PRETTY_PRINT);
            echo "<b>Response:</b><pre>$errors</pre>";
        }

        if (!get_option('sms77api_key')) {
            $href = admin_url('options-general.php?page=sms77api');
            echo "<p>An API Key is required for sending SMS. Please head to the
                <a href='$href'>Plugin Settings</a> to set it.
            </p>";
        }
    }
}