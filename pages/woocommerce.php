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

<?php require_once __DIR__ . '/../partials/responsable.php' ?>

<?php if (get_option('sms77api_key') && sms77api_Util::hasWooCommerce()): ?>
    <h2>
        <?php esc_html_e('Send Bulk', 'sms77api'); ?>
    </h2>
    <form method='POST' action='<?php echo admin_url('admin-post.php'); ?>'>
        <input type='hidden' name='action' value='sms77api_wooc_bulk'>

        <?php sms77api_Partials::all(false) ?>

        <h3>Filters</h3>
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

        <label style='flex-direction: column;'>
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
    <style>
        .datepicker {
            min-height: 30px;
        }

        input {
            max-width: 200px;
        }

        form label {
            justify-content: space-between;
        }

        textarea {
            width: 100%;
        }
    </style>
<?php endif; ?>