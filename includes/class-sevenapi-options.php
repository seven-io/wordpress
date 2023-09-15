<?php

/**
 * @property array sevenapi_debug
 * @property null[] sevenapi_delay
 * @property array sevenapi_flash
 * @property null[] sevenapi_label
 * @property array sevenapi_key
 * @property null[] sevenapi_msg
 * @property array sevenapi_performance_tracking
 * @property null[] sevenapi_udh
 * @property array sevenapi_unicode
 * @property null[] sevenapi_receivers
 * @property array sevenapi_ttl
 * @property array sevenapi_utf8
 * @link       http://www.seven.io
 * @package    sevenapi
 * @subpackage sevenapi/includes
 * @author     seven communications GmbH & Co. KG <support@seven.io>
 */
class sevenapi_Options {
    public function __construct() {
        foreach (get_class_methods($this) as $name) {
            if ('__construct' !== $name) {
                $this->{$name}();
            }
        }
    }

    /** @return void */
    private function debug() {
        $this->sevenapi_debug = [0, [], 'boolean'];
    }

    /** @return void */
    private function delay() {
        $this->sevenapi_delay = [null];
    }

    /** @return void */
    private function flash() {
        $this->sevenapi_flash = [0, [], 'boolean'];
    }

    /** @return void */
    private function label() {
        $this->sevenapi_label = [null];
    }

    /** @return void */
    private function key() {
        $this->sevenapi_key = [null, ['sanitize_callback' => function ($key) {
            $error = function ($msg) {
                add_settings_error('sevenapi_key',
                    'sevenapi_invalid_key', $msg);
            };

            $response = sevenapi_Util::get('balance', $key);

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
        $this->sevenapi_msg = [null];
    }

    /** @return void */
    private function performance_tracking() {
        $this->sevenapi_performance_tracking = [0, [], 'boolean'];
    }

    /** @return void */
    private function receivers() {
        $this->sevenapi_receivers = [null];
    }

    /** @return void */
    private function udh() {
        $this->sevenapi_udh = [null];
    }

    /** @return void */
    private function unicode() {
        $this->sevenapi_unicode = [0, [], 'boolean'];
    }

    /** @return void */
    private function utf8() {
        $this->sevenapi_utf8 = [0, [], 'boolean'];
    }

    /** @return void */
    private function ttl() {
        $this->sevenapi_ttl = [null, [], 'integer'];
    }
}
