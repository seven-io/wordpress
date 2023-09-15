<?php
/**
 * @link       http://www.seven.io
 * @package    sevenapi
 * @subpackage sevenapi/pages
 * @author     seven communications GmbH & Co. KG <support@seven.io>
 */

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/../includes/class-sevenapi-partials.php';
?>
<h1>seven - <?php _e('Settings', 'sevenapi') ?></h1>

<h2><?php _e('General', 'sevenapi') ?></h2>

<form name='sevenapi_settings' method="POST" action="options.php"
      style='display: flex; flex-direction: column;'>
    <?php settings_fields('sevenapi_general_settings'); ?>

    <label style='display: flex'>
        <span>
            <strong><?php _e('API Key', 'sevenapi') ?></strong>
            <small>
                <?php _e('required for sending SMS - get yours @ ', 'sevenapi') ?>
                <a href='https://www.seven.io'>seven.io</a></small>
        </span>

        <input required name="sevenapi_key" value="<?php echo get_option('sevenapi_key'); ?>"/>
    </label>

    <?php
    sevenapi_Partials::all(true);

    sevenapi_Partials::text(true);
    sevenapi_Partials::receivers(true);

    submit_button();
    ?>
</form>

<style>
    form[name='sevenapi_settings'] label {
        justify-content: space-between;
    }

    form[name='sevenapi_settings'] label:not(:last-of-type) {
        margin-bottom: 10px;
    }
</style>
