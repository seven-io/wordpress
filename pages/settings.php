<?php
if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/../includes/class-sms77api-partials.php';
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
        <?php sms77api_Partials::debug(true) ?>
    </label>

    <label style='display: flex; justify-content: space-between; margin-bottom: 10px;'>
        <?php sms77api_Partials::unicode(true) ?>
    </label>

    <label style='display: flex; justify-content: space-between; margin-bottom: 10px;'>
        <strong>Default Message</strong>
        <input name="sms77api_msg" value="<?php echo get_option('sms77api_msg'); ?>"/>
    </label>

    <label style='display: flex; justify-content: space-between;'>
        <?php sms77api_Partials::receivers(false, true); ?>
    </label>

    <?php submit_button(); ?>
</form>