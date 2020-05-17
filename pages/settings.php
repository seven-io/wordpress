<?php
if (!defined('WPINC')) {
    die;
}
?>

<h1>sms77 API Settings</h1>

<h2>General</h2>

<form method="POST" action="options.php" style='display: flex; flex-direction: column;'>
    <?php settings_fields('sms77api_general_settings'); ?>

    <label style='display: flex; justify-content: space-between; margin-bottom: 10px;'>
        <strong>API Key</strong>
        <input required name="sms77api_key" value="<?php echo get_option('sms77api_key'); ?>"/>
    </label>

    <label style='display: flex; justify-content: space-between; margin-bottom: 10px;'>
        <span>
            <strong>Debug</strong>
            <small>validate parameters but do not send actual messages</small>
        </span>
        <input name="sms77api_debug" style='margin: 0;'
               type='checkbox' <?php echo (bool)get_option('sms77api_debug') ? 'checked' : ''; ?>/>
    </label>

    <label style='display: flex; justify-content: space-between; margin-bottom: 10px;'>
        <strong>Default Message</strong>
        <input name="sms77api_msg" value="<?php echo get_option('sms77api_msg'); ?>"/>
    </label>

    <label style='display: flex; justify-content: space-between;'>
        <span>
            <strong>Default Receiver(s)</strong>
            <small>separated by comma eg: +4912345, +12345</small>
        </span>
        <input name="sms77api_receivers" value="<?php echo get_option('sms77api_receivers'); ?>"/>
    </label>

    <?php submit_button(); ?>
</form>