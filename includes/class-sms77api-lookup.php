<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */
require_once 'class-sms77api-util.php';

class sms77api_Lookup {
    static function numbered($number, $type) {
        switch ($type) {
            case 'format':
                return self::format($number);
                break;
            case 'mnp':
                return self::mnp($number);
                break;
            default:
                return false;
                break;
        }
    }

    static function format($number) {
        return self::execute('format', $number, 'international', 'number_lookups');
    }

    static function mnp($number) {
        return self::execute('mnp', $number, 'number', 'mnp_lookups');
    }

    private static function execute($type, $number, $entityKey, $entityName) {
        global $wpdb;

        $response = sms77api_Util::get(
            'lookup', get_option('sms77api_key'), ['number' => $number, 'type' => $type]);

        if (true !== $response['success']) {
            error_log(is_array($response) || is_object($response)
                ? print_r($response, true) : $response);

            return false;
        }

        if ('mnp' === $type) {
            $response = (array)$response['mnp'];
        }

        if (empty($wpdb->get_col("SELECT $entityKey from {$wpdb->prefix}sms77api_$entityName"
            . " WHERE $entityKey = {$response[$entityKey]}"))) {
            return 1 === $wpdb->insert($wpdb->prefix . "sms77api_$entityName", $response)
                ? $response : false;
        }

        return is_int($wpdb->update($wpdb->prefix . "sms77api_$entityName",
            $response, [$entityKey => $response[$entityKey]])) ? $response : false;
    }
}