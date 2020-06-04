<?php

/**
 * @property array sms77api_debug
 * @property null[] sms77api_delay
 * @property array sms77api_flash
 * @property null[] sms77api_label
 * @property array sms77api_key
 * @property null[] sms77api_msg
 * @property array sms77api_performance_tracking
 * @property null[] sms77api_udh
 * @property array sms77api_unicode
 * @property null[] sms77api_receivers
 * @property array sms77api_ttl
 * @property array sms77api_utf8
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */
class sms77api_Options {
    public function __construct() {
        foreach (get_class_methods($this) as $name) {
            if ('__construct' !== $name) {
                $this->{$name}();
            }
        }
    }

    /** @return void */
    private function debug() {
        $this->sms77api_debug = [0, [], 'boolean'];
    }

    /** @return void */
    private function delay() {
        $this->sms77api_delay = [null];
    }

    /** @return void */
    private function flash() {
        $this->sms77api_flash = [0, [], 'boolean'];
    }

    /** @return void */
    private function label() {
        $this->sms77api_label = [null];
    }

    /** @return void */
    private function key() {
        $this->sms77api_key = [null, ['sanitize_callback' => function ($key) {
            $error = function ($msg) {
                add_settings_error('sms77api_key',
                    'sms77api_invalid_key', $msg);
            };

            $response = sms77api_Util::get('balance', $key);

            if (!$response) {
                return $error('Internal error. Please try again later.');
            }

            if ('900' === $response) {
                return $error('Invalid API key or API down.');
            }

            return $key;
        },]];
    }

    /** @return void */
    private function msg() {
        $this->sms77api_msg = [null];
    }

    /** @return void */
    private function performance_tracking() {
        $this->sms77api_performance_tracking = [0, [], 'boolean'];
    }

    /** @return void */
    private function receivers() {
        $this->sms77api_receivers = [null];
    }

    /** @return void */
    private function udh() {
        $this->sms77api_udh = [null];
    }

    /** @return void */
    private function unicode() {
        $this->sms77api_unicode = [0, [], 'boolean'];
    }

    /** @return void */
    private function utf8() {
        $this->sms77api_utf8 = [0, [], 'boolean'];
    }

    /** @return void */
    private function ttl() {
        $this->sms77api_ttl = [null, [], 'integer'];
    }
}