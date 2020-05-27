<?php
/**
 * @link       http://sms77.io
 * @package    sms77api
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
dbDelta("DROP TABLE IF EXISTS {$wpdb->prefix}sms77api_messages;");
dbDelta("DROP TABLE IF EXISTS {$wpdb->prefix}sms77api_number_lookups;");

delete_option('sms77api_db_version');
require_once plugin_dir_path(__FILE__) . 'includes/class-sms77api-options.php';
foreach ((array)new sms77api_Options as $name => $v) {
    delete_option($name);
}