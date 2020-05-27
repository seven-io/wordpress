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
?>
<div class="wrap">
    <h1>sms77 - <?php _e('Messages', 'sms77api') ?></h1>

    <?php sms77api_Util::defaultMessageElements() ?>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="POST">
                        <?php
                        $this->messages_table->prepare_items();
                        $this->messages_table->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>