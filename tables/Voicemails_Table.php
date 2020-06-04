<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/tables
 * @author     sms77 e.K. <support@sms77.io>
 */

require_once __DIR__ . '/Base_Table.php';

class Voicemails_Table extends Base_Table {
    public function __construct() {
        parent::__construct('voicemails', __('Voice Mail', 'sms77api'),
            __('Voice Mails', 'sms77api'), 'created');
    }

    /** @return array */
    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'response' => __('Response', 'sms77api'),
            'config' => __('Config', 'sms77api'),
            'created' => __('Created', 'sms77api'),
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
            'delete' => __('Delete', 'sms77api'),
            'resend' => __('Resend', 'sms77api'),
        ];
    }

    /** @return void */
    public function prepare_items() {
        global $wpdb;

        $this->_initPrepareItems();

        switch ($this->current_action()) {
            case 'resend':
                if (!isset($_POST['row_action'])) {
                    wp_redirect(esc_url(add_query_arg()));
                    die;
                }

                $errors = [];
                $responses = [];

                foreach ($_POST['row_action'] as $msgId) {
                    $sql = "SELECT config from {$wpdb->prefix}sms77api_voicemails WHERE id = $msgId";

                    try {
                        $responses[] = sms77api_Util::voice((array)json_decode($wpdb->get_row($sql)
                            ->config, true));
                    } catch (Exception $ex) {
                        $errors[] = $ex->getMessage();
                    }
                }

                die($this->jsRedirect(esc_url_raw(add_query_arg([
                    'errors' => $errors,
                    'response' => $responses,
                ]))));
                break;
            case 'delete':
                if (isset($_POST['row_action'])) {
                    foreach (esc_sql($_POST['row_action']) as $id) {
                        $wpdb->delete("{$wpdb->prefix}sms77api_voicemails", ['id' => $id], ['%d']);
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