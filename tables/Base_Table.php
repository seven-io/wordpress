<?php

/**
 * @link       http://www.seven.io
 * @package    sevenapi
 * @subpackage sevenapi/tables
 * @author     seven communications GmbH & Co. KG <support@seven.io>
 */

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Base_Table extends WP_List_Table {
    /**
     * Base_Table constructor.
     * @param string $entityName
     * @param string $singular
     * @param string $plural
     * @param string $orderColumn
     * @param array $extra
     */
    public function __construct($entityName, $singular, $plural, $orderColumn, $extra = []) {
        parent::__construct(array_merge([
            'singular' => $singular,
            'plural' => $plural,
            '_entityName' => $entityName,
            '_orderColumn' => $orderColumn,
            '_tpl' => ['title' => $plural,],
        ], $extra));
    }

    /**
     * @param string $href
     * @return string
     */
    protected function jsRedirect($href) {
        return '<script>location.href = "' . $href . '"</script>';
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
     * @param array $item
     * @return string
     */
    function column_cb($item) {
        return "<input type='checkbox' name='row_action[]' value='{$item['id']}' />";
    }

    /** @return void */
    protected function _initPrepareItems() {
        $this->_column_headers = $this->get_column_info();

        if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce']) && !wp_verify_nonce(
                filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING),
                'bulk-' . $this->_args['plural'])) {
            wp_die('SECURITY_CHECK_FAILED');
        }
    }

    /** @return void */
    public function prepare_items() {
        global $wpdb;

        $perPage = $this->get_items_per_page("{$this->_args['_entityName']}_per_page", 5);

        $this->set_pagination_args([
            'total_items' =>
                $wpdb->get_var(
                    "SELECT COUNT(*) FROM {$wpdb->prefix}sevenapi_{$this->_args['_entityName']}"),
            'per_page' => $perPage,
        ]);

        $this->items = $this->getRows();
    }

    /** @return mixed */
    public function getRows() {
        $perPage = $this->get_pagination_arg('per_page');

        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}sevenapi_{$this->_args['_entityName']}";

        if (empty($_REQUEST['orderby'])) {
            $sql .= " ORDER BY {$this->_args['_orderColumn']} DESC";
        } else {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= empty($_REQUEST['order']) ? ' ASC' : ' ' . esc_sql($_REQUEST['order']);
        }

        $sql .= " LIMIT $perPage";
        $sql .= ' OFFSET ' . ($this->get_pagenum() - 1) * $perPage;

        return $wpdb->get_results($sql, 'ARRAY_A');
    }
}
