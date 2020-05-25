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

require_once __DIR__ . '/../includes/class-sms77api-util.php';
require_once __DIR__ . '/../includes/class-sms77api-partials.php';
?>
    <h1>
        sms77 - <?php _e('Compose SMS', 'sms77api'); ?>
    </h1>

<?php sms77api_Util::defaultMessageElements() ?>

<?php if (get_option('sms77api_key')): ?>
    <form name='sms77api_compose' method='POST' action='<?php echo admin_url('admin-post.php'); ?>'>
        <input type='hidden' name='action' value='sms77api_compose_hook'>

        <?php sms77api_Partials::all(false) ?>

        <label class='nostyle' style='display: flex; flex-direction: column;'>
            <?php sms77api_Partials::receivers(false) ?>
        </label>

        <label class='nostyle' style='display: flex; flex-direction: column;'>
            <?php sms77api_Partials::msg(false) ?>
        </label>

        <?php submit_button(__('Send SMS', 'sms77api')) ?>
    </form>

    <style>
        form[name='sms77api_compose'] input {
            max-width: 200px;
        }

        form[name='sms77api_compose'] label:not(.nostyle) {
            justify-content: space-between;
        }

        form[name='sms77api_compose'] textarea, form[name='sms77api_compose'] input[name='receivers'] {
            width: 75%;
        }
    </style>
<?php endif; ?>