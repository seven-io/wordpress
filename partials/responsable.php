<?php
/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/partials
 * @author     sms77 e.K. <support@sms77.io>
 */

if (!defined('WPINC')) {
    die;
}
?>

<?php if (count(isset($_GET['errors']) ? $_GET['errors'] : [])): ?>
    <b>Errors:</b>
    <pre><?php echo implode(PHP_EOL, $_GET['errors']) ?></pre>
<?php endif; ?>

<?php if (isset($_GET['response'])): ?>
    <b>Response:</b>
    <pre><?php echo json_encode($_GET['response'], JSON_PRETTY_PRINT) ?></pre>
<?php endif; ?>

<?php if (!get_option('sms77api_key')): ?>
    <p>An API Key is required for sending SMS. Please head to the
        <a href='<?php echo admin_url('options-general.php?page=sms77api') ?>'>Plugin Settings</a> to set it.
    </p>
<?php endif; ?>