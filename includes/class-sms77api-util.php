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

    /**
     * @param Base_Table $table
     */
    static function grid($table) {
        ?>
        <div class="wrap">
            <h1>sms77 - <?php echo $table->_args['_tpl']['title'] ?></h1>

            <?php self::defaultMessageElements() ?>

            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form method="POST">
                                <?php
                                $table->prepare_items();
                                $table->display();
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>
        </div>
        <?php
    }

    static function sms($config) {
        global $wpdb;

        $response = self::get('sms', get_option('sms77api_key'), $config);

        $wpdb->insert("{$wpdb->prefix}sms77api_messages", [
            'response' => json_encode($response),
            'config' => json_encode($config),
        ]);

        return $response;
    }

    static function send($receivers) {
        $msg = self::toString('msg');

        if (!isset($_POST['submit'])) {
            return;
        }

        $errors = [];

        if (!mb_strlen($receivers)) {
            $errors[] = 'Receivers cannot be missing.';
        }

        if (!mb_strlen($msg)) {
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

    static function toString($key) {
        return array_key_exists($key, $_POST) ? $_POST[$key] : '';
    }

    static function toShortBool($key) {
        return array_key_exists($key, $_POST) ? (int)((bool)$_POST[$key]) : 0;
    }

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
            $response = json_encode($_GET['response'], JSON_PRETTY_PRINT);
            echo "<b>Response:</b><pre>$response</pre>";
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