<?php

/**
 * @link       http://www.seven.io
 * @package    sevenapi
 * @subpackage sevenapi/tables
 * @author     seven communications GmbH & Co. KG <support@seven.io>
 */

require_once __DIR__ . '/Base_Table.php';

class Messages_Table extends Base_Table {
    public function __construct() {
        parent::__construct('messages', __('Message', 'sevenapi'),
            __('Messages', 'sevenapi'), 'created');
    }

    /** @return array */
    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'response' => __('Response', 'sevenapi'),
            'config' => __('Config', 'sevenapi'),
            'created' => __('Created', 'sevenapi'),
        ];
    }

    /** @return array */
    public function get_sortable_columns() {
        return [
            'created' => ['created', true],
        ];
    }

    /** @return array */
    public function get_bulk_actions() {
        return [
            'delete' => __('Delete', 'sevenapi'),
            'resend' => __('Resend', 'sevenapi'),
        ];
    }

    /** @return void */
    public function prepare_items() {
        global $wpdb;

        $this->_initPrepareItems();

        switch ($this->current_action()) {
            case 'resend':
                if (isset($_POST['row_action'])) {
                    $errors = [];
                    $responses = [];

                    foreach ($_POST['row_action'] as $msgId) {
                        try {
                            $responses[] = sevenapi_Util::sms((array)json_decode($wpdb->get_row(
                                "SELECT config from {$wpdb->prefix}sevenapi_messages WHERE id = $msgId")
                                ->config, true));
                        } catch (Exception $ex) {
                            $errors[] = $ex->getMessage();
                        }
                    }

                    die($this->jsRedirect(esc_url_raw(add_query_arg([
                        'errors' => $errors,
                        'response' => $responses,
                    ]))));
                }

                die($this->jsRedirect(esc_url(add_query_arg())));
                break;
            case 'delete':
                if (isset($_POST['row_action'])) {
                    foreach (esc_sql($_POST['row_action']) as $id) {
                        $wpdb->delete("{$wpdb->prefix}sevenapi_messages", ['id' => $id], ['%d']);
                    }
                }

                die($this->jsRedirect(esc_url(add_query_arg())));
                break;
            default:
                break;
        }

        parent::prepare_items();
    }
}
