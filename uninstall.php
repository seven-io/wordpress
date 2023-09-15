<?php
/**
 * @link       http://www.seven.io
 * @package    sevenapi
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-sevenapi-util.php';
foreach (sevenapi_Util::getTableNames() as $tableName) {
    dbDelta("DROP TABLE IF EXISTS $tableName;");
}

delete_option('sevenapi_db_version');
require_once plugin_dir_path(__FILE__) . 'includes/class-sevenapi-options.php';
foreach ((array)new sevenapi_Options as $name => $v) {
    delete_option($name);
}
