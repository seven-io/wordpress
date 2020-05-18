<?php
require_once plugin_dir_path(__FILE__) . 'includes/class-sms77api-util.php';
/**
 * @link       http://sms77.io
 * @package    sms77api
 */

if (!defined('WP_UNINSTALL_PLUGIN')) { // If uninstall not called from WordPress, then exit.
    exit;
}

foreach (sms77api_Util::getOptions() as $name => $v) {
    delete_option($name);
}