<?php

/**
 * @link       http://www.seven.io
 * @package    sevenapi
 * @subpackage sevenapi/includes
 * @author     seven communications GmbH & Co. KG <support@seven.io>
 */

class sevenapi_Partials {
    /**
     * @param bool $isGlobal
     * @return void
     */
    static function all($isGlobal) {
        self::from($isGlobal);
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

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function from($isGlobal) {
        $name = 'from';
        $option = "sevenapi_$name";
        ?>
        <label style='display: flex;'>
        <span>
            <strong><?php _e('From', 'sevenapi') ?></strong>
            <small>this gets displayed as the sender on the receiving end</small>
        </span>

            <input style='min-height: 30px' name='<?php echo $isGlobal ? $option : $name ?>'
                   value='<?php echo get_option($option) ?>'/>
        </label>
        <?php
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function debug($isGlobal) {
        self::checkboxSetting(
            'debug',
            __('Debug', 'sevenapi'),
            $isGlobal,
            'validate parameters but do not send actual messages');
    }

    /**
     * @param string $name
     * @param string $label
     * @param bool $isGlobal
     * @param string|null $helper
     * @return void
     */
    static function checkboxSetting($name, $label, $isGlobal, $helper = null) {
        $option = "sevenapi_$name";
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

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function delay($isGlobal) {
        $name = 'delay';
        $option = "sevenapi_$name";
        ?>
        <label style='display: flex;'>
            <span>
            <strong><?php _e('Delay', 'sevenapi') ?></strong>
            <small><?php _e('sets a delay for sending', 'sevenapi') ?></small>
        </span>

            <input name='<?php echo $isGlobal ? $option : $name ?>'
                   value='<?php echo get_option($option) ?>'/>
        </label>
        <?php
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function unicode($isGlobal) {
        self::checkboxSetting(
            'unicode',
            __('Unicode', 'sevenapi'),
            $isGlobal,
            __('forces unicode regardless of server determination', 'sevenapi'));
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function flash($isGlobal) {
        self::checkboxSetting(
            'flash',
            __('Flash', 'sevenapi'),
            $isGlobal,
            __('makes the message appear directly in the display', 'sevenapi'));
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function performanceTracking($isGlobal) {
        self::checkboxSetting(
            'performance_tracking',
            __('Performance Tracking', 'sevenapi'),
            $isGlobal);
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function utf8($isGlobal) {
        self::checkboxSetting(
            'utf8',
            __('UTF-8', 'sevenapi'),
            $isGlobal,
            __('forces utf8 regardless of server determination', 'sevenapi'));
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function udh($isGlobal) {
        $name = 'udh';
        $option = "sevenapi_$name";
        ?>
        <label style='display: flex;'>
            <span>
            <strong><?php _e('UDH', 'sevenapi') ?></strong>
            <small>sets a custom <a
                        href='https://en.wikipedia.org/wiki/User_Data_Header'>
                    <?php _e('User Data Header', 'sevenapi') ?></a></small>
        </span>

            <input style='min-height: 30px' name='<?php echo $isGlobal ? $option : $name ?>'
                   value='<?php echo get_option($option) ?>'/>
        </label>
        <?php
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function label($isGlobal) {
        $name = 'label';
        $option = "sevenapi_$name";
        ?>
        <label style='display: flex;'>
            <span>
            <strong><?php _e('Label', 'sevenapi') ?></strong>
            <small>allowed characters: a-z, A-Z, 0-9, .-_@</small>
        </span>

            <input style='min-height: 30px' name='<?php echo $isGlobal ? $option : $name ?>'
                   value='<?php echo get_option($option) ?>'/>
        </label>
        <?php
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function ttl($isGlobal) {
        $name = 'ttl';
        $option = "sevenapi_$name";
        $value = get_option($option);
        ?>
        <label style='display: flex;'>
            <span>
                <strong><?php _e('TTL', 'sevenapi') ?></strong>
                <small><?php _e('Time-To-Live (default: 86400000 ms - 24h)', 'sevenapi') ?></small>
            </span>

            <input type='number' name='<?php echo $isGlobal ? $option : $name ?>'
                <?php echo $value ? "value='$value'" : '' ?>/>
        </label>
        <?php
    }

    /**
     * @param Base_Table $table
     * @param bool $wrap
     * @return void
     */
    static function grid($table, $wrap = true) {
        ?>
        <?php
        echo $wrap ? "<div class='wrap'><h1>seven - {$table->_args['_tpl']['title']}</h1>" : '';
        self::defaultMessageElements();
        ?>
        <div id='poststuff'>
            <div id='post-body' class='metabox-holder columns-2'>
                <div id='post-body-content'>
                    <div class='meta-box-sortables ui-sortable'>
                        <form method='POST'>
                            <?php
                            $table->prepare_items();
                            $table->display();
                            ?>
                        </form>
                    </div>
                </div>
            </div>

            <br class='clear'/>
        </div>
        <?php echo $wrap ? "</div>" : '';
    }

    /** @return void */
    static function defaultMessageElements() {
        if (count(isset($_GET['errors']) ? $_GET['errors'] : [])) {
            $errors = implode(PHP_EOL, $_GET['errors']);
            echo "<b>Errors:</b><pre>$errors</pre>";
        }

        if (isset($_GET['response'])) {
            $response = json_encode($_GET['response'], JSON_PRETTY_PRINT);
            echo "<b>Response:</b><pre>$response</pre>";
        }

        echo self::missingApiKeyLink();
    }

    /** @return string */
    static function missingApiKeyLink() {
        if (get_option('sevenapi_key')) {
            return '';
        }

        $href = admin_url('options-general.php?page=sevenapi');
        $p = wp_kses(
            __('An API Key is required for using this plugin. Please head to the <a href="%s">Plugin Settings</a> to set it.', 'sevenapi'),
            ['a' => ['href' => []]]);
        $p = sprintf($p, esc_url($href));

        return "<p>$p</p>";
    }

    /**
     * @param bool $isGlobal
     * @param bool $counter
     * @return void
     */
    static function text($isGlobal, $counter = true) {
        $name = 'msg';
        $option = "sevenapi_$name";
        ?>
        <label style='display: flex;'>
            <strong><?php $isGlobal
                    ? _e('Default Message', 'sevenapi')
                    : _e('Message', 'sevenapi') ?></strong>

            <textarea style='padding-top: 10px;' data-seven-sms
                      name='<?php echo $isGlobal ? $option : $name ?>'
            <?php echo $isGlobal ? '' : 'required' ?>><?php echo trim(get_option($option)) ?></textarea>
        </label>

        <?php if ($counter): ?>
            <script src='https://unpkg.com/@seven.io/counter/dist/index.js'></script>
        <?php endif;
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    static function receivers($isGlobal) {
        $name = 'receivers';
        $option = "sevenapi_$name";
        ?>
        <label style='display: flex;'>
            <span>
                 <strong><?php $isGlobal
                         ? _e('Default Receiver(s)', 'sevenapi')
                         : _e('Receiver(s)', 'sevenapi') ?></strong>
                <small>separated by comma eg: +4912345, +12345</small>
            </span>

            <input name="<?php echo $isGlobal ? $option : $name ?>"
                   value="<?php echo get_option($option); ?>"
                <?php echo $isGlobal ? '' : 'required' ?>/>
        </label>
        <?php
    }
}
