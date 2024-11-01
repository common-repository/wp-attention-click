<?php
/**
 * Pixel Framework - WordPress Options Framework
 * @author    Lenin Zapata <i@leninzapata.com>
 * @link      https://leninzapata.com
 * @copyright 2017-2019 Pixel Framework
 * @package   pixelframework
 *
 * @wordpress-plugin
 * Plugin Name: Pixel Framework
 * Plugin URI: https://leninzapata.com
 * Author: iLenStudio
 * Author URI: https://leninzapata.com
 * Version: 1.5.1
 * Description: WordPress Option Framework ( for Theme Options, Setting plugins, Metabox options, Widgets, Menus, more... )
 * Text Domain: pf
 * Domain Path: /languages
 */
if( ! class_exists('PF') ) {
    // FIXME: Here you must add more constants, as well as the plugins
    // I define the constants
    // ─── Absolute server path ────────
    define( 'PIXEL_PATH', plugin_dir_path( __FILE__ ) );

    // ─── Absolute public url ────────
    define( 'PIXEL_URL', plugin_dir_url( __FILE__ ) );

    // I invoke the nucleus
    require_once PIXEL_PATH .'classes/class-init.php';
} ?>