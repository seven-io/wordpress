<?php

/**
 * @link       http://www.seven.io
 * @package    sevenapi
 * @subpackage sevenapi/tables
 * @author     seven communications GmbH & Co. KG <support@seven.io>
 */

if (!class_exists('sevenapi_Lookup')) {
    require_once __DIR__ . '/../includes/class-sevenapi-lookup.php';
}

require_once __DIR__ . '/Base_Table.php';

class Lookup_Table extends Base_Table {
    /**
     * Lookup_Table constructor.
     * @param string $entityName
     * @param string $singular
     * @param string $plural
     * @param string $orderColumn
     * @param string $entityUnique
     */
    public function __construct($entityName, $singular, $plural, $orderColumn, $entityUnique) {
        parent::__construct($entityName, $singular, $plural, $orderColumn, [
            '_entityUnique' => $entityUnique,
        ]);
    }

    /**
     * @param string $type
     * @return void
     */
    protected function _lookup($type) {
        global $wpdb;

        switch ($this->current_action()) {
            case 'relookup':
                if (isset($_POST['row_action'])) {
                    $errors = [];
                    $responses = [];

                    foreach ($_POST['row_action'] as $lookupId) {
                        try {
                            $responses[] = sevenapi_Lookup::numbered($wpdb->get_col(
                                "SELECT {$this->_args['_entityUnique']} from {$wpdb->prefix}sevenapi_{$this->_args['_entityName']}"
                                . " WHERE id = $lookupId")[0], $type);
                        } catch (Exception $ex) {
                            $errors[] = $ex->getMessage();
                        }
                    }

                    die(
                    esc_url_raw(add_query_arg(['errors' => $errors, 'response' => $responses,])));
                }

                die($this->jsRedirect(esc_url(add_query_arg())));
                break;
            case 'delete':
                if (isset($_POST['row_action'])) {
                    foreach (esc_sql($_POST['row_action']) as $id) {
                        $wpdb->delete("{$wpdb->prefix}sevenapi_{$this->_args['_entityName']}",
                            ['id' => $id], ['%d']);
                    }
                }

                die($this->jsRedirect(esc_url(add_query_arg())));
                break;
            default:
                break;
        }
    }
}
