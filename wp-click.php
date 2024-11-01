<?php
/**
 * WP Attention Click
 * @author    Lenin Zapata <leninzapatap@gmail.com>
 * @link      https://leninzapata.com
 * @copyright 2019 WP Attention Click
 * @package   WP Attention Click
 *
 * @wordpress-plugin
 * Plugin Name: WP Attention Click
 * Plugin URI: https://wordpress.org/plugins/wp-attention-click/
 * Author: Lenin Zapata
 * Author URI:
 * Version: 1.0
 * Description: It allows to draw the attention of visitors in various ways
 * Text Domain: wp-attention-click
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// ─── Start ☇ of the standard programming PSR-1 and PSR-4 ────────
namespace wpAttentionClick;

// ─── Exit if accessed directly ────────
if ( ! defined( 'ABSPATH' ) ) { exit; }

// ─── If this file is called directly, abort. ────────
if ( ! defined( 'WPINC' ) ) { die; }

// ─── Get data head plugin ────────
$filedata = get_file_data( __FILE__ , array( 'Version','Text Domain' ) );

// ─── Global development mode // ← Global mode developer ────────
if( ! defined( 'WP_MODE_DEV' ) ){ define( 'WP_MODE_DEV', ( WP_DEBUG || FALSE ) ? true : false );  }

// ─── Verify if it is in developer mode ────────
define( 'WPAC_MODE_DEV', WP_MODE_DEV );

/*
|--------------------------------------------------------------------------
| The current version of the plugin is defined according to the file header
| If you activate the mode:1 (developer mode) it will take random values ​​to
| avoid file caching.
|--------------------------------------------------------------------------
*/
define( 'WPAC_VERSION', ! WPAC_MODE_DEV ? $filedata[0] : rand(111,999) );

// ─── Version tables in DB ➘ ────────
define( 'WPAC_VERSION_DB', '0.8.3' );

// ─── Absolute server path ────────
define( 'WPAC_PATH', plugin_dir_path( __FILE__ ) );

// ─── Absolute public url ────────
define( 'WPAC_URL', plugin_dir_url( __FILE__ ) );

// ─── Text Domain for international language ────────
define( 'WPAC_TEXTDOMAIN',  isset( $filedata[1] ) && $filedata[1] ? $filedata[1] : '' );

// ─── Name (slug) or ID plugin ────────
define( 'WPAC_ID', 'wpac' );

// ─── Url plugin base name: example plugin/index.php ────────
define( 'WPAC_BASENAME', plugin_basename( __FILE__ ) );

/*
|--------------------------------------------------------------------------
| Start the plugin
|--------------------------------------------------------------------------
|
| This file is plugin initializer
|
*/
require_once 'includes/classes/class-init.php';