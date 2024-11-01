<?php
/*
|--------------------------------------------------------------------------
| Customization variables
|--------------------------------------------------------------------------
*/
/**
 * @since   0.6     2019-04-01  Release
 * @since   0.7     2019-04-05  Add
 * @since   1.0     2019-08-31  - It is added to the plugin to an options.php submenu
 *                              - WP Click name changed → WP Attention Click
 *                              - Now in the setting footer you can also save the data
 *                              - Credit text update.
 */
$array_countries = wpac_get_country_code();
$array_countries = array('all' => 'All') + $array_countries;


/*
|--------------------------------------------------------------------------
| Creation options
|--------------------------------------------------------------------------
*/
PF::addSetting( WPAC_ID . '-setting' , array(
    'menu_title'            => 'WP Attention Click',
    'menu_slug'             => 'wp-click',
    'menu_title_sub'        => 'WP Attention Click',
    'menu_type'             => 'submenu',
    'menu_parent'           => 'options-general.php',
    'setting_vertical_mode' => FALSE,
    'setting_title'         => 'WP Attention Click',
    'ajax_save'             => FALSE,
    'show_search'           => FALSE,
    'show_all_options'      => FALSE,
    'show_buttons_footer'   => TRUE,
    'show_footer'           => TRUE,
    'footer_credit'         => 'Made with 💙 by Lenin Zapata',
    'show_reset_section'    => FALSE,
    //'menu_icon'             => WPAC_URL . 'admin/assets/images/icon.png'
));
// Get options
require_once  WPAC_PATH . 'admin/options/options-bar.php';
require_once  WPAC_PATH . 'admin/options/options-popup.php';
PF::addPlugin(WPAC_ID);