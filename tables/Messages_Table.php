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
            'ajax' => false,
        ]);
    }

    public function no_items() {
        _e('There are no messages yet.', 'sms77api');
    }

    /**
     * @param array $item an array of DB data
     * @return string
     */
    function column_name($item) {
        return '<strong>' . $item['name'] . '</strong>' . $this->row_actions([
                'delete' => sprintf('<a href="?page=%s&action=%s&message=%s&_wpnonce=%s">Delete</a>',
                    esc_attr($_REQUEST['page']),
                    'delete',
                    absint($item['id']),
                    wp_create_nonce('sms77api_delete_message')),]);
    }

    /**
     * @param array $item
     * @param string $column_name
     * @return mixed
     */
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'response':
            case 'config':
            case 'created':
            case 'updated':
                return $item[$column_name];
            default:
                return '';
        }
    }

    /**
     * Render the bulk edit checkbox
     * @param array $item
     * @return string
     */
    function column_cb($item) {
        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']);
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
            'bulk-delete' => __('Delete', 'sms77api'),
        ];
    }

    public function prepare_items() {
        global $wpdb;

        $this->_column_headers = $this->get_column_info();

        $this->process_bulk_action();

        $per_page = $this->get_items_per_page('messages_per_page', 4);

        $this->set_pagination_args([
            'total_items' => $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sms77api_messages"),
            'per_page' => $per_page,
        ]);

        $this->items = self::getMessages($per_page, $this->get_pagenum());
    }

    private function process_bulk_action() {
        $redirect = function() {
            wp_redirect(esc_url(add_query_arg()));

            die;
        };

        if ('delete' === $this->current_action()) {
            if (!wp_verify_nonce(esc_attr($_REQUEST['_wpnonce']), 'sms77api_delete_message')) {
                die;
            }

            self::deleteMessage(absint($_GET['message']));

            $redirect();
        }

        // If the delete bulk action is triggered
        if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {
            foreach (esc_sql($_POST['bulk-delete']) as $id) {
                self::deleteMessage($id);
            }

            $redirect();
        }
    }

    /**
     * @param int $id message ID
     * @return bool|false|int
     */
    public static function deleteMessage($id) {
        global $wpdb;

        return $wpdb->delete("{$wpdb->prefix}sms77api_messages", ['id' => $id], ['%d']);
    }

    /**
     * @param int $perPage
     * @param int $pageNumber
     * @return mixed
     */
    public static function getMessages($perPage = 5, $pageNumber = 1) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}sms77api_messages";

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= empty($_REQUEST['order']) ? ' ASC' : ' ' . esc_sql($_REQUEST['order']);
        }

        $sql .= " LIMIT $perPage";
        $sql .= ' OFFSET ' . ($pageNumber - 1) * $perPage;

        return $wpdb->get_results($sql, 'ARRAY_A');
    }
}