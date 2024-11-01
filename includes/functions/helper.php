<?php
/**
 * Get the saved settings
 *
 * @since 0.1 2019-03-28 00:19:46 Release
 * @return object|mixed
 */
function wpac_get_option( $id ){
    return (object) get_option( $id );
}

/**
 * Generate CSS based on selector, properties and values
 *
 * @since 0.1 2019-03-28 00:20:37 Release
 * @since 0.8 2019-04-07 16:49:45 If $value is null, then place the variable $style
 *                                as part of $value
 *
 * @param string $selector Name of class/s
 * @param string $style Name of the property
 * @param string $value Value of the property
 * @param string $prefix Main prefix
 * @param string $postfix Postfix by name
 * @param boolean $echo TRUE=print | FALSE=return
 *
 * @return string
 */
function wpac_generate_css( $selector, $style, $value = null, $prefix='', $postfix='', $echo=true ) {
    $return = '';
    if ( ! empty( $value ) ) {
        $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$value.$postfix
        );
    }else{
        $return = sprintf('%s { %s; }',
            $selector,
            $style
        );
    }
    if ( $echo ) {
        echo $return;
    }
    return $return;
}

/**
 * Generates a range of numbers with 3 parameters
 *
 * @since 0.2 2019-03-28 21:10:34 Release
 */
function wpac_generate_range( $from = 1, $to = 30, $step = 1 ){
    return array_map( 'strval', range( $from, $to, $step) );
}

/**
 * Get real IP user
 * @since 0.4 2019-03-30 18:47:10 Release
 * @return string
 */
function wpac_get_real_ip( $ip_test = '' ){
    if( ! empty( $ip_test ) ) return $ip_test;
    switch(true){
        case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
        case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
        case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
        default : return $_SERVER['REMOTE_ADDR'];
    }
}
?>