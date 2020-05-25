<?php
/**
 * @link       http://sms77.io
 * @package    sms77api
 */

if (!defined('WP_UNINSTALL_PLUGIN')) { // If uninstall not called from WordPress, then exit.
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/class-sms77api-options.php';

foreach ((array)new sms77api_Options as $name => $v) {
    delete_option($name);
}