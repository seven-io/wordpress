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
<div class='wrap'>
    <h1>seven - <?php _e('Voice Mails', 'sevenapi') ?></h1>

    <?php sevenapi_Partials::defaultMessageElements() ?>

    <?php if (get_option('sevenapi_key')): ?>
        <form name='sevenapi_voicemails' method='POST'
              action='<?php echo admin_url('admin-post.php'); ?>'>
            <input type='hidden' name='action' value='sevenapi_voice_hook'>

            <label>
                <?php
                sevenapi_Partials::checkboxSetting(
                    'xml',
                    __('XML', 'sevenapi'),
                    false,
                    __('specifies whether given text is XML or not', 'sevenapi'));
                ?>
            </label>

            <label class='nostyle' style='display: flex; flex-direction: column;'>
                <?php sevenapi_Partials::receivers(false) ?>
            </label>

            <label class='nostyle' style='display: flex; flex-direction: column;'>
                <?php sevenapi_Partials::text(false, false) ?>
            </label>

            <?php submit_button(__('Send Voice Mail', 'sevenapi')) ?>
        </form>

        <style>
            form[name='sevenapi_voicemails'] input {
                max-width: 200px;
            }

            form[name='sevenapi_voicemails'] label:not(.nostyle) {
                justify-content: space-between;
            }

            form[name='sevenapi_voicemails'] textarea,
            form[name='sevenapi_voicemails'] input[name='receivers'] {
                width: 75%;
            }
        </style>
    <?php
    endif;
    sevenapi_Partials::grid($this->voicemails_table, false);
    ?>
</div>
