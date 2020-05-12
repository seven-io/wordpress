<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */
class sms77api {
    public function __construct() {
        $this->load_dependencies();
        $this->set_locale();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-sms77api-loader.php';

        new sms77api_Loader();
    }

    private function set_locale() {
        add_action('plugins_loaded', function() {
            load_plugin_textdomain(
                'sms77api',
                false,
                dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
            );
        });
    }
}