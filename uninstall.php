<?php
/**
 * @link       http://sms77.io
 * @package    sms77api
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-sms77api-util.php';
foreach (sms77api_Util::getTableNames() as $tableName) {
    dbDelta("DROP TABLE IF EXISTS $tableName;");
}

delete_option('sms77api_db_version');
require_once plugin_dir_path(__FILE__) . 'includes/class-sms77api-options.php';
foreach ((array)new sms77api_Options as $name => $v) {
    delete_option($name);
}