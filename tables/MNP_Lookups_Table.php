<?php

/**
 * @link       http://www.seven.io
 * @package    sevenapi
 * @subpackage sevenapi/tables
 * @author     seven communications GmbH & Co. KG <support@seven.io>
 */

require_once __DIR__ . '/Lookup_Table.php';

class MNP_Lookups_Table extends Lookup_Table {
    public function __construct() {
        parent::__construct(
            'mnp_lookups', 'MNP Lookup',
            'MNP Lookups', 'updated', 'number');
    }

    /** @return array */
    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'updated' => __('Updated', 'sevenapi'),
            'country' => __('Country', 'sevenapi'),
            'number' => __('Number', 'sevenapi'),
            'international_formatted' => __('International Formatted', 'sevenapi'),
            'national_format' => __('National Format', 'sevenapi'),
            'network' => __('Network', 'sevenapi'),
            'mccmnc' => __('MCCMNC', 'sevenapi'),
            'isPorted' => __('Is Ported', 'sevenapi'),
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
            'delete' => __('Delete', 'sevenapi'),
            'relookup' => __('Lookup again', 'sevenapi'),
        ];
    }

    /** @return void */
    function prepare_items() {
        $this->_initPrepareItems();

        $this->_lookup('mnp');

        parent::prepare_items();
    }
}
