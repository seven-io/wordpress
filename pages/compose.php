<?php
if (!defined('WPINC')) {
    die;
}
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
        <input type='hidden' name='action' value='compose_hook'>

        <label>
            <span>
                <strong>Debug</strong>
                <small>validate parameters but do not send actual messages</small>
            </span>
            <input name="debug" style='margin: 0;'
                   type='checkbox' <?php echo (bool)get_option('sms77api_debug') ? 'checked' : ''; ?>/>
        </label>

        <label style='display: flex; flex-direction: column;'>
            <span>
                <strong>Receiver(s)</strong>
                <small>separated by comma eg: +4912345, +12345</small>
            </span>

            <input name="receivers"
                   value="<?php echo get_option('sms77api_receivers'); ?>" required/>
        </label>

        <label style='display: flex; flex-direction: column;'>
            <strong>Message</strong>

            <textarea name="msg"
                      required><?php echo get_option('sms77api_msg'); ?></textarea>
        </label>

        <?php submit_button('Send SMS'); ?>
    </form>
<?php else: ?>
    <p>An API Key is required for sending SMS. Please head to the
        <a href='<?php echo admin_url('options-general.php?page=sms77api') ?>'>Plugin Settings</a> to set it.
    </p>
<?php endif; ?>