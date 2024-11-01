<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Fields Classs
 *
 * @package PF
 * @subpackage PF/Classes
 *
 * @since 1.0 2019-02-25 13:55:09 Release
 * @since 1.2.2 2019-03-20 14:05:56 Removed spaces in the file header
 *
 */
abstract class PF_classFields extends PF_classAbstract {

    public function __construct( $field = array(), $value = '', $unique = '', $where = '', $parent = '' ) {
        $this->field  = $field;
        $this->value  = $value;
        $this->unique = $unique;
        $this->where  = $where;
        $this->parent = $parent;
    }

    public function field_name( $nested_name = '' ) {

        $field_id   = ( ! empty( $this->field['id'] ) ) ? $this->field['id'] : '';
        $unique_id  = ( ! empty( $this->unique ) ) ? $this->unique .'['. $field_id .']' : $field_id;
        $field_name = ( ! empty( $this->field['name'] ) ) ? $this->field['name'] : $unique_id;
        $tag_prefix = ( ! empty( $this->field['tag_prefix'] ) ) ? $this->field['tag_prefix'] : '';

        if( ! empty( $tag_prefix ) ) {
            $nested_name = str_replace( '[', '['. $tag_prefix, $nested_name );
        }

        return $field_name . $nested_name;

    }

    public function field_attributes( $custom_atts = array() ) {

        $field_id   = ( ! empty( $this->field['id'] ) ) ? $this->field['id'] : '';
        $attributes = ( ! empty( $this->field['attributes'] ) ) ? $this->field['attributes'] : array();

        if( ! empty( $field_id ) ) {
            $attributes['data-depend-id'] = $field_id;
        }

        if( ! empty( $this->field['placeholder'] ) ) {
            $attributes['placeholder'] = $this->field['placeholder'];
        }

        $attributes = wp_parse_args( $attributes, $custom_atts );

        $atts = '';

        if( ! empty( $attributes ) ) {
            foreach ( $attributes as $key => $value ) {
                if( $value === 'only-key' ) {
                    $atts .= ' '. $key;
                } else {
                    $atts .= ' '. $key . '="'. $value .'"';
                }
            }
        }

        return $atts;
    }

    /**
     * Add the tooltip attribute for fields
     *
     * @since   1.5.1           2019-07-16 09:54:57 Release
     * @param   array|string    $attr               Tooltip parameter, even incomplete
     * @return  string
     */
    public function field_tooltip( $attr = null ){

        if( is_string($attr) ){
            return ' data-tooltip = "' . $attr . '" ';
        }elseif( is_array( $attr ) ){
            // FUTURE here you must put the color attribute, position among others
        }

        return '';

    }

    public function field_before() {
        return ( ! empty( $this->field['before'] ) ) ? $this->field['before'] : '';
    }

    /**
     * Add data after the field
     *
     * @since   1.2.3   2019-03-20 23:22:50     Improve: add class 'pf-after'
     * @since   1.4.6   2019-06-12 18:34:12     Change of position of variables
     * @return void
     */
    public function field_after() {

        $output = ( ! empty( $this->field['after'] ) ) ? '<span class="pf-after">' . $this->field['after'] . '</span>' : '';
        $output .= ( ! empty( $this->field['desc'] ) ) ? '<p class="pf-text-desc">'. $this->field['desc'] .'</p>' : '';
        $output .= ( ! empty( $this->field['help'] ) ) ? '<span class="pf-help"><span class="pf-help-text">'. $this->field['help'] .'</span><span class="fa fa-question-circle"></span></span>' : '';
        $output .= ( ! empty( $this->field['_error'] ) ) ? '<p class="pf-text-error">'. $this->field['_error'] .'</p>' : '';

        return $output;

    }

    /**
     * JS Arguments for jQuery plugins
     *
     * @since   1.4.8   2019-07-11 01:08:49     Release
     * @return string|html
     */
    public function field_attributes_js(){

        $out      = '';
        $out_html = '';
        $property_A = [];
        if( ! empty( $this->field['attributes_js'] ) ){
            $out      .= '{';
            foreach( $this->field['attributes_js'] as $property => $value ){
                $property_A[] = " $property : $value ";
            }
            $out      .= implode(",",$property_A) . '}';
            $out_html = " data-attributes-js = '".($out)."'";
        }

        return $out_html;

    }

    /**
     * Field callback functions
     *
     * @@since      1.5     2019-07-14 13:15:54     Documented
     * @param       string  $type
     * @return      void
     */
    public function field_data( $type = '' ) {

        $options = array();
        $query_args = ( ! empty( $this->field['query_args'] ) ) ? $this->field['query_args'] : array();

        switch( $type ) {

            case 'page':
            case 'pages':

            $pages = get_pages( $query_args );

            if ( ! is_wp_error( $pages ) && ! empty( $pages ) ) {
                foreach ( $pages as $page ) {
                $options[$page->ID] = $page->post_title;
                }
            }

            break;

            case 'post':
            case 'posts':

                $posts = get_posts( $query_args );

                if ( ! is_wp_error( $posts ) && ! empty( $posts ) ) {
                    foreach ( $posts as $post ) {
                    $options[$post->ID] = $post->post_title;
                    }
                }

            break;

            case 'category':
            case 'categories':

                $categories = get_categories( $query_args );

                if ( ! is_wp_error( $categories ) && ! empty( $categories ) && ! isset( $categories['errors'] ) ) {
                    foreach ( $categories as $category ) {
                    $options[$category->term_id] = $category->name;
                    }
                }

            break;

            case 'tag':
            case 'tags':

                $taxonomies = ( isset( $query_args['taxonomies'] ) ) ? $query_args['taxonomies'] : 'post_tag';
                $tags = get_terms( $taxonomies, $query_args );

                if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
                    foreach ( $tags as $tag ) {
                    $options[$tag->term_id] = $tag->name;
                    }
                }

            break;

            case 'menu':
            case 'menus':

                $menus = wp_get_nav_menus( $query_args );

                if ( ! is_wp_error( $menus ) && ! empty( $menus ) ) {
                    foreach ( $menus as $menu ) {
                    $options[$menu->term_id] = $menu->name;
                    }
                }

            break;

            case 'post_type':
            case 'post_types':

                $post_types = get_post_types( array(
                    //'show_in_nav_menus' => true
                ), "objects" );
                $exclude = array(
                    'attachment',
                    'revision',
                    'nav_menu_item',
                    'custom_css',
                    'customize_changeset',
                    'oembed_cache',
                    'user_request',
                    'wp_block',
                    'yuzo'
                );

                if( ! empty( $query_args['exclude'] ) && is_array( $query_args['exclude'] ) ){
                    $exclude = array_merge($exclude, $query_args['exclude']);
                }

                if ( ! is_wp_error( $post_types ) && ! empty( $post_types ) ) {
                    foreach ( $post_types as $post_type ) {
                        if( TRUE === in_array( $post_type->name, $exclude ) ) continue;
                        $options[$post_type->name] = ucfirst($post_type->labels->name);
                    }
                }

            break;

            case 'sidebar':
            case 'sidebars':

                global $wp_registered_sidebars;

                if( ! empty( $wp_registered_sidebars ) ) {
                    foreach( $wp_registered_sidebars as $sidebar ) {
                    $options[$sidebar['id']] = $sidebar['name'];
                    }
                }

            break;

            default:
                if( function_exists( $type ) ) {
                    $options = call_user_func( $type, $this->value, $this->field );
                }
            break;

        }

        return $options;

    }

}