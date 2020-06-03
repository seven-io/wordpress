<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/tables
 * @author     sms77 e.K. <support@sms77.io>
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
            'updated' => __('Updated', 'sms77api'),
            'international_format_number' => __('International Format Number', 'sms77api'),
            'status_message' => __('Status Message', 'sms77api'),
            'lookup_outcome' => __('Lookup Outcome', 'sms77api'),
            'lookup_outcome_message' => __('Lookup Outcome Message', 'sms77api'),
            'international_formatted' => __('International Formatted', 'sms77api'),
            'national_format_number' => __('National Format Number', 'sms77api'),
            'country_code' => __('Country Code', 'sms77api'),
            'country_code_iso3' => __('Country Code ISO3', 'sms77api'),
            'country_prefix' => __('Country Prefix', 'sms77api'),
            'current_carrier' => __('Current Carrier', 'sms77api'),
            'original_carrier' => __('Original Carrier', 'sms77api'),
            'valid_number' => __('Valid Number', 'sms77api'),
            'reachable' => __('Reachable', 'sms77api'),
            'ported' => __('Ported', 'sms77api'),
            'roaming' => __('Roaming', 'sms77api'),
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
            'delete' => __('Delete', 'sms77api'),
            'relookup' => __('Lookup again', 'sms77api'),
        ];
    }

    function prepare_items() {
        $this->_initPrepareItems();

        $this->_lookup('hlr');

        parent::prepare_items();
    }
}