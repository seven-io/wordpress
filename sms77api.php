<?php

/**
 * This plugin bootstrap file is read by WordPress to generate the plugin information in the plugin admin area.
 * Also includes all dependencies, registers (de)activation functions and defines a boostrap function.
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

define('SMS77API_VERSION', '1.0.0');
define('SMS77API_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('SMS77API_PLUGIN_DIR_INCLUDES_PATH', SMS77API_PLUGIN_DIR_PATH . 'includes/');

require SMS77API_PLUGIN_DIR_INCLUDES_PATH . 'class-sms77api.php';

new sms77api();