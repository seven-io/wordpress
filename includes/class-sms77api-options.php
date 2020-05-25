<?php

/**
 * @property array debug
 * @property null[] delay
 * @property array flash
 * @property null[] label
 * @property array key
 * @property null[] msg
 * @property array performance_tracking
 * @property null[] udh
 * @property array unicode
 * @property null[] receivers
 * @property array ttl
 * @property array utf8
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

    private function debug() {
        $this->debug = [0, [], 'boolean'];
    }

    private function delay() {
        $this->delay = [null];
    }

    private function flash() {
        $this->flash = [0, [], 'boolean'];
    }

    private function label() {
        $this->label = [null];
    }

    private function key() {
        $this->key = [null, ['sanitize_callback' => function($key) {
                $error = function($msg) {
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

    private function msg() {
        $this->msg = [null];
    }

    private function performance_tracking() {
        $this->performance_tracking = [0, [], 'boolean'];
    }

    private function receivers() {
        $this->receivers =  [null];
    }

    private function udh() {
        $this->udh =  [null];
    }

    private function unicode() {
        $this->unicode =  [0, [], 'boolean'];
    }

    private function utf8() {
        $this->utf8 =  [0, [], 'boolean'];
    }

    private function ttl() {
        $this->ttl = [null, [], 'integer'];
    }
}