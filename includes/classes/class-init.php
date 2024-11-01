<?php
/**
 * @since 		0.1     2019-03-21 05:01:53     Release
 * @package 	wpAttentionClick
 * @subpackage 	wpAttentionClick/Core
 */
// ───────────────────────────
namespace wpAttentionClick\Core;
use wpAttentionClick\Admin;
use wpAttentionClick\Publi;
use wpAttentionClick\Core;
// ───────────────────────────

if( ! class_exists( 'WPAC', false ) ){
    /*
    |--------------------------------------------------------------------------
    | Main Class WPAC
    |--------------------------------------------------------------------------
    |
    | This is the initial and main WPAC plugin class, here is
    | The bases for the operation of everything are executed.
    |
    */
    final class WPAC{

        /**
         * Existing instance
         *
         * Instance of the main class of the plugin
         * with this (deny duplicate).
         *
         * @access protected
         * @since 0.1   2019-03-21 05:01:53     Release
         * @var object|mixed
         */
        protected static $instance;

        public
        /**
         * The ID|Slug plugin
         * @since 0.1 2019-03-21 05:01:53 Release
         * @access public
         * @var string
         */
        $name = WPAC_ID,
        /**
         * Version
         * @since 0.1 2019-03-21 05:01:53 Release
         * @access public
         * @var string|int
         */
        $version = WPAC_VERSION,
        /**
         * Version database
         * @since 0.1 2019-03-21 05:01:53 Release
         * @access public
         * @var string|int
         */
        $version_db = WPAC_VERSION_DB,
        /**
         * URL public plugin
         * @since 0.1 2019-03-21 05:01:53 Release
         * @access public
         * @var string
         */
        $url = WPAC_URL,
        /**
         * URL server plugin
         * @since 0.1 2019-03-21 05:01:53 Release
         * @access public
         * @var string
         */
        $path = WPAC_PATH,
        /**
         * Maintains and registers all hooks for the plugin
         * @since 0.1 2019-03-21 05:01:53 Release
         * @access public
         * @var object
         */
        $loader,
        /**
         * Refers to the admin object of the plugin
         * @since 0.1 2019-03-21 05:01:53 Release
         * @access public
         * @var object
         */
        $admin,
        /**
         * Customize the administration (aesthetic part)
         * @since 0.6 2019-04-01 10:08:07 Release
         * @access public
         * @var object
         */
        $admin_custom,
        /**
         * Refers to the public object of the plugin
         * @since 0.1 2019-03-21 05:01:53 Release
         * @access public
         * @var object
         */
        $public;

        public static
        /**
         * Get all general options
         * of the application.
         * @var mixed|object
         */
        $options = null;

        /**
         * Get class instance (Singleton is Rock!)
         *
         * @since 	0.1     2019-03-21 05:01:53     Release
         * @return 	object
         */
        public static function instance() {
            if ( ! isset( static::$instance ) || !static::$instance ) {
                static::$instance = new static();
                static::$instance->init();
            }
            return static::$instance;
        }

        /**
         * Initializer of the class.
         *
         * @since   0.1     2019-03-21 05:01:53     Release
         * @return  void
         */
        public function init(){

            // ─── Init action ────────
            do_action( 'WPAC_init' );

            // ─── Load Core Files ────────
            $this->load_files_core();

            // ─── Load Options ────────
            self::get_options();

            // ─── Load Setup ────────
            self::setup();

            // Test
            ////PF_classUtils::ok();
            ////var_dump( self::$dir, self::$url );exit;
        }

        /**
         * Load Setup initial plugin
         *
         * @since   0.1     2019-03-21 05:01:53     Release
         * @return void
         */
        public function setup(){
            $this->load_dependencies();
            $this->set_locale();
            $this->define_admin_hooks();
            $this->define_public_hooks();
        }

        /**
         * Load the necessary files so that the core
         * start working correctly.
         *
         * @since	0.1     2019-03-21 05:01:53     Release
         * @return void
         */
        public function load_files_core(){
            // ─── Load functions ────────
            $files[] = ['includes/functions/sanitize',
                        'includes/functions/helper',
                        'includes/functions/actions',
                        'includes/functions/validate',];

            // ─── Load class ────────
            $files[] = ['includes/classes/class-abstract',
                        'includes/classes/class-i18n',
                        'includes/classes/class-loader',
                        'includes/classes/class-activator',

                        'admin/functions/helpers',
                        'admin/classes/class-admin',
                        'admin/classes/class-custom',

                        'public/classes/class-public',
                        ];
            // Fetch
            foreach( $files as $x => $file )
                foreach($file as $key => $value)
                    self::load_file( $this->path . '/' . $value .'.php' );
        }

        /**
         * Upload a file sent by a route
         *
         * @since 0.1	2019-03-21 05:01:53 Release
         *
         * @param string $file Name or path of the file
         * @return void
         */
        public static function load_file( $file ){
            require_once $file;
        }

        /**
         * Load the initial dependencies
         * of the plugin, these are environment variables
         *
         * @since	0.1	    2019-03-21 05:01:53     Release
         * @return	void
         */
        protected function load_dependencies(){
            // ─── Set hooks and filters ────────
            $this->loader 	= new \wpAttentionClick\Core\wpacLoader;

            // ─── Set admin functions ────────
            $this->admin 	= new \wpAttentionClick\Admin\wpacAdmin( $this->get_plugin_name(), $this->get_version() );

            // ─── Set admin aesthetics ────────
            $this->admin_custom = new \wpAttentionClick\Admin\wpacAdminCustom( $this->get_plugin_name(), $this->get_version() );

            // ─── Set and load class public ────────
            $this->public 	= new \wpAttentionClick\Publi\wpacPublic( $this->get_plugin_name(), $this->get_version() );
        }

        /**
         * Get ID plugin
         * The name|slug of the plugin
         *
         * @since   0.1     2019-03-21 05:01:53     Release
         * @return string
         */
        public function get_plugin_name() {
            return $this->name;
        }

        /**
         * Retrieve the version number of the plugin
         * The version number of the plugin.
         *
         * @since 0.1   2019-03-21 05:01:53     Release
         * @return string
         */
        public function get_version() {
            return  $this->version;
        }

        /**
         * Define the locale for this plugin for internationalization.
         *
         * Uses the WPAC_i18n class in order to set the domain and to register the hook
         * with WordPress.
         *
         * @since 0.1    2019-03-21 05:01:53    Release
         * @access private
         */
        private function set_locale() {
            $plugin_i18n = new WPAC_i18n();
            $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
        }

        /**
         * Register all of the hooks related to the admin area functionality
         * of the plugin.
         *
         * @since   0.1     2019-03-21  Release
         * @since   0.4     2019-03-31  The action wp_ajax_wpac-save-click was added
         * @since   0.9.4   2019-08-13  Ajax was added for backend
         * @access   private
         */
        private function define_admin_hooks() {
            // ─── Load style and script ────────
            $this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_script_styles' );

            // ─── Load functionalities (framework)    ↓ ────────
            $this->loader->add_action( 'pixel_options_wpac_buttons_after', $this->admin_custom, 'add_button_support' );

            // ───Load other actions ────────
            $this->loader->add_action( 'activated_plugin', $this->admin, 'admin_activated_plugin' );
            $this->loader->add_action( 'upgrader_process_complete', $this->admin, 'after_upgrade_plugin' );

            // ─── AJAX actions ────────
            //$this->loader->add_action( 'wp_ajax_wpac-save-click', $this->admin, 'wpac_save_click' );
            add_action( 'wp_ajax_wpac-save-click', 'wpac_save_click' );
            add_action( 'wp_ajax_nopriv_wpac-save-click', 'wpac_save_click' );

            //add_action( 'wp_ajax_wpac-save-view', 'wpac_save_view' );
            //add_action( 'wp_ajax_nopriv_wpac-save-view', 'wpac_save_view' );

            $this->loader->add_action( 'activated_plugin', $this->admin, 'save_and_display_error', 10, 1 );
        }

        /**
         * Register all of the hooks related to the public-facing functionality
         * of the plugin.
         *
         * @since 0.1   2019-03-21 05:01:53     Release
         * @access private
         */
        private function define_public_hooks() {
            $this->loader->add_action( 'wp_enqueue_scripts', $this->public, 'enqueue_script_styles' );
        }

        /**
         * Load options general
         *
         * @since	0.1	    2019-03-23  Release
         * @since   0.9.3   2019-08-13  With the new pixel 1.5.1 the name of the change option is the fixed post -setting
         * @return	object|mixed
         */
        public static function get_options(){
            return self::$options = \wpac_get_option( 'wpac-setting' );
        }

        /**
         * Run the loader to execute all of the hooks with WordPress.
         *
         * @since 0.1   2019-03-21 05:01:53     Release
         * @return void
         */
        public function run() {
            // Loads hook and filters
            $this->loader->run();
        }
    }
}
// Run!
global  $WPAC;
$WPAC 	= WPAC::instance();
$WPAC->run(); //!! 😜