<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/tables
 * @author     sms77 e.K. <support@sms77.io>
 */

if (!class_exists('sms77api_Lookup')) {
    require_once __DIR__ . '/../includes/class-sms77api-lookup.php';
}

require_once __DIR__ . '/Base_Table.php';

class Lookup_Table extends Base_Table {
    public function __construct($entityName, $singular, $plural, $orderColumn, $entityUnique) {
        parent::__construct($entityName, $singular, $plural, $orderColumn, [
            '_entityUnique' => $entityUnique,
        ]);
    }

    protected function _lookup($type) {
        global $wpdb;

        switch ($this->current_action()) {
            case 'relookup':
                if (isset($_POST['row_action'])) {
                    $errors = [];
                    $responses = [];

                    foreach ($_POST['row_action'] as $lookupId) {
                        try {
                            $responses[] = sms77api_Lookup::numbered($wpdb->get_col(
                                "SELECT {$this->_args['_entityUnique']} from {$wpdb->prefix}sms77api_{$this->_args['_entityName']}"
                                . " WHERE id = $lookupId")[0], $type);
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
                        $wpdb->delete("{$wpdb->prefix}sms77api_{$this->_args['_entityName']}",
                            ['id' => $id], ['%d']);
                    }
                }

                wp_redirect(esc_url(add_query_arg()));
                die;

                break;
            default:
                break;
        }
    }
}