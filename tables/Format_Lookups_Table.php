<?php

/**
 * @link       http://www.seven.io
 * @package    sevenapi
 * @subpackage sevenapi/tables
 * @author     seven communications GmbH & Co. KG <support@seven.io>
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
            'updated' => __('Updated', 'sevenapi'),
            'success' => __('Success', 'sevenapi'),
            'national' => __('National', 'sevenapi'),
            'international' => __('International', 'sevenapi'),
            'international_formatted' => __('International Formatted', 'sevenapi'),
            'country_name' => __('Country Name', 'sevenapi'),
            'country_code' => __('Country Code', 'sevenapi'),
            'country_iso' => __('Country ISO', 'sevenapi'),
            'carrier' => __('Carrier', 'sevenapi'),
            'network_type' => __('Network Type', 'sevenapi'),
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
            'delete' => __('Delete', 'sevenapi'),
            'relookup' => __('Lookup again', 'sevenapi'),
        ];
    }

    /** @return void */
    function prepare_items() {
        $this->_initPrepareItems();

        $this->_lookup('format');

        parent::prepare_items();
    }
}
