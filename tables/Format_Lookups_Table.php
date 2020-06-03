<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/tables
 * @author     sms77 e.K. <support@sms77.io>
 */

require_once __DIR__ . '/Lookup_Table.php';

class Format_Lookups_Table extends Lookup_Table {
    public function __construct() {
        parent::__construct(
            'number_lookups', 'Format Lookup',
            'Format Lookups', 'updated', 'international');
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
        $this->_initPrepareItems();

        $this->_lookup('format');

        parent::prepare_items();
    }
}