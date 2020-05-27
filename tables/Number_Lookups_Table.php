<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/tables
 * @author     sms77 e.K. <support@sms77.io>
 */

require_once __DIR__ . '/Base_Table.php';

class Number_Lookups_Table extends Base_Table {
    public function __construct() {
        parent::__construct(
            'number_lookups', 'Number Lookup',
            'Number Lookups', 'updated');
    }

    /** @return array */
    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'updated' => __('Updated', 'sms77api'),
            'success' => __('Success', 'sms77api'),
            'national' => __('National', 'sms77api'),
            'international' => __('International', 'sms77api'),
            'international_formatted' => __('International Formatted', 'sms77api'),
            'country_name' => __('Country Name', 'sms77api'),
            'country_code' => __('Country Code', 'sms77api'),
            'country_iso' => __('Country ISO', 'sms77api'),
            'carrier' => __('Carrier', 'sms77api'),
            'network_type' => __('Network Type', 'sms77api'),
        ];
    }

    /** @return array */
    function get_sortable_columns() {
        return [
            'updated' => ['updated', true],
            'success' => ['success', false],
            'national' => ['national', false],
            'international' => ['international', false],
            'international_formatted' => ['international_formatted', false],
            'country_name' => ['country_name', false],
            'country_code' => ['country_code', false],
            'country_iso' => ['country_iso', false],
            'carrier' => ['carrier', false],
            'network_type' => ['network_type', false],
        ];
    }

    /** @return array */
    function get_bulk_actions() {
        return [
            'delete' => __('Delete', 'sms77api'),
            'relookup' => __('Lookup again', 'sms77api'),
        ];
    }

    function prepare_items() {
        global $wpdb;

        $this->_initPrepareItems();

        switch ($this->current_action()) {
            case 'relookup':
                if (isset($_POST['row_action'])) {
                    $errors = [];
                    $responses = [];

                    foreach ($_POST['row_action'] as $nrLookupId) {
                        try {
                            $responses[] = sms77api_Util::formatLookup($wpdb->get_col(
                                "SELECT international from {$wpdb->prefix}sms77api_number_lookups"
                                . " WHERE id = $nrLookupId")[0]);
                        } catch (\Exception $ex) {
                            $errors[] = $ex->getMessage();
                        }
                    }

                    wp_redirect(esc_url_raw(add_query_arg(['errors' => $errors, 'response' => $responses,])));
                    die;
                }

                wp_redirect(esc_url(add_query_arg()));
                die;

                break;
            case 'delete':
                if (isset($_POST['row_action'])) {
                    foreach (esc_sql($_POST['row_action']) as $id) {
                        $wpdb->delete("{$wpdb->prefix}sms77api_number_lookups", ['id' => $id], ['%d']);
                    }
                }

                wp_redirect(esc_url(add_query_arg()));
                die;

                break;
            default:
                break;
        }

        parent::prepare_items();
    }
}