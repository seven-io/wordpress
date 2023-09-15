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
    <h1>seven - <?php _e('Messages', 'sevenapi') ?></h1>

    <?php sevenapi_Partials::defaultMessageElements() ?>

    <?php if (get_option('sevenapi_key')): ?>
        <form name='sevenapi_compose' method='POST'
              action='<?php echo admin_url('admin-post.php'); ?>'>
            <input type='hidden' name='action' value='sevenapi_compose_hook'>

            <?php sevenapi_Partials::all(false) ?>

            <label class='nostyle' style='display: flex; flex-direction: column;'>
                <?php sevenapi_Partials::receivers(false) ?>
            </label>

            <label class='nostyle' style='display: flex; flex-direction: column;'>
                <?php sevenapi_Partials::text(false) ?>
            </label>

            <?php submit_button(__('Send SMS', 'sevenapi')) ?>
        </form>

        <style>
            form[name='sevenapi_compose'] input {
                max-width: 200px;
            }

            form[name='sevenapi_compose'] label:not(.nostyle) {
                justify-content: space-between;
            }

            form[name='sevenapi_compose'] textarea, form[name='sevenapi_compose'] input[name='receivers'] {
                width: 75%;
            }
        </style>
    <?php
    endif;
    sevenapi_Partials::grid($this->messages_table, false);
    ?>
</div>
