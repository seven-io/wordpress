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
    <h1>
        sms77 - <?php esc_html_e('Compose SMS', 'sms77api'); ?>
    </h1>

<?php require_once __DIR__ . '/../partials/responsable.php' ?>

<?php if (get_option('sms77api_key')): ?>
    <form method='POST' action='<?php echo admin_url('admin-post.php'); ?>'>
        <input type='hidden' name='action' value='sms77api_compose_hook'>

        <?php sms77api_Partials::all(false) ?>

        <label class='nostyle' style='display: flex; flex-direction: column;'>
            <?php sms77api_Partials::receivers(false) ?>
        </label>

        <label class='nostyle' style='display: flex; flex-direction: column;'>
            <?php sms77api_Partials::msg(false) ?>
        </label>

        <?php submit_button('Send SMS') ?>
    </form>

    <style>
        input {
            max-width: 200px;
        }

        form label:not(.nostyle) {
            justify-content: space-between;
        }

        textarea, input[name='receivers'] {
            width: 75%;
        }
    </style>
<?php endif; ?>