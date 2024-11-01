<?php
/**
 * Save the record with a click
 *
 * @since 0.4 2019-03-30 16:26:02 Release
 * @since 0.7 2019-04-07 10:05:35 Add nonce verification for security
 *
 * @return json|array
 */
function wpac_save_click(){

    // ─── Verifies Nonce for security  ────────
    if ( ! empty( $_REQUEST['nonce'] ) && ! wp_verify_nonce( $_REQUEST['nonce'], 'wpac-click-bar' ) )  return;

    global $wpdb;

    // ─── We include the geolocation class ────────
    require_once WPAC_PATH . 'public/classes/class-geo.php';

    // ─── We get all GEO data ────────
    $ip        = wpac_get_real_ip();
    $geoplugin = new geoPlugin( $ip );

    // ─── We obtain the data transported from the page ────────
    $post_id         = (int)$_REQUEST['post_id'];
    $date_click      = date( "Y-m-d H:i:s" );
    $timestamp_click = time();
    $ip              = $ip;
    $la              = $geoplugin->latitude;
    $lo              = $geoplugin->longitude;
    $country         = $geoplugin->countryName;
    $country_code    = $geoplugin->countryCode;
    $region          = $geoplugin->regionName;
    $city            = $geoplugin->city;
    $device          = $_REQUEST['device'];
    $url             = $_REQUEST['url'];
    $where_is        = $_REQUEST['where_is'];
    $browser_details = $_REQUEST['browser_details'];
    $others          = json_decode( stripslashes( $_REQUEST['others'] ), true );  // ◄ return array from JSON.stringify javascript

    // ─── Process the 'other' field ────────
    $type   = ! empty( $others['type'] ) ? $others['type'] : 'b';

    // ─── Insert the click that the user made ────────
    $sql = "INSERT INTO {$wpdb->prefix}wpac_clicks
        (post_id,
        date_click,
        timestamp_click,
        ip,
        la,
        lo,
        country,
        country_code,
        region,
        city,
        device,
        url,
        where_is,
        browser_details,
        type_click)
        values(
        $post_id,
        '$date_click',
        $timestamp_click,
        '$ip',
        '$la',
        '$lo',
        '$country',
        '$country_code',
        '$region',
        '$city',
        '$device',
        '$url',
        '$where_is',
        '$browser_details',
        '$type'
    )";
    // FIXME: here the prepare does not work, I still do not understand why
    //values( %i, %s, %i, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
    $r = $wpdb->query( $sql );

    /*
    |--------------------------------------------------------------------------
    | Returns the data to affirm that the insert was made
    |--------------------------------------------------------------------------
    */
    wp_send_json_success(
        array(
            'result'    => ( $r ? true : false ),
            'error_sql' => WPAC_MODE_DEV ? wpac_sql_print_error()  : null,
            'sql'       => WPAC_MODE_DEV ? $sql : null,
            'request'   => WPAC_MODE_DEV ? $_REQUEST : null,
            'others'    => WPAC_MODE_DEV ? $others : null,
        )
    );
}

/**
 * Save the record view
 *
 * @since 0.8 2019-04-07 23:22:26 Release
 * @return json|array
 */
function wpac_save_view(){

    // ─── Verifies Nonce for security  ────────
    if ( ! empty( $_REQUEST['nonce'] ) && ! wp_verify_nonce( $_REQUEST['nonce'], 'wpac-click-bar' ) )  return;

    global $wpdb;

    // ─── We include the geolocation class ────────
    require_once WPAC_PATH . 'public/classes/class-geo.php';

    // ─── We get all GEO data ────────
    $ip        = wpac_get_real_ip();
    $geoplugin = new geoPlugin( $ip );

    // ─── We obtain the data transported from the page ────────
    $post_id         = (int)$_REQUEST['post_id'];
    $date_click      = date( "Y-m-d H:i:s" );
    $timestamp_click = time();
    $ip              = $ip;
    $la              = $geoplugin->latitude;
    $lo              = $geoplugin->longitude;
    $country         = $geoplugin->countryName;
    $country_code    = $geoplugin->countryCode;
    $region          = $geoplugin->regionName;
    $city            = $geoplugin->city;
    $device          = $_REQUEST['device'];
    $url             = $_REQUEST['url'];
    $where_is        = $_REQUEST['where_is'];
    $browser_details = $_REQUEST['browser_details'];
    $others          = json_decode( stripslashes( $_REQUEST['others'] ), true );  // ◄ return array from JSON.stringify javascript

    // ─── Process the 'other' field ────────
    $type   = ! empty( $others['type'] ) ? $others['type'] : 'b';

    // ─── Insert the click that the user made ────────
    $sql = "INSERT INTO {$wpdb->prefix}wpac_views
        (post_id,
        date_click,
        timestamp_click,
        ip,
        la,
        lo,
        country,
        country_code,
        region,
        city,
        device,
        url,
        where_is,
        browser_details,
        type_view)
        values(
        $post_id,
        '$date_click',
        $timestamp_click,
        '$ip',
        '$la',
        '$lo',
        '$country',
        '$country_code',
        '$region',
        '$city',
        '$device',
        '$url',
        '$where_is',
        '$browser_details',
        '$type'
    )";
    // FIXME: here the prepare does not work, I still do not understand why
    //values( %i, %s, %i, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
    $r = $wpdb->query( $sql );

    /*
    |--------------------------------------------------------------------------
    | Returns the data to affirm that the insert was made
    |--------------------------------------------------------------------------
    */
    wp_send_json_success(
        array(
            'result'    => ( $r ? true : false ),
            'error_sql' => WPAC_MODE_DEV ? wpac_sql_print_error()  : null,
            'sql'       => WPAC_MODE_DEV ? $sql : null,
            'request'   => WPAC_MODE_DEV ? $_REQUEST : null,
            'others'    => WPAC_MODE_DEV ? $others : null,
        )
    );
}



function wpac_sql_print_error(){

    global $wpdb;

    if( $wpdb->last_error !== '' ) :

        $str   = htmlspecialchars( $wpdb->last_result, ENT_QUOTES );
        $query = htmlspecialchars( $wpdb->last_query, ENT_QUOTES );

        return "<div id='error'>
        <p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br />
        <code>$query</code></p>
        </div>";

    endif;

}