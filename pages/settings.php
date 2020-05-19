<?php
/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/pages
 * @author     sms77 e.K. <support@sms77.io>
 */

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/../includes/class-sms77api-partials.php';
?>
<h1>sms77 - Settings</h1>

<h2>General</h2>

<form method="POST" action="options.php" style='display: flex; flex-direction: column;'>
    <?php settings_fields('sms77api_general_settings'); ?>

    <label style='display: flex'>
        <span>
            <strong>API Key</strong>
            <small>required for sending SMS - get yours @ <a href='http://sms77.io'>sms77.io</a></small>
        </span>

        <input required name="sms77api_key" value="<?php echo get_option('sms77api_key'); ?>"/>
    </label>

    <?php
    sms77api_Partials::all(true);

    sms77api_Partials::msg(true);
    sms77api_Partials::receivers(true);

    submit_button();
    ?>
</form>

<style>
    form label {
        justify-content: space-between;
    }

    form label:not(:last-of-type) {
        margin-bottom: 10px;
    }
</style>