<?php
/**
 * @since 0.1   2019-03-21 05:00:02     Release
 * @package    wpAttentionClick
 * @subpackage wpAttentionClick/Core
 */
// ───────────────────────────
namespace wpAttentionClick\Core;
// ───────────────────────────

if( ! class_exists( 'wpacActivator' ) ){
    /*
    |--------------------------------------------------------------------------
    | Fired during plugin activation
    |--------------------------------------------------------------------------
    |
    | This class defines all code necessary to run during the plugin's activation.
    |
    */
    class wpacActivator{

        /**
         * Perform necessary processes when activating the plugin
         * @return void
         */
        public static function activate() {
            // Registers the plugin tables
            self::create_tables();
        }

        /**
         * Creation of plugin tables
         *
         * @since   0.1   2019-03-15 01:27:58 Release
         * @since   0.4   2019-03-30 08:28:11 Added the '$prefix_clicks' tables
         * @since   0.7   2019-04-07 09:20:32 The field was added 'type_click'
         * @since   0.8   2019-04-07 23:25:13 Add table 'wpac_views'
         * @since   0.8.2 2019-04-08 01:40:01 Add after table via $wpdb
         * @since   0.8.3 2019-04-08 13:45:22 Fixed add column (type_click) in table wpac_clicks
         * @since   0.9.3   2019-08-13 Removal of the 'wpac_views' table
         *
         * @static
         * @access public
         *
         * @return void
         */
        public static function create_tables(){

            global $wpdb;

            $prefix             = $wpdb->prefix;
            $current_db_version = WPAC_VERSION_DB;
            $table_name         = $prefix . 'wpac_clicks';
            //$table_name2        = $wpdb->prefix . 'wpac_views';
            //$table_name2        = $wpdb->prefix . 'wpac_views';

            $installed_ver      = wpac_get_option( WPAC_ID .'_db_version' );
            $add_table_db       = false;

            if ( $installed_ver != '$current_db_version' ) {

                /**
                * We'll set the default character set and collation for this table.
                * If we don't do this, some characters could end up being converted
                * to just ?'s when saved in our table.
                */

                $charset_collate = '';

                if ( ! empty( $wpdb->charset ) ) {
                $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
                }

                if ( ! empty( $wpdb->collate ) ) {
                $charset_collate .= " COLLATE {$wpdb->collate}";
                }

                // ─── We validate if the table exists ────────
                $row = $wpdb->query(  "SHOW TABLES LIKE '$table_name'"  );
                if( empty( $row ) ){
                    $sql = "CREATE TABLE $table_name (
                                ID int(11) NOT NULL AUTO_INCREMENT,
                                post_id int(15) NULL,
                                type_click CHAR(20) NOT NULL DEFAULT 'b', /* ← even if you add it here, it goes to the end of the table */
                                date_click datetime NOT NULL,
                                timestamp_click int(15) NOT NULL,
                                ip varchar(100) NULL,
                                la varchar(100) NULL,
                                lo varchar(100) NULL,
                                country varchar(100) NULL,
                                country_code varchar(100) NULL,
                                region varchar(100) NULL,
                                city varchar(100) NULL,
                                device varchar(10) NOT NULL,
                                url varchar(250) NOT NULL,
                                where_is varchar(30) NULL,
                                browser_details varchar(500) NULL
                            UNIQUE KEY ID (ID)
                    ) $charset_collate;";

                    /* $sql .= "CREATE TABLE $table_name2 (
                        ID int(11) NOT NULL AUTO_INCREMENT,
                        post_id int(15) NULL,
                        type_view CHAR(20) NOT NULL DEFAULT 'b',
                        date_click datetime NOT NULL,
                        timestamp_click int(15) NOT NULL,
                        ip varchar(100) NULL,
                        la varchar(100) NULL,
                        lo varchar(100) NULL,
                        country varchar(100) NULL,
                        country_code varchar(100) NULL,
                        region varchar(100) NULL,
                        city varchar(100) NULL,
                        device varchar(10) NOT NULL,
                        url varchar(250) NOT NULL,
                        where_is varchar(30) NULL,
                        browser_details varchar(500) NULL,
                    UNIQUE KEY ID (ID)
                    ) $charset_collate;"; */

                    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                    @dbDelta( $sql );
                }

                /**
                 * @since 0.8.3 2019-04-08 13:45:08 Release
                 */
                $row = $wpdb->query(  "SHOW COLUMNS FROM $table_name LIKE 'type_click'"  );
                if( empty( $row ) ){
                    $wpdb->query( "ALTER TABLE $table_name ADD type_click CHAR(20) NOT NULL DEFAULT 'b'" );
                }

                if( ! $installed_ver ){
                    add_option( WPAC_ID.'_db_version', $current_db_version );
                }else{
                    update_option( WPAC_ID.'_db_version', $current_db_version );
                }
            }

        }


        /**
         * Verify if there is a new version of the table
         *
         * @since 0.1   2019-03-21 05:00:47     Release
         * @static
         * @access public
         *
         * @return boolean
         */
        public static function if_changes_in_table_db(){

            global $wpdb;

            // Get the current installed version
            $current_version_db = get_option( WPAC_ID .'_db_version' );

            // Current version of the plugin
            $version_db         = WPAC_VERSION_DB;

            if( $current_version_db != $version_db ){
                return $version_db;
            }else{
                return false;
            }
        }
    }
}