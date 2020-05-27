<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/tables
 * @author     sms77 e.K. <support@sms77.io>
 */

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Messages_Table extends WP_List_Table {
    public function __construct() {
        parent::__construct([
            'singular' => __('Message', 'sms77api'),
            'plural' => __('Messages', 'sms77api'),
        ]);
    }

    /**
     * @param array $item
     * @param string $columnName
     * @return mixed
     */
    public function column_default($item, $columnName) {
        switch ($columnName) {
            case 'actions':
                return "<a href='?page={$_REQUEST['page']}&action=resend&message={$item['id']}'>Resend</a>";
            default:
                return $item[$columnName];
        }
    }

    /**
     * Render the bulk edit checkbox
     * @param array $item
     * @return string
     */
    function column_cb($item) {
        return "<input type='checkbox' name='row_action[]' value='{$item['id']}' />";
    }

    /** @return array */
    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'response' => __('Response', 'sms77api'),
            'config' => __('Config', 'sms77api'),
            'created' => __('Created', 'sms77api'),
            'updated' => __('Updated', 'sms77api'),
        ];
    }

    /** @return array */
    public function get_sortable_columns() {
        return [
            'created' => ['created', true],
            'updated' => ['updated', false],
        ];
    }

    /** @return array */
    public function get_bulk_actions() {
        return [
            'delete' => __('Delete', 'sms77api'),
            'resend' => __('Resend', 'sms77api'),
        ];
    }

    public function prepare_items() {
        global $wpdb;

        $this->_column_headers = $this->get_column_info();

        if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {
            if (!wp_verify_nonce(
                filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING),
                'bulk-' . $this->_args['plural'])) {
                wp_die('SECURITY_CHECK_FAILED');
            }

            switch ($this->current_action()) {
                case 'resend':
                    if (isset($_POST['row_action'])) {
                        $errors = [];
                        $responses = [];

                        foreach ($_POST['row_action'] as $msgId) {
                            try {
                                $responses[] = sms77api_Util::sms((array)json_decode($wpdb->get_row(
                                    "SELECT config from {$wpdb->prefix}sms77api_messages WHERE id = $msgId")
                                    ->config));
                            } catch (\Exception $ex) {
                                $errors[] = $ex->getMessage();
                            }
                        }

                        wp_redirect(esc_url_raw(add_query_arg([
                            'errors' => $errors,
                            'response' => $responses,
                        ])));
                        die;
                    }

                    wp_redirect(esc_url(add_query_arg()));
                    die;

                    break;
                case 'delete':
                    if (isset($_POST['row_action'])) {
                        foreach (esc_sql($_POST['row_action']) as $id) {
                            $wpdb->delete("{$wpdb->prefix}sms77api_messages", ['id' => $id], ['%d']);
                        }
                    }

                    wp_redirect(esc_url(add_query_arg()));
                    die;

                    break;
                default:
                    break;
            }
        }

        $perPage = $this->get_items_per_page('messages_per_page', 5);

        $this->set_pagination_args([
            'total_items' => $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sms77api_messages"),
            'per_page' => $perPage,
        ]);

        $this->items = self::getMessages($perPage, $this->get_pagenum());
    }

    /**
     * @param int $perPage
     * @param int $pageNumber
     * @return mixed
     */
    public static function getMessages($perPage, $pageNumber) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}sms77api_messages";

        if (empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY created DESC';
        } else {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= empty($_REQUEST['order']) ? ' ASC' : ' ' . esc_sql($_REQUEST['order']);
        }

        $sql .= " LIMIT $perPage";
        $sql .= ' OFFSET ' . ($pageNumber - 1) * $perPage;

        return $wpdb->get_results($sql, 'ARRAY_A');
    }
}