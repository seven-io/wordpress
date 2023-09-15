<?php

/**
 * @link       http://www.seven.io
 * @package    sevenapi
 * @subpackage sevenapi/tables
 * @author     seven communications GmbH & Co. KG <support@seven.io>
 */

require_once __DIR__ . '/Lookup_Table.php';

class HLR_Lookups_Table extends Lookup_Table {
    public function __construct() {
        parent::__construct(
            'hlr_lookups', 'HLR Lookup',
            'HLR Lookups', 'updated', 'international_format_number');
    }

    /** @return array */
    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'updated' => __('Updated', 'sevenapi'),
            'international_format_number' => __('International Format Number', 'sevenapi'),
            'status_message' => __('Status Message', 'sevenapi'),
            'lookup_outcome' => __('Lookup Outcome', 'sevenapi'),
            'lookup_outcome_message' => __('Lookup Outcome Message', 'sevenapi'),
            'international_formatted' => __('International Formatted', 'sevenapi'),
            'national_format_number' => __('National Format Number', 'sevenapi'),
            'country_code' => __('Country Code', 'sevenapi'),
            'country_code_iso3' => __('Country Code ISO3', 'sevenapi'),
            'country_prefix' => __('Country Prefix', 'sevenapi'),
            'current_carrier' => __('Current Carrier', 'sevenapi'),
            'original_carrier' => __('Original Carrier', 'sevenapi'),
            'valid_number' => __('Valid Number', 'sevenapi'),
            'reachable' => __('Reachable', 'sevenapi'),
            'ported' => __('Ported', 'sevenapi'),
            'roaming' => __('Roaming', 'sevenapi'),
        ];
    }

    /** @return array */
    function get_sortable_columns() {
        return [
            'updated' => ['updated', true],
            'international_format_number' => ['international_format_number', false],
            'status_message' => ['status_message', false],
            'lookup_outcome' => ['lookup_outcome', false],
            'lookup_outcome_message' => ['lookup_outcome_message', false],
            'international_formatted' => ['international_formatted', false],
            'national_format_number' => ['national_format_number', false],
            'country_code' => ['country_code', false],
            'country_code_iso3' => ['country_code_iso3', false],
            'country_prefix' => ['country_prefix', false],
            'current_carrier' => ['current_carrier', false],
            'original_carrier' => ['original_carrier', false],
            'valid_number' => ['valid_number', false],
            'reachable' => ['reachable', false],
            'ported' => ['ported', false],
            'roaming' => ['roaming', false],
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

        $this->_lookup('hlr');

        parent::prepare_items();
    }
}
