<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/tables
 * @author     sms77 e.K. <support@sms77.io>
 */

require_once __DIR__ . '/Lookup_Table.php';

class CNAM_Lookups_Table extends Lookup_Table {
    public function __construct() {
        parent::__construct(
            'cnam_lookups', 'CNAM Lookup',
            'CNAM Lookups', 'updated', 'number');
    }

    /** @return array */
    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'updated' => __('Updated', 'sms77api'),
            'number' => __('Number', 'sms77api'),
            'name' => __('Name', 'sms77api'),
        ];
    }

    /** @return array */
    function get_sortable_columns() {
        return [
            'updated' => ['updated', true],
            'number' => ['number', false],
            'name' => ['name', false],
        ];
    }

    /** @return array */
    function get_bulk_actions() {
        return [
            'delete' => __('Delete', 'sms77api'),
            'relookup' => __('Lookup again', 'sms77api'),
        ];
    }

    /** @return void */
    function prepare_items() {
        $this->_initPrepareItems();

        $this->_lookup('cnam');

        parent::prepare_items();
    }
}