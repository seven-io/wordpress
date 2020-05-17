<?php
require_once __DIR__ . '/class-sms77api-util.php';

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */
class sms77api_Loader {
    public function __construct() {
        $this->admin_init();

        $this->admin_menu();

        $this->admin_post_compose_hook();
    }

    private function admin_init() {
        add_action('admin_init', function() {
            foreach (sms77api_Util::getOptions() as $name => $values) {
                add_option($name, $values[0]);

                register_setting(
                    'sms77api_general_settings',
                    $name, array_merge(
                    ['type' => isset($values[2]) ? $values[2] : 'string'],
                    isset($values[1]) ? $values[1] : []));
            }
        });
    }

    private function admin_menu() {
        add_action('admin_menu', function() {
            add_options_page(
                'sms77 API Settings', 'sms77 API', 'manage_options', 'sms77api',
                function() {
                    require_once __DIR__ . '/../pages/settings.php';
                });

            add_menu_page(
                __('sms77 SMS', 'sms77api'),
                __('sms77 SMS', 'sms77api'),
                'manage_options',
                'sms77api-compose',
                function() {
                    require_once __DIR__ . '/../pages/compose.php';
                },
                'dashicons-email-alt2'
            );
        });
    }

    private function admin_post_compose_hook() {
        add_action('admin_post_compose_hook', function() {
            if (!isset($_POST['submit'])) {
                return;
            }

            $errors = [];

            $receivers = $_POST['receivers'];
            if (!isset($receivers)) {
                $errors[] = 'Receivers cannot be missing.';
            }

            $msg = $_POST['msg'];
            if (!isset($msg)) {
                $errors[] = 'Message cannot be empty.';
            }

            if (!count($errors)) {
                $res = sms77api_Util::get(
                    'sms',
                    get_option('sms77api_key'),
                    [
                        'debug' =>(int)(bool)$_POST['debug'],
                        'text' => $msg,
                        'to' => $receivers,
                    ]
                );
            }

            wp_redirect(admin_url('admin.php?' . http_build_query([
                    'errors' => $errors,
                    'page' => 'sms77api-compose',
                    'response' => $res,
                ])));
        });
    }
}