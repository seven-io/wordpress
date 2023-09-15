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
require_once __DIR__ . '/../includes/class-sevenapi-util.php';
?>
    <h1>seven - WooCommerce</h1>

<?php sevenapi_Partials::defaultMessageElements() ?>

<?php if (get_option('sevenapi_key') && sevenapi_Util::hasWooCommerce()): ?>
    <h2>
        <?php _e('Send Bulk SMS', 'sevenapi') ?>
    </h2>
    <form name='sevenapi_wooc' method='POST' action='<?php echo admin_url('admin-post.php'); ?>'>
        <input type='hidden' name='action' value='sevenapi_wooc_bulk'>

        <?php sevenapi_Partials::all(false) ?>

        <h3>Filters</h3>
        <div style='display: flex; align-items: flex-end;'>
            <label style='display: flex; flex-direction: column;'>
                <strong><?php _e('Action', 'sevenapi') ?></strong>

                <select name='date_action'>
                    <option></option>
                    <?php foreach (sevenapi_Util::WOOC_BULK_FILTER_DATE_ACTIONS as $ACTION): ?>
                        <option value='<?php echo $ACTION ?>'><?php _e(ucfirst($ACTION), 'sevenapi') ?></option>
                    <?php endforeach ?>
                </select>
            </label>

            <label style='display: flex; flex-direction: column;'>
                <strong><?php _e('Modificator', 'sevenapi') ?></strong>

                <select name='date_modificator'>
                    <option></option>
                    <?php foreach (sevenapi_Util::WOOC_BULK_FILTER_DATE_MODIFICATORS as $MODIFICATOR): ?>
                        <option value='<?php echo $MODIFICATOR ?>'><?php echo $MODIFICATOR ?></option>
                    <?php endforeach ?>
                </select>
            </label>

            <label style='display: flex; flex-direction: column;'>
                <strong><?php _e('Date', 'sevenapi') ?></strong>

                <input class='datepicker' name='date'/>
            </label>

            <label style='flex-direction: column; display: none;'>
                <strong><?php _e('Date to', 'sevenapi') ?></strong>

                <input class='datepicker' name='date_to'/>
            </label>
        </div>

        <label style='flex-direction: column;'>
            <?php sevenapi_Partials::text(false) ?>
        </label>

        <?php submit_button(__('Send Bulk SMS', 'sevenapi')) ?>
    </form>
    <script>
        jQuery(function () {
            jQuery('.datepicker').datepicker({dateFormat: 'yy-dd-mm'});

            const $form = document.querySelector('form[name="sevenapi_wooc"]');
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
        form[name='sevenapi_wooc'] .datepicker {
            min-height: 30px;
        }

        form[name='sevenapi_wooc'] input {
            max-width: 200px;
        }

        form[name='sevenapi_wooc'] label {
            justify-content: space-between;
        }

        form[name='sevenapi_wooc'] textarea {
            width: 100%;
        }
    </style>
<?php endif; ?>
