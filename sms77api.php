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
global $sms77api_db_version;
$sms77api_db_version = '1.0.0';
define('SMS77API_VERSION', '1.0.0');
$rootPath = plugin_dir_path(__FILE__);
require_once $rootPath . 'includes/' . 'class-sms77api-util.php';
require_once $rootPath . 'includes/' . 'class-sms77api-options.php';
require_once $rootPath . 'tables/' . 'Messages_Table.php';
require_once $rootPath . 'tables/' . 'Number_Lookups_Table.php';

/**
 * @property Messages_Table messages_table
 * @property Number_Lookups_Table number_lookups_table
 * @property string _charset
 */
class Sms77Api_Plugin {
    static $instance;

    public function __construct() {
        global $wpdb;
        $this->_charset = $wpdb->get_charset_collate();

        load_plugin_textdomain(
            'sms77api',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );

        $this->addActions();

        $this->registerActivationHook();

        add_filter('set-screen-option', function($status, $option, $value) {
            return $value;
        }, 10, 3);
    }

    private function addActions() {
        add_action('init', [$this, 'updateTables']);

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

            $messagesHook = add_submenu_page(
                'sms77api-menu',
                __('Messages', 'sms77io'),
                __('Messages', 'sms77io'),
                'manage_options',
                'sms77api-messages',
                function() {
                    require_once __DIR__ . '/pages/messages.php';
                }
            );
            add_action("load-$messagesHook", function() {
                add_screen_option('per_page', [
                    'label' => 'Messages',
                    'default' => 5,
                    'option' => 'messages_per_page',
                ]);

                $this->messages_table = new Messages_Table();
            });

            $numberLookupsHook = add_submenu_page(
                'sms77api-menu',
                __('Number Lookups', 'sms77io'),
                __('Number Lookups', 'sms77io'),
                'manage_options',
                'sms77api-number_lookups',
                function() {
                    if (!get_option('sms77api_key')) {
                        return;
                    }
                    ?>
                    <h2>Create new Number Lookup</h2>

                    <form method='POST' action='<?php echo admin_url('admin-post.php'); ?>'
                          style='display: flex; align-items: baseline'>
                        <input type='hidden' name='action' value='sms77api_number_lookup_hook'>

                        <input aria-label='<?php _e('Number to look up', 'sms77api') ?>'
                               placeholder='<?php _e('Number to look up', 'sms77api') ?>' name='number'/>

                        <?php submit_button(__('Lookup', 'sms77api')) ?>
                    </form>
                    <?php
                    sms77api_Util::grid($this->number_lookups_table);
                }
            );
            add_action("load-$numberLookupsHook", function() {
                add_screen_option('per_page', [
                    'label' => 'Number Lookups',
                    'default' => 5,
                    'option' => 'number_lookups_per_page',
                ]);

                $this->number_lookups_table = new Number_Lookups_Table();
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

        add_action('admin_post_sms77api_number_lookup_hook', function() {
            $errors = [];

            if (!isset($_POST['submit'])) {
                return;
            }

            $response = sms77api_Util::formatLookup($_POST['number']);
            if (!$response) {
                $errors[] = __('Failed to lookup number format.', 'sms77api');
            }

            wp_redirect(admin_url('admin.php?' . http_build_query([
                    'errors' => $errors,
                    'page' => 'sms77api-number_lookups',
                    'response' => $response,
                ])));
        });
    }

    private function registerActivationHook() {
        register_activation_hook(ABSPATH . 'wp-content/plugins/sms77api/sms77api.php', function() {
            global $wpdb;
            global $sms77api_db_version;

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';

            dbDelta("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sms77api_messages` (
                `id` MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
                `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `response` TEXT NOT NULL,
                `config` TEXT NOT NULL,
                PRIMARY KEY (`id`)
                ) $this->_charset;");


            add_option('sms77api_db_version', $sms77api_db_version);

            $this->updateTables();
        });
    }

    function updateTables() {
        global $wpdb;
        global $sms77api_db_version;
        $newVersion = '1.5.0';

        if (!(version_compare(get_option('sms77api_db_version', $sms77api_db_version), $newVersion) < 0)) {
            return;
        }

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}sms77api_number_lookups` (
                `id` TINYINT(9) AUTO_INCREMENT,
                `international` VARCHAR(255) UNIQUE NOT NULL,
                `success` TINYINT(1) NOT NULL,
                `national` VARCHAR(255) NOT NULL,
                `international_formatted` VARCHAR(255) NOT NULL,
                `country_name` VARCHAR(255) NOT NULL,
                `country_code` VARCHAR(4) NOT NULL,
                `country_iso` VARCHAR(4) NOT NULL,
                `carrier` VARCHAR(255) NOT NULL,
                `network_type` VARCHAR(24) NOT NULL,
                `updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
                ) $this->_charset;");

        update_option('sms77api_db_version', $newVersion);
    }

    static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

add_action('plugins_loaded', function() {
    Sms77Api_Plugin::get_instance();
});