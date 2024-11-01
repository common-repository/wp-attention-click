<?php
/**
 * Get the current full URL of the website
 *
 * @since 0.4 2019-03-30 19:34:14 Release
 * @return string
 */
function wpac_current_location(){
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Get the place/location of wordpress
 *  * Check if it is: is_home
 *  * Check if it is: is_archive(), is_single(), is_singlular(), etc...
 *
 * @since 0.4 2019-03-30 20:01:06 Release
 *
 * @return array
 */
function wpac_get_query_flags( $wp_query = null ) {
    if ( !$wp_query )
        $wp_query = $GLOBALS['wp_query'];

    $flags = array();

    foreach ( get_object_vars( $wp_query ) as $key => $val ) {
        if ( 'is_' == substr( $key, 0, 3 ) && $val )
            $flags[] = substr( $key, 3 );
    }

    return ( $flags );
}