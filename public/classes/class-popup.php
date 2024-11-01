<?php
/**
 * @since 		0.7 2019-04-05 23:32:52 Release
 * @package 	wpAttentionClick
 * @subpackage 	wpAttentionClick/Public
 */
// ───────────────────────────
namespace wpAttentionClick\Publi;
use wpAttentionClick\Core as wpac_core;
use wpAttentionClick\Publi as wpac_public;
// ───────────────────────────

if( ! class_exists( 'wpacPopup' ) ){
    /*
    |--------------------------------------------------------------------------
    | Methods to show the popup
    |--------------------------------------------------------------------------

    */
    class wpacPopup{
        private
        /**
         * The ID
         * @since 0.7 2019-04-05 23:39:47 Release
         * @access private
         * @var string
         */
        $name,
        /**
         * The version
         *
         * @since    0.7 2019-04-05 23:43:55 Release
         * @access   private
         * @var      string    $version    The current version of this plugin.
         */
        $version,
        /**
         * Options main setting
         *
         * @since 0.7 2019-04-05 23:41:30 Release
         * @var object|mixed
         */
        $options;

        /**
         * Initialize the class and set its properties.
         *
         * @since 0.7 2019-04-05 23:36:45 Release
         * @since 0.8 2019-04-07 16:32:20 Add hook for 'custom_cs' function
         *
         * @return   void
         */
        public function __construct( $options = [] ) {
            // ─── Load options ────────
            $this->load_options( $options );
            // ─── Print HTML ────────
            wpac_core\WPAC::instance()
                ->loader
                ->add_action( 'wp_footer', $this, 'set_html' );
            // ─── Print css custom ────────
            wpac_core\WPAC::instance()
                ->loader
                ->add_action( 'wp_head', $this, 'custom_css');
        }

        /**
         * Get option from main setting
         *
         * @since 0.7 2019-04-05 23:40:35 Release
         * @return object|mixed
         */
        public function load_options( $opt ){
            $this->options = $opt;
        }

        /**
         * HTML will show on the public page
         *
         * @since   0.7 2019-04-05  Release
         * @since   1.0 2019-08-31  This was not respecting the line breaks of the html output, the nl2br function was added
         * @return	string|html
         */
        public function set_html(){
            // ─── Validate if the plugin is activated ────────
            if( empty( $this->options->wpac_popup_activate ) ) return;
            // ────────────────────────────────────────────────
            //$zone_click_bar = $this->options->wpac_popup_fieldset_action['popup_click'];
            $link           = $this->options->wpac_popup_fieldset_action['popup_redirection_url'];
            $target         = ! empty( $this->options->wpac_popup_fieldset_action['popup_click_target'] ) ? "target='_blank'" : "";

            echo '<div class="wpac_popup">';

            echo '<div class="wpac_popup_content">';

                echo '<div class="wpac_popup_content_wrap">';

                /*if( $zone_click_bar ){
                    echo "<a class='wpac_center_a' href='$href' $target >";
                }*/

                echo $this->filter_text_out( nl2br($this->options->wpac_popup_fieldset_content['popup_editor']) );

                /*if( $zone_click_bar ){
                    echo "</a>";
                }*/

                echo '<span class="wpac_popup_close">✕</span>';
                echo '</div>';

            echo '</div>';

            // ─── Activate & Test ────────
            $activate  = ! empty( $this->options->wpac_popup_activate[0] ) ? 1 : 0;
            $mode_test = ! empty( $this->options->wpac_popup_activate[1] ) ? 1 : 0;

            // ─── Inputs hidden ────────
            echo '<input type="hidden" id="'.( WPAC_ID . '_nonce' ).'" value="'.wp_create_nonce( 'wpac-click-bar' ).'" />';
            echo '<input type="hidden" id="'.( WPAC_ID . '_popup_time_appear' ).'" value="'.(int)$this->options->wpac_popup_fieldset_action['popup_time_appear'].'" />';
            //echo '<input type="hidden" id="'.( WPAC_ID . '_popup_click' ).'" value="'.(int)$this->options->wpac_popup_fieldset_action['popup_click'].'" />';
            echo '<input type="hidden" id="'.( WPAC_ID . '_popup_appear_again' ).'" value="'.((int)$this->options->wpac_popup_fieldset_action['popup_time_appear_againt'] + 1).'" />';
            echo '<input type="hidden" id="'.( WPAC_ID . '_popup_activate' ).'" value="'.$activate.'" />';
            echo '<input type="hidden" id="'.( WPAC_ID . '_popup_mode_test' ).'" value="'.$mode_test.'" />';
            echo '<input type="hidden" id="'.( WPAC_ID . '_popup_allow_country' ).'" value="'.implode(",",(array)$this->options->wpac_popup_fieldset_advanced['popup_countries_include']).'" />';
            echo '</div>';
        }

        /**
         * FIlter the text and add:
         * * A button
         *
         * @since	0.7 2019-04-06 00:06:34 Release
         * @return	string
         */
        public function filter_text_out( $text ){
            // ─── Assign defaults ────────
            $link       = $this->options->wpac_popup_fieldset_action['popup_redirection_url'];
            $target     = ! empty( $this->options->wpac_popup_fieldset_action['popup_click_target'] ) ? "target='_blank'" : "";
            $for_button = '';

            $button     = '<a ' . $target . ' href="' . $link . '" class="wpac_popup_button">' . $this->options->wpac_fieldset_popup_button['popup_button_text'] . '</a>';
            // ─── Add new object string ────────
            $for_button .= str_replace('{{button}}', $button, $text);

            return $for_button;
        }

        /**
         * Add custom CSS according to the
         * configurations
         *
         * @since	0.8	2019-04-07 16:32:53 Release
         * @return	string|html
         */
        public function custom_css(){
            echo "<style id='".WPAC_ID."-popup-custom-css' type=\"text/css\">";

            // Padding
            $padding = $this->options->wpac_popup_fieldset_content['popup_padding_top_bottom'];
            \wpac_generate_css('.wpac_popup_content_wrap','padding', $padding['width']. 'px '.$padding['height'] . 'px') ;

            // Background & Colors
            $bg                    = $this->options->wpac_popup_fieldset_content['popup_background'];
            $background_color      = ! empty( $bg['background-color'] ) ? ';background-color:'.$bg['background-color'] : '';
            $background_image      = ! empty( $bg['background-image'] ) ? ';background-image:url('.$bg['background-image']['url'] . ')' : '';
            $background_position   = ! empty( $bg['background-position'] ) ? ';background-position:'.$bg['background-position'] : '';
            $background_repeat     = ! empty( $bg['background-repeat'] ) ? ';background-repeat:'.$bg['background-repeat'] : '';
            $background_attachment = ! empty( $bg['background-attachment'] ) ? ';background-attachment:'.$bg['background-attachment'] : '';
            $background_size       = ! empty( $bg['background-size'] ) ? ';background-size:'.$bg['background-size'] : '';
            $background            = $background_color . $background_image . $background_position . $background_repeat . $background_attachment . $background_size;
            \wpac_generate_css( '.wpac_popup_content' , $background ) ;
            \wpac_generate_css('.wpac_popup_content,.wpac_popup_content a,.wpac_popup_content p,.wpac_popup_content h1,.wpac_popup_content h2,.wpac_popup_content h3,.wpac_popup_content h4','color',$this->options->wpac_popup_fieldset_content['popup_color_text']);
            \wpac_generate_css('.wpac_popup_content,.wpac_popup_content p,.wpac_popup_content h1,.wpac_popup_content h2,.wpac_popup_content h3,.wpac_popup_content h4,.wpac_popup_content h5,.wpac_popup_content h6','margin', '0px');

            // Button
            \wpac_generate_css('.wpac_popup_content .wpac_popup_button','background', $this->options->wpac_fieldset_popup_button['popup_button_bg']);
            \wpac_generate_css('.wpac_popup_content .wpac_popup_button','color', $this->options->wpac_fieldset_popup_button['popup_button_color']);
            echo "</style>";
        }

    }
}
