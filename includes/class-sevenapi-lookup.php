<?php

/**
 * @link       http://www.seven.io
 * @package    sevenapi
 * @subpackage sevenapi/includes
 * @author     seven communications GmbH & Co. KG <support@seven.io>
 */
require_once 'class-sevenapi-util.php';

class sevenapi_Lookup {
    /**
     * @param string $number
     * @param string $type
     * @return array|bool|mixed|null
     */
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

    /**
     * @param string $number
     * @return array|bool|mixed|null
     */
    static function cnam($number) {
        return self::execute(
            'cnam', $number, 'number', 'sevenapi_cnam_lookups');
    }

    /**
     * @param string $number
     * @return array|bool|mixed|null
     */
    static function format($number) {
        return self::execute(
            'format', $number, 'international', 'sevenapi_number_lookups');
    }

    /**
     * @param string $number
     * @return array|bool|mixed|null
     */
    static function hlr($number) {
        return self::execute(
            'hlr', $number, 'international_format_number', 'sevenapi_hlr_lookups', 'status');
    }

    /**
     * @param string $number
     * @return array|bool|mixed|null
     */
    static function mnp($number) {
        return self::execute('mnp', $number, 'number', 'sevenapi_mnp_lookups');
    }

    /**
     * @param string $type
     * @param string $number
     * @param string $entityKey
     * @param string $entityName
     * @param string $successKey
     * @return array|bool|mixed|null
     */
    private static function execute($type, $number, $entityKey, $entityName, $successKey = 'success') {
        global $wpdb;

        $response = sevenapi_Util::get(
            'lookup', get_option('sevenapi_key'), ['number' => $number, 'type' => $type]);

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
