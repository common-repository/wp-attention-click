<?php
/**
 * @since 0.1   2019-03-15 00:41:05     Release
 * @package wpAttentionClick
 * @subpackage wpAttentionClick/Core
 */
// ───────────────────────────
namespace wpAttentionClick\Core;
// ───────────────────────────

if( ! class_exists( 'wpac_i18n' ) ){
    /*
    |--------------------------------------------------------------------------
    | Define the internationalization functionality
    |--------------------------------------------------------------------------
    |
    | Loads and defines the internationalization files for this plugin
    | so that it is ready for translation.
    |
    */
    class wpac_i18n {
        /**
         * Load the plugin text domain for translation.
         *
         * @since 0.1   2019-03-21 05:01:14     Release
         * @return void
         */
        public function load_plugin_textdomain() {
            load_plugin_textdomain(
                WPAC_TEXTDOMAIN,
                false,
                WPAC_PATH . '/languages/'
            );
        }
    }
}?>