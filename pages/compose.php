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
require_once __DIR__ . '/../includes/class-sms77api-util.php';
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
        <input type='hidden' name='action' value='sms77api_compose_hook'>

        <label style='display: flex;'>
            <?php sms77api_Partials::debug(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::unicode(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::flash(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::performanceTracking(false) ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::utf8(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::udh(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::label(false); ?>
        </label>

        <label style='display: flex;'>
            <?php sms77api_Partials::ttl(false); ?>
        </label>

        <label style='display: flex; flex-direction: column;'>
            <?php sms77api_Partials::receivers(false) ?>
        </label>

        <label style='display: flex; flex-direction: column;'>
            <?php sms77api_Partials::msg(false) ?>
        </label>

        <?php submit_button('Send SMS') ?>
    </form>
    <?php if (in_array('woocommerce/woocommerce.php',
        apply_filters('active_plugins', get_option('active_plugins')))): ?>
        <h2>WooCommerce</h2>

        <h3>Bulk SMS</h3>
        <form method='POST' action='<?php echo admin_url('admin-post.php'); ?>'>
            <input type='hidden' name='action' value='sms77api_wooc_bulk'>

            <h4>Filters</h4>

            <div style='display: flex; align-items: flex-end;'>
                <label style='display: flex; flex-direction: column;'>
                    <strong>Action</strong>

                    <select name='date_action'>
                        <option></option>
                        <?php foreach (sms77api_Util::WOOC_BULK_FILTER_DATE_ACTIONS as $ACTION): ?>
                            <option value='<?php echo $ACTION ?>'><?php echo ucfirst($ACTION) ?></option>
                        <?php endforeach ?>
                    </select>
                </label>

                <label style='display: flex; flex-direction: column;'>
                    <strong>Modificator</strong>

                    <select name='date_modificator'>
                        <option></option>
                        <?php foreach (sms77api_Util::WOOC_BULK_FILTER_DATE_MODIFICATORS as $MODIFICATOR): ?>
                            <option value='<?php echo $MODIFICATOR ?>'><?php echo $MODIFICATOR ?></option>
                        <?php endforeach ?>
                    </select>
                </label>

                <label style='display: flex; flex-direction: column;'>
                    <strong>Date</strong>

                    <input class='datepicker' name='date'/>
                </label>

                <label style='flex-direction: column; display: none;'>
                    <strong>Date to</strong>

                    <input class='datepicker' name='date_to'/>
                </label>
            </div>

            <label style='display: flex; flex-direction: column;'>
                <?php sms77api_Partials::msg(false) ?>
            </label>

            <?php submit_button('Send Bulk') ?>
        </form>
        <script>
            jQuery(function () {
                jQuery('.datepicker').datepicker({dateFormat: 'yy-dd-mm'});

                const $dateLabel = document.querySelector('input[name="date"]').previousElementSibling;
                const dateText = $dateLabel.innerText.trim();

                const $dateTo = document.querySelector('input[name="date_to"]');
                document.querySelector('select[name="date_modificator"]').addEventListener('change', ev => {
                    const isBetween = '...' === ev.target.value;
                    $dateTo.parentElement.style.display = isBetween ? 'flex' : 'none';
                    $dateLabel.innerText = isBetween ? `${dateText} from`.trim() : dateText;
                });
            });
        </script>

    <?php endif; ?>
<?php else: ?>
    <p>An API Key is required for sending SMS. Please head to the
        <a href='<?php echo admin_url('options-general.php?page=sms77api') ?>'>Plugin Settings</a> to set it.
    </p>
<?php endif; ?>