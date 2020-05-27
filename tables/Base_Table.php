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

/**
 * @property array __args
 */
class Base_Table extends WP_List_Table {
    public function __construct($entityName, $singular, $plural, $orderColumn) {
        parent::__construct([
            'singular' => __($singular, 'sms77api'),
            'plural' => __($plural, 'sms77api'),
            '_entityName' => $entityName,
            '_orderColumn' => $orderColumn,
            '_tpl' => ['title' => __($plural, 'sms77api'),],
        ]);
    }

    /**
     * @param array $item
     * @param string $columnName
     * @return mixed
     */
    public function column_default($item, $columnName) {
        return $item[$columnName];
    }

    /**
     * Render the bulk edit checkbox
     * @param array $item
     * @return string
     */
    function column_cb($item) {
        return "<input type='checkbox' name='row_action[]' value='{$item['id']}' />";
    }

    protected function _initPrepareItems() {
        $this->_column_headers = $this->get_column_info();

        if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {
            if (!wp_verify_nonce(
                filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING),
                'bulk-' . $this->_args['plural'])) {
                wp_die('SECURITY_CHECK_FAILED');
            }
        }
    }

    public function prepare_items() {
        global $wpdb;

        $perPage = $this->get_items_per_page("{$this->_args['_entityName']}_per_page", 5);

        $this->set_pagination_args([
            'total_items' =>
                $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}sms77api_{$this->_args['_entityName']}"),
            'per_page' => $perPage,
        ]);

        $this->items = self::getRows($perPage, $this->get_pagenum(), $this->_args['_entityName'], $this->_args['_orderColumn']);
    }

    /**
     * @param int $perPage
     * @param int $pageNumber
     * @param string $tableName
     * @param $orderColumn
     * @return mixed
     */
    public static function getRows($perPage, $pageNumber, $tableName, $orderColumn) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}sms77api_$tableName";

        if (empty($_REQUEST['orderby'])) {
            $sql .= " ORDER BY $orderColumn DESC"; //
        } else {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= empty($_REQUEST['order']) ? ' ASC' : ' ' . esc_sql($_REQUEST['order']);
        }

        $sql .= " LIMIT $perPage";
        $sql .= ' OFFSET ' . ($pageNumber - 1) * $perPage;

        return $wpdb->get_results($sql, 'ARRAY_A');
    }
}