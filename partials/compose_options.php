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

require_once __DIR__ . '/../includes/class-sms77api-partials.php';
?>
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