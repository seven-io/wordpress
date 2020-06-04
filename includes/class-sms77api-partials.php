<?php

/**
 * @link       http://sms77.io
 * @package    sms77api
 * @subpackage sms77api/includes
 * @author     sms77 e.K. <support@sms77.io>
 */

class sms77api_Partials {
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
        $option = "sms77api_$name";
        ?>
        <label style='display: flex;'>
        <span>
            <strong><?php _e('From', 'sms77api') ?></strong>
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
            __('Debug', 'sms77api'),
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

    /**
     * @param bool $isGlobal
     * @return void
     */
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

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function unicode($isGlobal) {
        self::checkboxSetting(
            'unicode',
            __('Unicode', 'sms77api'),
            $isGlobal,
            __('forces unicode regardless of server determination', 'sms77api'));
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function flash($isGlobal) {
        self::checkboxSetting(
            'flash',
            __('Flash', 'sms77api'),
            $isGlobal,
            __('makes the message appear directly in the display', 'sms77api'));
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function performanceTracking($isGlobal) {
        self::checkboxSetting(
            'performance_tracking',
            __('Performance Tracking', 'sms77api'),
            $isGlobal);
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function utf8($isGlobal) {
        self::checkboxSetting(
            'utf8',
            __('UTF-8', 'sms77api'),
            $isGlobal,
            __('forces utf8 regardless of server determination', 'sms77api'));
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
    private static function udh($isGlobal) {
        $name = 'udh';
        $option = "sms77api_$name";
        ?>
        <label style='display: flex;'>
            <span>
            <strong><?php _e('UDH', 'sms77api') ?></strong>
            <small>sets a custom <a
                        href='https://en.wikipedia.org/wiki/User_Data_Header'>
                    <?php _e('User Data Header', 'sms77api') ?></a></small>
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

    /**
     * @param bool $isGlobal
     * @return void
     */
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

    /**
     * @param Base_Table $table
     * @param bool $wrap
     * @return void
     */
    static function grid($table, $wrap = true) {
        ?>
        <?php
        echo $wrap ? "<div class='wrap'><h1>sms77 - {$table->_args['_tpl']['title']}</h1>" : '';
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
        if (get_option('sms77api_key')) {
            return '';
        }

        $href = admin_url('options-general.php?page=sms77api');
        $p = wp_kses(
            __('An API Key is required for using this plugin. Please head to the <a href="%s">Plugin Settings</a> to set it.', 'sms77api'),
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
        $option = "sms77api_$name";
        ?>
        <label style='display: flex;'>
            <strong><?php $isGlobal
                    ? _e('Default Message', 'sms77api')
                    : _e('Message', 'sms77api') ?></strong>

            <textarea style='padding-top: 10px;' data-sms77-sms
                      name='<?php echo $isGlobal ? $option : $name ?>'
            <?php echo $isGlobal ? '' : 'required' ?>><?php echo trim(get_option($option)) ?></textarea>
        </label>

        <?php if ($counter): ?>
            <script>
                const ONE_BYTE_CHARS = ['@', 'Δ', '0', '¡', 'P', '¿', 'p', '£', '_', '!', '1', 'A', 'Q', 'a', 'q', '$', 'Φ', '"', '2', 'B', 'R', 'b', 'r', '¥', 'Γ', '#', '3', 'C', 'S', 'c', 's', 'è', 'Λ', '¤', '4', 'D', 'T', 'd', 't', 'é', 'Ω', '%', '5', 'E', 'U', 'e', 'u', 'ù', 'Π', '&', '6', 'F', 'V', 'f', 'v', 'ì', 'Ψ', '\'', '7', 'G', 'W', 'g', 'w', 'ò', 'Σ', '(', '8', 'H', 'X', 'h', 'x', 'Ç', 'Θ', ')', '9', 'I', 'Y', 'i', 'y', 'Ξ', '*', ':', 'J', 'Z', 'j', 'z', 'Ø', '+', ';', 'K', 'Ä', 'k', 'ä', 'ø', 'Æ', ',', '<', 'L', 'Ö', 'l', 'ö', 'æ', '-', '=', 'M', 'Ñ', 'm', 'ñ', 'Å', 'ß', '.', '>', 'N', 'Ü', 'n', 'ü', 'å', 'É', '/', '?', 'O', '§', 'o', 'à'];
                const TWO_BYTE_CHARS = ['^', '|', '€', '{', '}', '[', ']', '~', '\\'];

                const handleSmsText = () => {
                    const handleInput = ({target: textarea}) => {
                        const encoding = (() => {
                            let encoding = 'GSM7';

                            for (const char of textarea.value.split('')) {
                                if (![...ONE_BYTE_CHARS, ...TWO_BYTE_CHARS].includes(char)) {
                                    encoding = 'UCS2';
                                    break;
                                }
                            }

                            return encoding;
                        })();

                        let charCount = textarea.value.length;

                        if ('UCS2' === encoding) {
                            for (const twoByteChar of TWO_BYTE_CHARS) {
                                for (const char of textarea.value.split('')) {
                                    if (char === twoByteChar) {
                                        charCount++;
                                    }
                                }
                            }
                        }

                        const CHAR_LIMITS = {
                            GSM7: 160,
                            UCS2: 70,
                        };

                        if (CHAR_LIMITS[encoding] < charCount) {
                            CHAR_LIMITS.GSM7 = 153;
                            CHAR_LIMITS.UCS2 = 67;
                        }

                        let msgCount = charCount / CHAR_LIMITS[encoding];
                        if (1 >= msgCount) {
                            msgCount = 1;
                        } else {
                            msgCount = Math.floor(Number.parseFloat(String(msgCount)));
                            msgCount++;
                        }

                        textarea.nextElementSibling.textContent
                            = `${charCount}/${msgCount} [${encoding}]`;
                    };

                    for (const textarea of document.querySelectorAll('textarea[data-sms77-sms]')) {
                        textarea.insertAdjacentHTML('afterend',
                            `<span style='position: absolute; right: 22px;'></span>`);

                        textarea.addEventListener('input', handleInput);
                    }
                };

                document.addEventListener('DOMContentLoaded', handleSmsText);
            </script>
        <?php endif;
    }

    /**
     * @param bool $isGlobal
     * @return void
     */
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

            <input name="<?php echo $isGlobal ? $option : $name ?>"
                   value="<?php echo get_option($option); ?>"
                <?php echo $isGlobal ? '' : 'required' ?>/>
        </label>
        <?php
    }
}