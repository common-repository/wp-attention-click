<?php
/**
 * @since 		0.1     2019-03-21 05:06:07     Release
 * @package 	WPAC
 * @subpackage 	WPAC/Admin
 */
// ───────────────────────────
namespace wpAttentionClick\Admin;
use wpAttentionClick\Core;
// ───────────────────────────

if( ! class_exists( 'wpacAdmin' ) ){
    /*
    |--------------------------------------------------------------------------
    | The admin-specific functionality of the plugin.
    |--------------------------------------------------------------------------
    |
    | Defines the plugin name, version, hooks for how to
    | enqueue the admin-specific stylesheet, javaScript and admin processes
    |
    */
    class wpacAdmin{
        private
        /**
         * The ID
         * @since 0.1 2019-03-21 05:06:07 Release
         * @access private
         * @var string
         */
        $name,
        /**
         * The version
         *
         * @since    0.1 2019-03-21 05:06:07
         * @access   private
         * @var      string    $version    The current version of this plugin.
         */
        $version;

        /**
         * Indicates that if the login user can have permission
         * to load panel setting.
         *
         * @since    0.1    2019-03-21 05:06:07     Release
         * @access   public
         * @var bool
         */
        public static $is_user_panel_setting = false;

        /**
         * Initialize the class and set its properties.
         *
         * @since    0.1       2019-03-21 05:06:07     Release
         *
         * @param    string    $name 		The name of this plugin.
         * @param    string    $version 	The version of this plugin.
         * @return   void
         */
        public function __construct( $name, $version ) {
            $this->name    = $name;
            $this->version = $version;

            // Load setting & options
            $this->builds_options();
        }

        /**
         * Validate a user according to the role he has
         * default is now the 'administrator'
         *
         * @since 0.1   2019-03-21 05:06:07     Release
         * @access public
         * @static
         *
         * @return boolean
         */
        public static function isUserAllow(){
            if( ! function_exists('wp_get_current_user' ) ) {
                require_once ABSPATH . "wp-includes/pluggable.php" ;
            }
            $allowed_roles = apply_filters( WPAC_ID . '_role_user_allow',array('administrator') );
            $user          = wp_get_current_user();
            $is_user_admin = array_intersect($allowed_roles, $user->roles );
            $is_value      = ( is_admin() || is_customize_preview() || $is_user_admin );
            if( $is_value ){ self::$is_user_panel_setting = true; }

            return $is_value;
        }

        /**
         * Register the stylesheets and script for the admin area.
         * @since 0.1   2019-03-21 05:06:07     Release
         */
        public function enqueue_script_styles() {
            $min = ! WPAC_MODE_DEV ? '.min' : '';
            /**
             * An instance of this class should be passed to the run() function
             * defined in wpacLoader as all of the hooks are defined
             * in that particular class.
             *
             * The wpacLoader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
            wp_enqueue_style( $this->name, WPAC_URL . 'admin/assets/css/wpac' . $min . '.css', array(), $this->version, 'all' );
            wp_enqueue_script( $this->name, WPAC_URL . 'admin/assets/js/wpac' . $min . '.js', array( 'jquery' ), $this->version, true );
        }

        /**
         * Performs functions when activating the plugin
         *
         * @since 0.1   2019-03-21 05:06:07     Release
         * @hook activated_plugin
         * @access public
         * @see wpAttentionClick -> define_admin_hooks
         */
        public static function admin_activated_plugin(){
            \wpAttentionClick\Core\wpacActivator::activate();
        }

        /**
         * Perform processes after an update
         *
         * @since 0.1   2019-03-21 05:06:07     Release
         * @return void
         */
        public static function after_upgrade_plugin( $options ){
            // ─── The path to our plugin's main file ────────
            $our_plugin = WPAC_BASENAME;
            // ─── If an update has taken place and the updated type is plugins and the plugins element exists ────────
            if( is_array($options) && $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
                // ─── Iterate through the plugins being updated and check if ours is there ────────
                foreach( $options['plugins'] as $plugin ) {
                    if( $plugin == $our_plugin ) {
                        // ─── Verify changes in the table of the database if you have it ────────
                        self::check_update_table_db();
                    }
                }
            }
        }

        /**
         * Verify new structure changes in the plugin table
         *
         * @since 0.1   2019-03-21 05:06:07     Release
         * @access private
         */
        private function check_update_table_db(){
            wpacActivator::create_tables();
        }

        /**
         * It builds the complement options panel in the administration,
         * as well as user administration functions.
         *
         * @since 0.1   2019-03-21 05:06:07     Release
         * @access private
         *
         */
        private function builds_options(){
            if( true === self::$is_user_panel_setting || self::isUserAllow() ){
                $this->load_framework();
                require_once WPAC_PATH . 'admin/options/options.php';
            }
        }

        /**
         * Load pixel framework
         * Create an interface setting|menus|others...
         * This is only allowed for users who are inside the Admin or users that are log in.
         *
         * @since 0.1   2019-03-21 05:06:07    Release
         * @author Lenin Zapata (Pixel) <leninzapatap@gmail.com>
         */
        private function load_framework(){
            require_once WPAC_PATH . 'admin/framework/pixel/pixel-framework.php';
        }

        /**
         * Save and show installation errors
         *
         * If you have problems with characters that escape the installation,
         * with this function you can save the error to know which characters
         * are escaping.
         *
         * @since   0.1     2019-03-21  Release
         * @since   0.9.3   2019-08-13  It belonged to the INit class, now it belongs to this class
         * @static
         * @access public
         *
         * @param boolean Action to save the escaped string or show it
         * @return void
         */
        public static function save_and_display_error( $save_error = true ){
            if( true === $save_error ){
                update_option('plugin_error',  ob_get_contents());
            }elseif( false === $save_error ){
                echo get_option('plugin_error');
            }
        }
    }
}