<?php

/**
 * @link              http://sms77.io
 * @package           sms77api
 * @wordpress-plugin
 * Plugin Name:       sms77 API
 * Plugin URI:        http://github.com/sms77io/wp-api
 * Description:       Send SMS through the sms77.io gateway.
 * Version:           1.0.0
 * Author:            sms77 e.K.
 * Author URI:        http://sms77.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sms77api
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}
global $sms77_db_version;
$sms77_db_version = '1.0.0';
define('SMS77API_VERSION', '1.0.0');
$rootPath = plugin_dir_path(__FILE__);
require_once $rootPath . 'includes/' . 'class-sms77api-util.php';
require_once $rootPath . 'includes/' . 'class-sms77api-options.php';
require_once $rootPath . 'tables/' . 'Messages_Table.php';

class Sms77Api_Plugin {
    static $instance;

    public $messages_table;

    public function __construct() {
        load_plugin_textdomain(
            'sms77api',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );

        add_action('admin_init', function() {
            foreach ((array)new sms77api_Options as $name => $values) {
                add_option($name, $values[0]);

                register_setting(
                    'sms77api_general_settings',
                    $name, array_merge(
                    ['type' => isset($values[2]) ? $values[2] : 'string'],
                    isset($values[1]) ? $values[1] : []));
            }
        });

        add_action('admin_menu', function() {
            add_options_page(
                'sms77 API Settings', 'sms77 API Settings', 'manage_options', 'sms77api',
                function() {
                    require_once __DIR__ . '/pages/settings.php';
                });

            add_menu_page(
                'Sms77.io',
                'Sms77.io',
                'manage_options',
                'sms77api-menu',
                function() {
                    header("Location: http://sms77.io");
                    die;
                },
                'dashicons-email-alt2'
            );

            $hook = add_submenu_page(
                'sms77api-menu',
                __('Messages', 'sms77io'),
                __('Messages', 'sms77io'),
                'manage_options',
                'sms77api-messages',
                function() {
                    require_once __DIR__ . '/pages/messages.php';
                }
            );

            add_action("load-$hook", function() {
                add_screen_option('per_page', [
                    'label' => 'Messages',
                    'default' => 5,
                    'option' => 'messages_per_page',
                ]);

                $this->messages_table = new Messages_Table();
            });

            add_submenu_page('sms77api-menu', 'Write SMS', 'Write SMS',
                'manage_options', 'sms77api-compose', function() {
                    require_once __DIR__ . '/pages/compose.php';
                });

            if (sms77api_Util::hasWooCommerce()) {
                add_submenu_page('sms77api-menu', 'WooCommerce Bulk', 'WooCommerce Bulk',
                    'manage_options', 'sms77api-wooc', function() {
                        require_once __DIR__ . '/pages/woocommerce.php';
                    });
            }
        });

        add_action('admin_post_sms77api_compose_hook', function() {
            $res = sms77api_Util::send(sms77api_Util::toString('receivers'));

            wp_redirect(admin_url('admin.php?' . http_build_query([
                    'errors' => $res['errors'],
                    'page' => 'sms77api-compose',
                    'response' => $res['response'],
                ])));
        });

        add_action('admin_enqueue_scripts', function() {
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('sms77api-admin-ui-css',
                'http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css',
                false, "1.12.1", false);
        });

        add_action('admin_post_sms77api_wooc_bulk', function() {
            if (!isset($_POST['submit'])) {
                return;
            }

            $date = isset($_POST['date']) ? $_POST['date'] : null;
            $dateModificator = isset($_POST['date_modificator']) ? $_POST['date_modificator'] : null;
            $dateAction = isset($_POST['date_action']) ? $_POST['date_action'] : null;
            $args = [];
            if ($date && $dateAction && $dateModificator) {
                if ('...' === $dateModificator) {
                    $dateTo = isset($_POST['date_to']) ? $_POST['date_to'] : null;
                    if (!$dateTo) {
                        return wp_redirect(admin_url('admin.php?' . http_build_query([
                                'errors' => ['To-Date must be set if using the "..." modificator.'],
                                'page' => 'sms77api-wooc',
                                'response' => null,
                            ])));
                    }

                    $search = "$date...$dateTo";
                } else {
                    $search = "$dateModificator$date";
                }

                $args["date_$dateAction"] = $search;
            }

            $phones = [];
            foreach (
                (new WC_Order_Query($args))
                    ->get_orders() as $order) {
                /* @var WC_Order $order */
                $phones[] = $order->get_billing_phone();
            }

            $apiRes = sms77api_Util::send(implode(',', array_unique($phones)));

            wp_redirect(admin_url('admin.php?' . http_build_query([
                    'errors' => $apiRes['wooc'],
                    'page' => 'sms77api-compose',
                    'response' => $apiRes['response'],
                ])));
        });

        register_activation_hook(__FILE__, function() {
            global $wpdb;
            global $sms77_db_version;
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';

            $table_name = $wpdb->prefix . "sms77api_messages";
            $charset_collate = $wpdb->get_charset_collate();
            dbDelta("CREATE TABLE IF NOT EXISTS `$table_name` (
      `id` MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
      `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `response` TEXT NOT NULL,
      `config` TEXT NOT NULL,
      PRIMARY KEY (`id`)
    ) $charset_collate;");

            add_option('sms77api_db_version', $sms77_db_version);
        });

        add_filter('set-screen-option', function($status, $option, $value) {
            return $value;
        }, 10, 3);
    }

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

add_action('plugins_loaded', function() {
    Sms77Api_Plugin::get_instance();
});