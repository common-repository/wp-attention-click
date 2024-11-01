<?php
/**
 * @since 		0.6 2019-04-01 10:01:01 Release
 * @package 	WPAC
 * @subpackage 	WPAC/Admin
 */
// ───────────────────────────
namespace wpAttentionClick\Admin;
use wpAttentionClick\Core;
// ───────────────────────────

if( ! class_exists( 'wpacAdminCustom' ) ){
    class wpacAdminCustom{

        private
        /**
         * The ID
         * @since 0.6 2019-04-01 10:10:48 Release
         * @access private
         * @var string
         */
        $name,
        /**
         * The version
         *
         * @since    0.6 2019-04-01 10:10:48 Release
         * @access   private
         * @var      string    $version    The current version of this plugin.
         */
        $version;

        /**
         * Initialize the class and set its properties.
         *
         * @since    0.6       2019-04-01 10:10:17     Release
         *
         * @param    string    $name 		The name of this plugin.
         * @param    string    $version 	The version of this plugin.
         * @return   void
         */
        public function __construct( $name, $version ) {
            $this->name    = $name;
            $this->version = $version;
        }

        /**
         * Add the support button in the setting options
         *
         * @since 0.6 2019-04-01 10:05:07 Release
         * @return string|html
         */
        public function add_button_support(){
            echo  '<a href="https://wordpress.org/support/plugin/wp-attention-click/" target="_black" id="wpac-button-support" class="button button-secondary" >' . esc_html__( 'Support', 'wpac' ) . '</a>';
        }

    }
}