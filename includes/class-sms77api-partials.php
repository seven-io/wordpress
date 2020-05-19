<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */
class sms77api_Partials {
    static function all($isGlobal) {
        self::debug($isGlobal);
        self::delay($isGlobal);
        self::unicode($isGlobal);
        self::flash($isGlobal);
        self::performanceTracking($isGlobal);
        self::utf8($isGlobal);
        self::udh($isGlobal);
        self::label($isGlobal);
        self::ttl($isGlobal);
    }

    private static function debug($isGlobal) {
        self::checkboxSetting(
            'debug',
            'Debug',
            $isGlobal,
            'validate parameters but do not send actual messages');
    }

    private static function checkboxSetting($name, $label, $isGlobal, $helper = null) {
        $option = "sms77api_$name";
        ?>
        <label style='display: flex;'>
            <span>
            <strong><?php echo $label ?></strong>
            <?php echo $helper ? "<small>$helper</small>" : '' ?>
        </span>

            <input name="<?php echo $isGlobal ? $option : $name ?>" style='margin: 0;'
                   type='checkbox' <?php echo (bool)get_option($option) ? 'checked' : '' ?>/>
        </label>
        <?php
    }

    private static function delay($isGlobal) {
        $name = 'delay';
        $option = "sms77api_$name";
        ?>
        <label style='display: flex;'>
            <span>
            <strong>Delay</strong>
            <small>sets a delay for sending</small>
        </span>

            <input name='<?php echo $isGlobal ? $option : $name ?>'
                   value='<?php echo get_option($option) ?>'/>
        </label>
        <?php
    }

    private static function unicode($isGlobal) {
        self::checkboxSetting(
            'unicode',
            'Unicode',
            $isGlobal,
            'forces unicode regardless of server determination');
    }

    private static function flash($isGlobal) {
        self::checkboxSetting(
            'flash',
            'Flash',
            $isGlobal,
            'makes the message appear directly in the display');
    }

    private static function performanceTracking($isGlobal) {
        self::checkboxSetting(
            'performance_tracking',
            'Performance Tracking',
            $isGlobal);
    }

    private static function utf8($isGlobal) {
        self::checkboxSetting(
            'utf8',
            'UTF-8',
            $isGlobal,
            'forces utf8 regardless of server determination');
    }

    private static function udh($isGlobal) {
        $name = 'udh';
        $option = "sms77api_$name";
        ?>
        <label style='display: flex;'>
            <span>
            <strong>UDH</strong>
            <small>sets a custom <a
                        href='https://en.wikipedia.org/wiki/User_Data_Header'>User Data Header</a></a></small>
        </span>

            <input style='min-height: 30px' name='<?php echo $isGlobal ? $option : $name ?>'
                   value='<?php echo get_option($option) ?>'/>
        </label>
        <?php
    }

    private static function label($isGlobal) {
        $name = 'label';
        $option = "sms77api_$name";
        ?>
        <label style='display: flex;'>
            <span>
            <strong>Label</strong>
            <small>allowed characters: a-z, A-Z, 0-9, .-_@</small>
        </span>

            <input style='min-height: 30px' name='<?php echo $isGlobal ? $option : $name ?>'
                   value='<?php echo get_option($option) ?>'/>
        </label>
        <?php
    }

    private static function ttl($isGlobal) {
        $name = 'ttl';
        $option = "sms77api_$name";
        $value = get_option($option);
        ?>
        <label style='display: flex;'>
            <span>
                <strong>TTL</strong>
                <small>Time-To-Live (default: 86400000 ms - 24h)</small>
            </span>

            <input type='number' name='<?php echo $isGlobal ? $option : $name ?>'
                <?php echo $value ? "value='$value'" : '' ?>/>
        </label>
        <?php
    }

    static function msg($isGlobal) {
        $name = 'msg';
        $option = "sms77api_$name";
        ?>
        <label style='display: flex;'>
            <strong><?php echo $isGlobal ? 'Default ' : '' ?>Message</strong>

            <textarea name='<?php echo $isGlobal ? $option : $name ?>'
            <?php echo $isGlobal ? '' : 'required' ?>><?php echo trim(get_option($option)) ?></textarea>
        </label>
        <?php
    }

    static function receivers($isGlobal) {
        $name = 'receivers';
        $option = "sms77api_$name";
        ?>
        <label style='display: flex;'>
            <span>
            <strong><?php echo $isGlobal ? 'Default ' : '' ?>Receiver(s)</strong>
            <small>separated by comma eg: +4912345, +12345</small>
        </span>

            <input name="<?php echo $isGlobal ? $option : $name ?>" value="<?php echo get_option($option); ?>"
                <?php echo $isGlobal ? '' : 'required' ?>/>
        </label>
        <?php
    }
}