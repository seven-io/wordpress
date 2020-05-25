<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */
require_once 'class-sms77api-options.php';

class sms77api_Util {
    const WOOC_BULK_FILTER_DATE_ACTIONS = ['created', 'paid', 'completed',];
    const WOOC_BULK_FILTER_DATE_MODIFICATORS = ['>=', '<=', '>', '<', '...',];

    static function hasWooCommerce() {
        return in_array('woocommerce/woocommerce.php',
            apply_filters('active_plugins', get_option('active_plugins')));
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
            $p = sprintf(wp_kses(
                __('An API Key is required for sending SMS. Please head to the <a href="%s">Plugin Settings</a> to set it.', 'sms77api'),
                ['a' => ['href' => []]]), esc_url($href));

            echo "<p>$p</p>";
        }
    }
}