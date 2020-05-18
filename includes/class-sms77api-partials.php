<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */
class sms77api_Partials {
    static function checkboxSetting($name, $label, $isGlobal, $helper = null) {
        $option = "sms77api_$name";
        ?>
        <span>
            <strong><?php echo $label ?></strong>
            <?php echo $helper ? "<small>$helper</small>" : '' ?>
        </span>

        <input name="<?php echo $isGlobal ? $option : $name ?>" style='margin: 0;'
               type='checkbox' <?php echo (bool)get_option($option) ? 'checked' : '' ?>/>
        <?php
    }

    static function textSetting($name, $label, $required, $isGlobal, $helper = null) {
        $option = "sms77api_$name";
        ?>
        <span>
                <strong><?php echo $label ?></strong>
                <?php echo $helper ? "<small>$helper</small>" : '' ?>
            </span>

        <input name="<?php echo $isGlobal ? $option : $name ?>" value="<?php echo get_option($option); ?>"
            <?php echo $required ? 'required' : '' ?>/>
        <?php
    }

    static function debug($isGlobal) {
        self::checkboxSetting(
            'debug',
            'Debug',
            $isGlobal,
            'validate parameters but do not send actual messages');
    }

    static function flash($isGlobal) {
        self::checkboxSetting(
            'flash',
            'Flash',
            $isGlobal,
            'makes the message appear directly in the display');
    }

    static function unicode($isGlobal) {
        self::checkboxSetting(
            'unicode',
            'Unicode',
            $isGlobal,
            'forces unicode regardless of server determination');
    }

    static function performanceTracking($isGlobal) {
        self::checkboxSetting(
            'performance_tracking',
            'Performance Tracking',
            $isGlobal);
    }

    static function receivers($required, $isGlobal) {
        self::textSetting(
            'receivers',
            'Default Receiver(s)',
            $required,
            $isGlobal,
            'separated by comma eg: +4912345, +12345');
    }

    static function ttl($isGlobal) {
        $name = 'ttl';
        $option = "sms77api_$name";
        ?>
        <span>
                <strong>TTL</strong>
                <small>Time-To-Live (default: 86400000 ms - 24h)</small>
            </span>

        <input type='number' name='<?php echo $isGlobal ? $option : $name ?>'
               value='<?php echo get_option($option) ?>'/>
        <?php
    }

    static function utf8($isGlobal) {
        self::checkboxSetting(
            'utf8',
            'UTF-8',
            $isGlobal,
            'forces utf8 regardless of server determination');
    }
}