<?php
/**
 * @since 		0.1 2019-03-21 05:16:07     Release
 * @package 	wpAttentionClick
 * @subpackage 	wpAttentionClick/Public
 */
// ───────────────────────────
namespace wpAttentionClick\Publi;
use wpAttentionClick\Core as wpac_core;
use wpAttentionClick\Publi as wpac_public;
// ───────────────────────────

if( ! class_exists( 'wpacPublic' ) ){
    /*
    |--------------------------------------------------------------------------
    | The public-facing functionality of the plugin.
    |--------------------------------------------------------------------------
    |
    | Defines the name, version and hooks for how to
    | enqueue the public-facing stylesheet and javaScript.
    |
    | This class also dedicates only to bar (top or bottom)
    |
    */
    class wpacPublic{
        private
        /**
         * The ID
         * @since 0.1 2019-03-21 05:16:07 Release
         * @access private
         * @var string
         */
        $name,
        /**
         * The version
         *
         * @since    0.1 2019-03-21 05:16:07
         * @access   private
         * @var      string    $version    The current version of this plugin.
         */
        $version,
        /**
         * Get options...
         * @var object|mixed
         */
        $options;

        /**
         * Initialize the class and set its properties.
         *
         * @since    0.1 2019-03-21 05:16:07     Release
         *
         * @param    string    $name 		The name of this plugin.
         * @param    string    $version 	The version of this plugin.
         * @return   void
         */
        public function __construct( $name, $version ) {
            $this->name    = $name;
            $this->version = $version;
            $this->options = wpac_core\WPAC::get_options();
            // ─── Core Files ────────
            $this->load_files_core();
            // ─── Enqueue ────────
            wpac_core\WPAC::instance()
                ->loader
                ->add_action( 'wp_enqueue_scripts', $this, 'enqueue_script_styles' );
            // ─── Print HTML ────────
            wpac_core\WPAC::instance()
                ->loader
                ->add_action( 'wp_footer', $this, 'set_html' );
            // ─── Print css custom────────
            wpac_core\WPAC::instance()
                ->loader
                ->add_action( 'wp_head', $this, 'custom_css');
            // ─── Load popup class ────────
            // FIXED: Here you can send the options in the constructor [wpac_public\wpacPopup($this->options)] of this in the popup class, not as static.
            new wpac_public\wpacPopup( $this->options );
        }

        /**
         * Register the JavaScript and Styles for the public-facing side of the site
         *
         * @since 0.1 2019-03-21 05:16:07     Release
         */
        public function enqueue_script_styles() {

            global $post;

            $min = ! WPAC_MODE_DEV ? '.min' : '';
            $scripts = [
                ['handle' => $this->name . '-pixel-geo', 'src'=> WPAC_URL . 'public/assets/js/pixel-geo' . $min . '.js', 'dep'=>array( 'jquery' ), 'ver'=> $this->version , 'in_foot'=>true],
                ['handle' => $this->name . '-js', 'src'=> WPAC_URL . 'public/assets/js/wpac' . $min . '.js', 'dep'=>array( 'jquery' ), 'ver'=> $this->version , 'in_foot'=>true],
            ];

            for ($i=0; $i < sizeof($scripts); $i++) {
                wp_enqueue_script( $scripts[$i]['handle'], $scripts[$i]['src'], $scripts[$i]['dep'], $scripts[$i]['ver'], $scripts[$i]['in_foot'] );
            }
            // Var JS
            $args_localizer = array(
                'ajaxurl'   => admin_url( 'admin-ajax.php' ),
                'post_id'   => ! empty( $post ) ? $post->ID : 0,
                'url'       => urlencode( wpac_current_location() ),
                'where_is'  => implode( "|", (array) wpac_get_query_flags() ),
                'nonce'     => wp_create_nonce( 'wpac-click' ),
                'is_logged' => is_user_logged_in() ? 1 : 0,
            );
            wp_localize_script( $this->name . '-js' , 'wpac_vars', $args_localizer );

            // Style
            wp_enqueue_style( $this->name . '-css', WPAC_URL . 'public/assets/css/wpac' . $min . '.css', array(), $this->version, 'all' );
        }

        /**
         * Load the necessary files so that the public
         * start working correctly.
         *
         * @since 0.1 2019-03-22 02:44:06 Release
         * @since 0.7 2019-04-07 10:22:30 Add class 'class-popup'
         *
         * @return void
         */
        public function load_files_core(){
            // ─── Load functions ────────
            $files[] = ['public/functions/helper',];
            // ─── Load public class ────────
            $files[] = ['public/classes/class-popup',];

            // Fetch
            foreach($files as $x => $file)
                foreach($file as $key => $value)
                    $this->load_file( WPAC_PATH . '/' . $value .'.php' );
        }

        /**
         * Upload a file sent by a route
         *
         * @since 0.1	2019-03-22 02:45:13 Release
         *
         * @param string $file Name or path of the file
         * @return void
         */
        public function load_file( $file ){
            require_once $file;
        }

        /**
         * HTML will show on the public page
         *
         * @since 0.1 2019-03-22 02:26:05 Release
         * @since 0.6 2019-04-02 00:28:24 Add the hidden country field
         * @since 0.7 2019-04-07 10:21:43 Change of link wrap position
         *
         * @return	string|html
         */
        public function set_html(){

            // ─── Validate if the plugin is activated ────────
            if( empty( $this->options->wpac_bar_activate[0] ) ) return;
            // ────────────────────────────────────────────────

            $zone_click_bar = $this->options->wpac_fieldset_action['click_bar'];
            $href           = $this->wpac_get_link();
            $target         = ! empty( $this->options->wpac_fieldset_action['click_target'] ) ? "target='_blank'" : "";
            $direction      = ! empty( $this->options->direction && $this->options->direction == 'bottom' ) ? 'wpac_bottom' : '';

            echo '<div class="wpac ' . $direction . '" base="'.WPAC_BASENAME.'">';

                echo '<div class="wpac_center">';

                if( $zone_click_bar ){
                    echo "<a class='wpac_center_a' href='$href' $target >";
                }

                    echo $this->filter_text_out( $this->options->wpac_fieldset_text['editor'] );

                if( $zone_click_bar ){
                    echo "</a>";
                }

                echo '</div>';

                // ─── Activate & Test ────────
                $activate  = ! empty( $this->options->wpac_bar_activate[0] ) ? 1 : 0;
                $mode_test = ! empty( $this->options->wpac_bar_activate[1] ) ? 1 : 0;

                echo '<span class="wpac_close">✕</span>';
                echo '<input type="hidden" id="'.( WPAC_ID . '_nonce' ).'" value="'.wp_create_nonce( 'wpac-click-bar' ).'" />';
                echo '<input type="hidden" id="'.( WPAC_ID . '_time_appear' ).'" value="'.(int)$this->options->wpac_fieldset_action['time_appear'].'" />';
                echo '<input type="hidden" id="'.( WPAC_ID . '_click_bar' ).'" value="'.(int)$this->options->wpac_fieldset_action['click_bar'].'" />';
                echo '<input type="hidden" id="'.( WPAC_ID . '_appear_again' ).'" value="'.((int)$this->options->wpac_fieldset_action['time_appear_againt'] + 1).'" />';
                echo '<input type="hidden" id="'.( WPAC_ID . '_activate' ).'" value="'.$activate.'" />';
                echo '<input type="hidden" id="'.( WPAC_ID . '_mode_test' ).'" value="'.$mode_test.'" />';
                echo '<input type="hidden" id="'.( WPAC_ID . '_allow_country' ).'" value="'.implode(",",(array)$this->options->wpac_fieldset_advanced['countries_include']).'" />';
            echo '</div>';
        }

        /**
         * Add custom CSS according to the
         * configurations
         *
         * @since	0.1	2019-03-23 11:08:43 Release
         * @return	string|html
         */
        public function custom_css(){
            echo "<style id='".WPAC_ID."-custom-css' type=\"text/css\">";
            \wpac_generate_css('.wpac','background',$this->options->wpac_fieldset_text['color-bg']);
            \wpac_generate_css('.wpac,.wpac a,.wpac p,.wpac h1,.wpac h2,.wpac h3,.wpac h4','color',$this->options->wpac_fieldset_text['color-text']);
            \wpac_generate_css('.wpac,.wpac p,.wpac h1,.wpac h2,.wpac h3,.wpac h4,.wpac h5,.wpac h6','margin', '0px');
            \wpac_generate_css('.wpac .wpac_center','padding', $this->options->wpac_fieldset_text['box-padding-top-bottom']['width'] . 'px');
            \wpac_generate_css('.wpac  .wpac_center','box-shadow', 'rgba(0, 0, 0, 0) 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px inset');
            \wpac_generate_css('.wpac  .wpac_center','text-shadow', 'rgba(0, 0, 0, 0) 0px 0px 0px');

            \wpac_generate_css('.wpac  .wpac_button','background', $this->options->wpac_fieldset_button['button-bg']);
            \wpac_generate_css('.wpac  .wpac_button','color', $this->options->wpac_fieldset_button['button-color']);
            echo "</style>";
        }

        /**
         * FIlter the text and add:
         * * A button
         * * A counter to the back
         *
         * @since	0.1 2019-03-23 15:35:47 Release
         * @return	string
         */
        public function filter_text_out( $text ){
            // ─── Assign defaults ────────
            $link       = $this->wpac_get_link();
            $target     = ! empty( $this->options->wpac_fieldset_action['click_target'] ) ? "target='_blank'" : "";
            $for_button = '';

            $button     = '<a ' . $target . ' href="' . $link . '" class="wpac_button">' . $this->options->wpac_fieldset_button['button-text'] . '</a>';
            // ─── Add new object string ────────
            $for_button .= str_replace('{{button}}', $button, $text);

            return $for_button;
        }

        /**
         * Format and get link
         *
         * @since	0.1	2019-03-25 07:30:45 Release
         * @return	string|html
         */
        public function wpac_get_link(){

            $href = '';

            if( 'r-url' ==  $this->options->wpac_fieldset_action['type_action'] ){
                $href       = $this->options->wpac_fieldset_action['redirection_url'];
            }else{
                global $post;

                $phone =    ! empty( $this->options->wpac_fieldset_action['number_whatsapp'] ) ?
                            'phone=' . \pf_onlynumber($this->options->wpac_fieldset_action['number_whatsapp']) : '';

                $text =     '&text=' . (( 'send_title_link' == $this->options->wpac_fieldset_action['number_whatsapp_aditional'] ) ?
                            $post->post_title . ' - ' . get_permalink( $post->post_ID ) :
                            $this->options->wpac_fieldset_action['number_whatsapp_custom_text'] );

                $href       = 'https://api.whatsapp.com/send?'.$phone.$text;
            }

            return $href;
        }
    }
}