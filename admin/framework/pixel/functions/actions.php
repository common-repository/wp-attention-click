<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0 2019-03-05 23:16:35 Release
 */
function pf_get_icons() {

    if( ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'pf_icon_nonce' ) ) {

        ob_start();

        PF::include_plugin_file( 'fields/icon/default-icons.php' );

        $icon_lists = apply_filters( 'pf_field_icon_add_icons', pf_get_default_icons() );

        if( ! empty( $icon_lists ) ) {

            foreach ( $icon_lists as $list ) {

                echo ( count( $icon_lists ) >= 2 ) ? '<div class="pf-icon-title">'. $list['title'] .'</div>' : '';

                foreach ( $list['icons'] as $icon ) {
                    echo '<a class="pf-icon-tooltip" data-pf-icon="'. $icon .'" title="'. $icon .'"><span class="pf-icon pf-selector"><i class="'. $icon .'"></i></span></a>';
                }

            }

        } else {

            echo '<div class="pf-text-error">'. esc_html__( 'No data provided by developer', 'pf' ) .'</div>';

        }

        wp_send_json_success( array( 'success' => true, 'content' => ob_get_clean() ) );

    } else {

        wp_send_json_error( array( 'success' => false, 'error' => esc_html__( 'Error while saving.', 'pf' ), 'debug' => $_REQUEST ) );

    }

}
add_action( 'wp_ajax_pf-get-icons', 'pf_get_icons' );

/**
 *
 * Export
 *
 * @since 1.0 2019-03-05 23:18:01 Release
 *
 */
function pf_export() {

    if( ! empty( $_GET['export'] ) && ! empty( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], 'pf_backup_nonce' ) ) {

        header('Content-Type: application/json');
        header('Content-disposition: attachment; filename=backup-'. gmdate( 'd-m-Y' ) .'.json');
        header('Content-Transfer-Encoding: binary');
        header('Pragma: no-cache');
        header('Expires: 0');

        if( ! empty( $_GET['where'] ) && $_GET['where'] == 'metabox' ){
            $val = get_post_meta( ( isset($_GET['post_id']) ? $_GET['post_id'] : 0 ) , $_GET['export'] );
			echo json_encode( $val[0] );
        }else{
            echo json_encode( get_option( wp_unslash( $_GET['export'] ) ) );
        }

    }

    die();
}
add_action( 'wp_ajax_pf-export', 'pf_export' );

/**
 *
 * Import Ajax
 *
 * @since   1.0     2019-03-05 23:18:34     Release
 * @since   1.4.9   2019-07-12 07:43:49     Now validate to be able to save import export in metabox
 *
 */
function pf_import_ajax() {

    if( ! empty( $_POST['import_data'] ) && ! empty( $_POST['unique'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'pf_backup_nonce' ) ) {

        //$import_data = unserialize( stripslashes( ( trim( $_POST['import_data'] ) ) ) );
        //echo ''; var_dump( json_decode(stripslashes( trim( $_POST['import_data'] ) ) )  ); echo '';exit;
        //var_dump( serialize(stripslashes(trim( $_POST['import_data'] )))) ;
        $import_data = json_decode( wp_unslash( trim( $_POST['import_data'] ) ), true );

        if( is_array( $import_data ) ) {

            if( ! empty( $_POST['where'] ) && $_POST['where'] == 'metabox' && ! empty( $_POST['post_id'] ) ){
                update_post_meta( $_POST['post_id'], wp_unslash( $_POST['unique'] ), wp_unslash( $import_data ) );
                wp_send_json_success( array( 'success' => true ) );
            }else{
                update_option( wp_unslash( $_POST['unique'] ), wp_unslash( $import_data ) );
                wp_send_json_success( array( 'success' => true ) );
            }

        }

    }

    wp_send_json_error( array( 'success' => false, 'error' => esc_html__( 'Error while saving.', 'pf' ), 'debug' => $_REQUEST, 'data-formated' => $import_data ) );

}
add_action( 'wp_ajax_pf-import', 'pf_import_ajax' );

/**
 *
 * Reset Ajax
 *
 * @since 1.0 2019-03-05 23:19:32 Release
 *
 */
function pf_reset_ajax() {

    if( ! empty( $_POST['unique'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'pf_backup_nonce' ) ) {
        delete_option( wp_unslash( $_POST['unique'] ) );
        wp_send_json_success( array( 'success' => true ) );
    }

    wp_send_json_error( array( 'success' => false, 'error' => esc_html__( 'Error while saving.', 'pf' ), 'debug' => $_REQUEST ) );
}
add_action( 'wp_ajax_pf-reset', 'pf_reset_ajax' );

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0 2019-03-05 23:19:41 Release
 *
 */
function pf_set_icons() {
    ?>
    <div id="pf-modal-icon" class="pf-modal pf-modal-icon">
    <div class="pf-modal-table">
        <div class="pf-modal-table-cell">
        <div class="pf-modal-overlay"></div>
        <div class="pf-modal-inner">
            <div class="pf-modal-title">
            <?php esc_html_e( 'Add Icon', 'pf' ); ?>
            <div class="pf-modal-close pf-icon-close"></div>
            </div>
            <div class="pf-modal-header pf-text-center">
            <input type="text" placeholder="<?php esc_html_e( 'Search a Icon...', 'pf' ); ?>" class="pf-icon-search" />
            </div>
            <div class="pf-modal-content">
            <div class="pf-modal-loading"><div class="pf-loading"></div></div>
            <div class="pf-modal-load"></div>
            </div>
        </div>
        </div>
    </div>
    </div>
    <?php
}
add_action( 'admin_footer', 'pf_set_icons', 10, 1);
//add_action( 'customize_controls_print_footer_scripts', 'pf_set_icons' );

/**
 *
 * Search post Ajax
 *
 * @since   1.4.3   2019-05-10  Release
 * @since   1.5.2   2019-08-31  It was added that by default the CP is 'posts'
 * @return  array|json
 */
function pf_search_post_ajax() {

    global $wpdb;

    // Term search from input
    $s   = $_REQUEST['q'];

    // Validate filter cpt
    $sql_cpt = '';
    if( $_REQUEST['filter'] == 1 ){
        if( ! empty( $_REQUEST['cpt'] ) ){
            if( $_REQUEST['cpt'] == 'all' || $_REQUEST['cpt'] == ''  || $_REQUEST['cpt'] == 'null' || !$_REQUEST['cpt'] ){
                $sql_cpt = " AND p.post_type = 'post' ";
            }else{
                $sql_cpt = " AND p.post_type =  '". $_REQUEST['cpt'] ."' ";
            }
        }
    }else{
        $sql_cpt = " AND p.post_type = 'post' ";
    }


    // Exclude ids that are already selected
    $sql_exclude = '';
    if( ! empty( $_REQUEST['exclude'] ) ){
        $exclude_array = explode(",",$_REQUEST['exclude']) ;
        if( ! empty( $exclude_array ) ){
            $exclude_sanitize = [];
            foreach ($exclude_array as $key => $value) {
                if( $value )
                    $exclude_sanitize[] = $value;
            }

            if( ! empty( $exclude_sanitize ) ){
                $sql_exclude = " AND p.ID NOT IN (". implode(",",$exclude_sanitize) .") ";
            }
        }
    }


    $sql = "SELECT
    p.ID as id, p.post_title as title
    FROM {$wpdb->prefix}posts p
    WHERE
    p.post_status =  'publish'
    $sql_cpt
    $sql_exclude
    AND  post_title like '%{$s}%'
    AND post_title <> ''
    LIMIT 0, 20";

    if( ! empty( $_REQUEST['nonce'] ) && wp_verify_nonce( $_REQUEST['nonce'], 'pf_search_post_nonce' ) ) {

        $r      = $wpdb->get_results( $sql );
        $result = [];

        if( ! empty( $r ) ){
            $image = new \wpImage;
            foreach ($r as $key => $value) {
                $img = $image->get_image(['post_id' => $value->id, 'size' => 'thumbnail' ]);
                $result[$key]['id']    = $value->id;
                $result[$key]['title'] = $value->title;
                $img                   = $img->src;
                $result[$key]['image'] = ! empty( $img ) ? $img : '' ;
            }
        }

        //wp_send_json_success( [ 'a' => $r, 'b' => $sql, 'c' => $_REQUEST ] );
        if( ! empty( $result ) ){
            wp_send_json_success( $result );
        }else{
            wp_send_json_success( [ 'data' => array( 'title' => __('No results','pixel'), 'id' => null  ) ] );
        }

    }

    wp_send_json_error( array( 'success' => false, 'error' => esc_html__( 'Error while search.', 'pf' ), 'debug' => $_REQUEST, 'sql' => $sql ) );

}
add_action( 'wp_ajax_pf-search-post-ajax', 'pf_search_post_ajax' );