<?php

/**
 * @link       http://www.seven.io
 * @package    sevenapi
 * @subpackage sevenapi/tables
 * @author     seven communications GmbH & Co. KG <support@seven.io>
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
            'updated' => __('Updated', 'sevenapi'),
            'number' => __('Number', 'sevenapi'),
            'name' => __('Name', 'sevenapi'),
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
            'delete' => __('Delete', 'sevenapi'),
            'relookup' => __('Lookup again', 'sevenapi'),
        ];
    }

    /** @return void */
    function prepare_items() {
        $this->_initPrepareItems();

        $this->_lookup('cnam');

        parent::prepare_items();
    }
}
