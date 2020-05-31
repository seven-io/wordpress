<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */

require_once 'class-sms77api-util.php';

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
            __('Debug', 'sms77api'),
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
            <strong><?php _e('Delay', 'sms77api') ?></strong>
            <small><?php _e('sets a delay for sending', 'sms77api') ?></small>
        </span>

            <input name='<?php echo $isGlobal ? $option : $name ?>'
                   value='<?php echo get_option($option) ?>'/>
        </label>
        <?php
    }

    private static function unicode($isGlobal) {
        self::checkboxSetting(
            'unicode',
            __('Unicode', 'sms77api'),
            $isGlobal,
            __('forces unicode regardless of server determination', 'sms77api'));
    }

    private static function flash($isGlobal) {
        self::checkboxSetting(
            'flash',
            __('Flash', 'sms77api'),
            $isGlobal,
            __('makes the message appear directly in the display', 'sms77api'));
    }

    private static function performanceTracking($isGlobal) {
        self::checkboxSetting(
            'performance_tracking',
            __('Performance Tracking', 'sms77api'),
            $isGlobal);
    }

    private static function utf8($isGlobal) {
        self::checkboxSetting(
            'utf8',
            __('UTF-8', 'sms77api'),
            $isGlobal,
            __('forces utf8 regardless of server determination', 'sms77api'));
    }

    private static function udh($isGlobal) {
        $name = 'udh';
        $option = "sms77api_$name";
        ?>
        <label style='display: flex;'>
            <span>
            <strong><?php _e('UDH', 'sms77api') ?></strong>
            <small>sets a custom <a
                        href='https://en.wikipedia.org/wiki/User_Data_Header'>
                    <?php _e('User Data Header', 'sms77api') ?></a></a></small>
        </span>

            <input style='min-height: 30px' name='<?php echo $isGlobal ? $option : $name ?>'
                   value='<?php echo get_option($option) ?>'/>
        </label>
        <?php
    }

    public static function lookupPage($table, $type) {
        if (!get_option('sms77api_key')) {
            return;
        }
        ?>
        <h2><?php _e('Create a new Number Lookup', 'sms77api') ?></h2>

        <form method='POST' action='<?php echo admin_url('admin-post.php') ?>'
              style='display: flex; align-items: baseline'>
            <input type='hidden' name='action' value='sms77api_number_lookup_hook'>
            <input type='hidden' name='type' value='<?php echo $type ?>'>

            <input aria-label='<?php _e('Number to look up', 'sms77api') ?>'
                   placeholder='<?php _e('Number to look up', 'sms77api') ?>' name='number'/>

            <?php submit_button(__('Lookup', 'sms77api')) ?>
        </form>
        <?php
        sms77api_Util::grid($table);
    }

    private static function label($isGlobal) {
        $name = 'label';
        $option = "sms77api_$name";
        ?>
        <label style='display: flex;'>
            <span>
            <strong><?php _e('Label', 'sms77api') ?></strong>
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
                <strong><?php _e('TTL', 'sms77api') ?></strong>
                <small><?php _e('Time-To-Live (default: 86400000 ms - 24h)', 'sms77api') ?></small>
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
            <strong><?php $isGlobal
                    ? _e('Default Message', 'sms77api')
                    : _e('Message', 'sms77api') ?></strong>

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
                 <strong><?php $isGlobal
                         ? _e('Default Receiver(s)', 'sms77api')
                         : _e('Receiver(s)', 'sms77api') ?></strong>
                <small>separated by comma eg: +4912345, +12345</small>
            </span>

            <input name="<?php echo $isGlobal ? $option : $name ?>" value="<?php echo get_option($option); ?>"
                <?php echo $isGlobal ? '' : 'required' ?>/>
        </label>
        <?php
    }
}