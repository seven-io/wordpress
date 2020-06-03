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
            case 'cnam':
                return self::cnam($number);
                break;
            case 'format':
                return self::format($number);
                break;
            case 'hlr':
                return self::hlr($number);
                break;
            case 'mnp':
                return self::mnp($number);
                break;
            default:
                return false;
                break;
        }
    }

    static function cnam($number) {
        return self::execute(
            'cnam', $number, 'number', 'sms77api_cnam_lookups');
    }

    static function format($number) {
        return self::execute(
            'format', $number, 'international', 'sms77api_number_lookups');
    }

    static function hlr($number) {
        return self::execute(
            'hlr', $number, 'international_format_number', 'sms77api_hlr_lookups', 'status');
    }

    static function mnp($number) {
        return self::execute('mnp', $number, 'number', 'sms77api_mnp_lookups');
    }

    private static function execute($type, $number, $entityKey, $entityName, $successKey = 'success') {
        global $wpdb;

        $response = sms77api_Util::get(
            'lookup', get_option('sms77api_key'), ['number' => $number, 'type' => $type]);

        $response = (array)$response;

        if (true !== $response[$successKey] && 'true' !== $response[$successKey]) {
            error_log(is_array($response) || is_object($response)
                ? print_r($response, true) : $response);

            return false;
        }

        switch ($type) {
            case 'cnam':
                unset($response['success'], $response['code']);
                break;
            case 'hlr':
                $response['current_carrier'] =
                    json_encode($response['current_carrier'], JSON_UNESCAPED_UNICODE);
                $response['original_carrier'] =
                    json_encode($response['original_carrier'], JSON_UNESCAPED_UNICODE);
                $response['status'] = true === $response['status'] ? 1 : 0;
                $response['lookup_outcome'] = true === $response['lookup_outcome'] ? 1 : 0;
                break;
            case 'mnp':
                $response = (array)$response['mnp'];
                break;
            default:
                break;
        }

        if (empty($wpdb->get_col("SELECT $entityKey FROM {$wpdb->prefix}$entityName"
            . " WHERE $entityKey = {$response[$entityKey]}"))) {
            return 1 === $wpdb->insert($wpdb->prefix . $entityName, $response)
                ? $response : false;
        }

        return is_int($wpdb->update($wpdb->prefix . $entityName,
            $response, [$entityKey => $response[$entityKey]])) ? $response : false;
    }
}