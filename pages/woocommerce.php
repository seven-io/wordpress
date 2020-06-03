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
    <h1>sms77 - WooCommerce</h1>

<?php sms77api_Partials::defaultMessageElements() ?>

<?php if (get_option('sms77api_key') && sms77api_Util::hasWooCommerce()): ?>
    <h2>
        <?php _e('Send Bulk', 'sms77api') ?>
    </h2>
    <form name='sms77api_wooc' method='POST' action='<?php echo admin_url('admin-post.php'); ?>'>
        <input type='hidden' name='action' value='sms77api_wooc_bulk'>

        <?php sms77api_Partials::all(false) ?>

        <h3>Filters</h3>
        <div style='display: flex; align-items: flex-end;'>
            <label style='display: flex; flex-direction: column;'>
                <strong><?php _e('Action', 'sms77api') ?></strong>

                <select name='date_action'>
                    <option></option>
                    <?php foreach (sms77api_Util::WOOC_BULK_FILTER_DATE_ACTIONS as $ACTION): ?>
                        <option value='<?php echo $ACTION ?>'><?php _e(ucfirst($ACTION), 'sms77api') ?></option>
                    <?php endforeach ?>
                </select>
            </label>

            <label style='display: flex; flex-direction: column;'>
                <strong><?php _e('Modificator', 'sms77api') ?></strong>

                <select name='date_modificator'>
                    <option></option>
                    <?php foreach (sms77api_Util::WOOC_BULK_FILTER_DATE_MODIFICATORS as $MODIFICATOR): ?>
                        <option value='<?php echo $MODIFICATOR ?>'><?php echo $MODIFICATOR ?></option>
                    <?php endforeach ?>
                </select>
            </label>

            <label style='display: flex; flex-direction: column;'>
                <strong><?php _e('Date', 'sms77api') ?></strong>

                <input class='datepicker' name='date'/>
            </label>

            <label style='flex-direction: column; display: none;'>
                <strong><?php _e('Date to', 'sms77api') ?></strong>

                <input class='datepicker' name='date_to'/>
            </label>
        </div>

        <label style='flex-direction: column;'>
            <?php sms77api_Partials::msg(false) ?>
        </label>

        <?php submit_button(__('Send Bulk', 'sms77api')) ?>
    </form>
    <script>
        jQuery(function () {
            jQuery('.datepicker').datepicker({dateFormat: 'yy-dd-mm'});

            const $form = document.querySelector('form[name="sms77api_wooc"]');
            const $dateLabel = $form.querySelector('input[name="date"]').previousElementSibling;
            const dateText = $dateLabel.innerText.trim();
            const $dateTo = $form.querySelector('input[name="date_to"]');

            document.querySelector('select[name="date_modificator"]').addEventListener('change', ev => {
                const isBetween = '...' === ev.target.value;
                $dateTo.parentElement.style.display = isBetween ? 'flex' : 'none';
                $dateLabel.innerText = isBetween ? `${dateText} from`.trim() : dateText;
            });
        });
    </script>
    <style>
        form[name='sms77api_wooc'] .datepicker {
            min-height: 30px;
        }

        form[name='sms77api_wooc'] input {
            max-width: 200px;
        }

        form[name='sms77api_wooc'] label {
            justify-content: space-between;
        }

        form[name='sms77api_wooc'] textarea {
            width: 100%;
        }
    </style>
<?php endif; ?>