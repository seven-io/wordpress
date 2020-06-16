<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */

class sms77api_Util {
    const LOOKUP_TYPES = ['format', 'cnam', 'hlr', 'mnp',];
    const TABLES = ['messages', 'number_lookups', 'mnp_lookups', 'hlr_lookups', 'cnam_lookups',];
    const WOOC_BULK_FILTER_DATE_ACTIONS = ['created', 'paid', 'completed',];
    const WOOC_BULK_FILTER_DATE_MODIFICATORS = ['>=', '<=', '>', '<', '...',];

    /**
     * @param string $endpoint
     * @param string $apiKey
     * @param array $data
     * @return array|mixed|null
     */
    static function get($endpoint, $apiKey, array $data = []) {
        $isJsonEndpoint = 'balance' !== $endpoint;

        $response = wp_remote_get(
            "https://gateway.sms77.io/api/$endpoint?"
            . http_build_query(array_merge($data, [
                'json' => $isJsonEndpoint ? 1 : 0,
                'p' => $apiKey,
                'sendWith' => 'WordPress'])),
            ['blocking' => true,]);

        if (is_wp_error($response)) {
            error_log($response->get_error_message());
            return null;
        }

        $body = $response['body'];

        if ($isJsonEndpoint) {
            $body = (array)json_decode($body, true);

            if ('sms' === $endpoint) {
                foreach ($body['messages'] as $k => $msg) {
                    $body['messages'][$k] = (array)$msg;
                }
            }
        }

        return stripslashes_deep($body);
    }

    /** @return string[] */
    static function getTableNames() {
        return array_map(function ($name) {
            global $wpdb;

            return "{$wpdb->prefix}sms77api_$name";
        }, self::TABLES);
    }

    /** @return bool */
    static function hasWooCommerce() {
        return in_array('woocommerce/woocommerce.php',
            apply_filters('active_plugins', get_option('active_plugins')), false);
    }

    /**
     * @param string $receivers
     * @return array|void
     */
    static function send($receivers) {
        if (!isset($_POST['submit'])) {
            return;
        }

        $msg = self::toString('msg');

        $errors = [];

        if ('' === $receivers) {
            $errors[] = 'Receivers cannot be missing.';
        }

        if ('' === $msg) {
            $errors[] = 'Message cannot be empty.';
        }

        if (count($errors)) {
            return [
                'errors' => $errors,
                'response' => null,
            ];
        }

        return [
            'errors' => $errors,
            'response' => self::sms([
                'debug' => self::toShortBool('debug'),
                'flash' => self::toShortBool('flash'),
                'from' => array_key_exists('from', $_POST) ? $_POST['from'] : null,
                'label' => array_key_exists('label', $_POST) ? $_POST['label'] : null,
                'performance_tracking' => self::toShortBool('performance_tracking'),
                'text' => $msg,
                'to' => $receivers,
                'ttl' => array_key_exists('ttl', $_POST) ? (int)$_POST['ttl'] : null,
                'udh' => array_key_exists('udh', $_POST) ? $_POST['udh'] : null,
                'unicode' => self::toShortBool('unicode'),
                'utf8' => self::toShortBool('utf8'),
            ]),
        ];
    }

    /**
     * @param array|string $receiversOrConfig
     * @return array|array[]
     */
    static function voice($receiversOrConfig) {
        global $wpdb;

        $errors = [];
        $isNew = is_string($receiversOrConfig);

        if ($isNew && !isset($_POST['submit'])) {
            $errors[] = 'Unexpected input! Please try again.';
        }

        $receivers = $isNew ? $receiversOrConfig : $receiversOrConfig['to'];
        $msg = $isNew ? self::toString('msg') : $receiversOrConfig['text'];

        if ('' === $receivers) {
            $errors[] = 'Receivers cannot be missing.';
        }

        if ('' === $msg) {
            $errors[] = 'Message cannot be empty.';
        }

        if (count($errors)) {
            return [
                'errors' => $errors,
                'response' => null,
            ];
        }

        $config = [
            'text' => $msg,
            'xml' => $isNew
                ? self::toShortBool('xml') : is_bool($receiversOrConfig['xml'])
                    ? $receiversOrConfig['xml'] : 0,
        ];
        $responses = [];
        foreach (explode(',', $receivers) as $receiver) {
            $config['to'] = $receiver;

            $response = self::get('voice', get_option('sms77api_key'), $config);
            $responses[] = $response;

            $wpdb->insert("{$wpdb->prefix}sms77api_voicemails", [
                'config' => json_encode($config),
                'response' => json_encode($response),
            ]);
        }

        return [
            'errors' => $errors,
            'response' => $responses,
        ];
    }

    /**
     * @param array $config
     * @return array|mixed|null
     */
    static function sms($config) {
        global $wpdb;

        $response = self::get('sms', get_option('sms77api_key'), $config);

        $wpdb->insert("{$wpdb->prefix}sms77api_messages", [
            'config' => json_encode($config),
            'response' => json_encode($response),
        ]);

        return $response;
    }

    /**
     * @param string $key
     * @return int
     */
    static function toShortBool($key) {
        return array_key_exists($key, $_POST) ? (int)((bool)$_POST[$key]) : 0;
    }

    /**
     * @param string $key
     * @return mixed|string
     */
    static function toString($key) {
        return array_key_exists($key, $_POST) ? $_POST[$key] : '';
    }
}