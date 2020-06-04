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
<div class='wrap'>
    <h1>sms77 - <?php _e('Voice Mails', 'sms77api') ?></h1>

    <?php sms77api_Partials::defaultMessageElements() ?>

    <?php if (get_option('sms77api_key')): ?>
        <form name='sms77api_voicemails' method='POST'
              action='<?php echo admin_url('admin-post.php'); ?>'>
            <input type='hidden' name='action' value='sms77api_voice_hook'>

            <label>
                <?php
                sms77api_Partials::checkboxSetting(
                    'xml',
                    __('XML', 'sms77api'),
                    false,
                    __('specifies whether given text is XML or not', 'sms77api'));
                ?>
            </label>

            <label class='nostyle' style='display: flex; flex-direction: column;'>
                <?php sms77api_Partials::receivers(false) ?>
            </label>

            <label class='nostyle' style='display: flex; flex-direction: column;'>
                <?php sms77api_Partials::text(false, false) ?>
            </label>

            <?php submit_button(__('Send Voice Mail', 'sms77api')) ?>
        </form>

        <style>
            form[name='sms77api_voicemails'] input {
                max-width: 200px;
            }

            form[name='sms77api_voicemails'] label:not(.nostyle) {
                justify-content: space-between;
            }

            form[name='sms77api_voicemails'] textarea,
            form[name='sms77api_voicemails'] input[name='receivers'] {
                width: 75%;
            }
        </style>
    <?php
    endif;
    sms77api_Partials::grid($this->voicemails_table, false);
    ?>
</div>