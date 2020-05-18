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

define('SMS77API_VERSION', '1.0.0');

require plugin_dir_path(__FILE__) . 'includes/' . 'class-sms77api-util.php';

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

add_action('admin_menu', function() {
    add_options_page(
        'sms77 API Settings', 'sms77 API', 'manage_options', 'sms77api',
        function() {
            require_once __DIR__ . '/pages/settings.php';
        });

    add_menu_page(
        __('sms77 SMS', 'sms77api'),
        __('sms77 SMS', 'sms77api'),
        'manage_options',
        'sms77api-compose',
        function() {
            require_once __DIR__ . '/pages/compose.php';
        },
        'dashicons-email-alt2'
    );
});

function toString($key) {
    return isset($_POST[$key]) ? $_POST[$key] : '';
}

function toBool($key) {
    return isset($_POST[$key]) ? (int)(bool)$_POST[$key] : 0;
}

function sms($receivers, $msg) {
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

    return [
        'errors' => $errors,
        'response' => count($errors) ? null : sms77api_Util::get(
            'sms',
            get_option('sms77api_key'),
            [
                'debug' => toBool('debug'),
                'flash' => toBool('flash'),
                'label' => isset($_POST['label']) ? $_POST['label'] : null,
                'performance_tracking' => toBool('performance_tracking'),
                'text' => $msg,
                'to' => $receivers,
                'ttl' => isset($_POST['ttl']) ? (int)$_POST['ttl'] : null,
                'udh' => isset($_POST['udh']) ? $_POST['udh'] : null,
                'unicode' => toBool('unicode'),
                'utf8' => toBool('utf8'),
            ]
        ),
    ];
}

add_action('admin_post_sms77api_compose_hook', function() {
    $res = sms(toString('receivers'), toString('msg'));

    wp_redirect(admin_url('admin.php?' . http_build_query([
            'errors' => $res['errors'],
            'page' => 'sms77api-compose',
            'response' => $res['response'],
        ])));
});

add_action('admin_post_sms77api_wooc_bulk', function() {
    if (!isset($_POST['submit'])) {
        return;
    }

    $phones = [];
    foreach ((new WC_Order_Query())->get_orders() as $order) {
        /* @var WC_Order $order */
        $phones[] = $order->get_billing_phone();
    }

    $apiRes = sms(implode(',', array_unique($phones)), toString('msg'));

    wp_redirect(admin_url('admin.php?' . http_build_query([
            'errors' => $apiRes['errors'],
            'page' => 'sms77api-compose',
            'response' => $apiRes['response'],
        ])));
});

add_action('plugins_loaded', function() {
    load_plugin_textdomain(
        'sms77api',
        false,
        dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
    );
});