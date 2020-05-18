<?php
if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/../includes/class-sms77api-partials.php';
?>
    <h1>
        <?php esc_html_e('Send SMS', 'sms77api'); ?>
    </h1>

<?php if (count(isset($_GET['errors']) ? $_GET['errors'] : [])): ?>
    <b>Errors:</b>
    <pre><?php echo implode(PHP_EOL, $_GET['errors']) ?></pre>
<?php endif; ?>

<?php if (isset($_GET['response'])): ?>
    <b>Response:</b>
    <pre><?php echo json_encode($_GET['response'], JSON_PRETTY_PRINT) ?></pre>
<?php endif; ?>

<?php if (get_option('sms77api_key')): ?>
    <form method='POST' action='<?php echo admin_url('admin-post.php'); ?>'>
        <input type='hidden' name='action' value='sms77api_compose_hook'>

        <label style='display: flex;'>
            <?php sms77api_Partials::debug(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::unicode(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::flash(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::performanceTracking(false) ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::utf8(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::udh(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::label(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::ttl(false); ?>
        </label>

        <label style='display: flex; flex-direction: column;'>
            <?php sms77api_Partials::receivers(false) ?>
        </label>

        <label style='display: flex; flex-direction: column;'>
            <?php sms77api_Partials::msg(false) ?>
        </label>

        <?php submit_button('Send SMS') ?>
    </form>
    <?php if (class_exists('WooCommerce')
        || in_array('woocommerce/woocommerce.php',
            apply_filters('active_plugins', get_option('active_plugins')))): ?>
        <h2>WooCommerce</h2>

        <h3>Bulk SMS</h3>
        <form method='POST' action='<?php echo admin_url('admin-post.php'); ?>'>
            <input type='hidden' name='action' value='sms77api_wooc_bulk'>

            <?php sms77api_Partials::msg(false) ?>

            <?php submit_button('Send Bulk') ?>
        </form>
    <?php endif; ?>
<?php else: ?>
    <p>An API Key is required for sending SMS. Please head to the
        <a href='<?php echo admin_url('options-general.php?page=sms77api') ?>'>Plugin Settings</a> to set it.
    </p>
<?php endif; ?>