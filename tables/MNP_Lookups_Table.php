<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/tables
 * @author     sms77 e.K. <support@sms77.io>
 */

require_once __DIR__ . '/Base_Table.php';

class MNP_Lookups_Table extends Base_Table {
    public function __construct() {
        parent::__construct(
            'mnp_lookups', 'MNP Lookup',
            'MNP Lookups', 'updated', 'number');
    }

    /** @return array */
    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'updated' => __('Updated', 'sms77api'),
            'country' => __('Country', 'sms77api'),
            'number' => __('Number', 'sms77api'),
            'international_formatted' => __('International Formatted', 'sms77api'),
            'national_format' => __('National Format', 'sms77api'),
            'network' => __('Network', 'sms77api'),
            'mccmnc' => __('MCCMNC', 'sms77api'),
            'isPorted' => __('Is Ported', 'sms77api'),
        ];
    }

    /** @return array */
    function get_sortable_columns() {
        return [
            'updated' => ['updated', true],
            'country' => ['country', false],
            'number' => ['number', false],
            'international_formatted' => ['international_formatted', false],
            'national_format' => ['national_format', false],
            'network' => ['network', false],
            'mccmnc' => ['mccmnc', false],
            'isPorted' => ['isPorted', false],
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

        $this->_lookup('mnp');

        parent::prepare_items();
    }
}