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

    static function debug($isGlobal) {
        self::checkboxSetting(
            'debug',
            'Debug',
            $isGlobal,
            'validate parameters but do not send actual messages');
    }

    static function delay($isGlobal) {
        $name = 'delay';
        $option = "sms77api_$name";
        ?>
        <span>
            <strong>Delay</strong>
            <small>sets a delay for sending</small>
        </span>

        <input name='<?php echo $isGlobal ? $option : $name ?>' value='<?php echo get_option($option) ?>'/>
        <?php
    }

    static function flash($isGlobal) {
        self::checkboxSetting(
            'flash',
            'Flash',
            $isGlobal,
            'makes the message appear directly in the display');
    }

    static function label($isGlobal) {
        $name = 'label';
        $option = "sms77api_$name";
        ?>
        <span>
            <strong>Label</strong>
            <small>allowed characters: a-z, A-Z, 0-9, .-_@</small>
        </span>

        <input name='<?php echo $isGlobal ? $option : $name ?>' value='<?php echo get_option($option) ?>'/>
        <?php
    }

    static function msg($isGlobal) {
        $name = 'msg';
        $option = "sms77api_$name";
        ?>
        <strong><?php echo $isGlobal ? 'Default ' : '' ?>Message</strong>

        <textarea name='<?php echo $isGlobal ? $option : $name ?>'
            <?php echo $isGlobal ? '' : 'required' ?>><?php echo trim(get_option($option)) ?></textarea>
        <?php
    }

    static function performanceTracking($isGlobal) {
        self::checkboxSetting(
            'performance_tracking',
            'Performance Tracking',
            $isGlobal);
    }

    static function receivers($isGlobal) {
        $name = 'receivers';
        $option = "sms77api_$name";
        ?>
        <span>
            <strong><?php echo $isGlobal ? 'Default ' : '' ?>Receiver(s)</strong>
            <small>separated by comma eg: +4912345, +12345</small>
        </span>

        <input name="<?php echo $isGlobal ? $option : $name ?>" value="<?php echo get_option($option); ?>"
            <?php echo $isGlobal ? '' : 'required' ?>/>
        <?php
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

    static function udh($isGlobal) {
        $name = 'udh';
        $option = "sms77api_$name";
        ?>
        <span>
            <strong>UDH</strong>
            <small>sets a custom <a
                        href='https://en.wikipedia.org/wiki/User_Data_Header'>User Data Header</a></a></small>
        </span>

        <input name='<?php echo $isGlobal ? $option : $name ?>' value='<?php echo get_option($option) ?>'/>
        <?php
    }

    static function unicode($isGlobal) {
        self::checkboxSetting(
            'unicode',
            'Unicode',
            $isGlobal,
            'forces unicode regardless of server determination');
    }

    static function utf8($isGlobal) {
        self::checkboxSetting(
            'utf8',
            'UTF-8',
            $isGlobal,
            'forces utf8 regardless of server determination');
    }
}