<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Class field Select
 *
 * @since 1.0 2019-03-02 13:08:46 Release
 *
 */
if( ! class_exists( 'PF_Field_select' ) ) {
class PF_Field_select extends PF_classFields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
        parent::__construct( $field, $value, $unique, $where, $parent );
    }

    /**
     * Render field
     *
     * @since   1.4.6   2019-06-12 21:14:35     New validation if there are options
     * @return  string
     */
    public function render() {

        $args = wp_parse_args( $this->field, array(
            'chosen'      => false,
            'multiple'    => false,
            'placeholder' => '',
        ) );

        $this->value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

        echo $this->field_before();

        if( isset( $this->field['options'] ) ) {

            $options          = ( is_array( $this->field['options'] ) ) ? $this->field['options'] : $this->field_data( $this->field['options'] );
            $multiple_name    = ( $args['multiple'] ) ? '[]' : '';
            $multiple_attr    = ( $args['multiple'] ) ? ' multiple="multiple"' : '';
            $chosen_rtl       = ( is_rtl() ) ? ' chosen-rtl' : '';
            $chosen_attr      = ( $args['chosen'] ) ? ' class="pf-chosen'. $chosen_rtl .'"' : '';
            $placeholder_attr = ( $args['chosen'] && $args['placeholder'] ) ? ' data-placeholder="'. $args['placeholder'] .'"' : '';

            if( is_array( $options ) && ! empty( $options ) ) {

                echo '<select name="'. $this->field_name( $multiple_name ) .'"'. $multiple_attr . $chosen_attr . $placeholder_attr . $this->field_attributes() .'>';

                if( $args['placeholder'] && empty( $args['multiple'] ) ) {
                    if( ! empty( $args['chosen'] ) ) {
                        echo '<option value=""></option>';
                    } else {
                        echo '<option value="">'. $args['placeholder'] .'</option>';
                    }
                }

                foreach ( $options as $option_key => $option ) {

                    if( is_array( $option ) && ! empty( $option ) ) {

                        echo '<optgroup label="'. $option_key .'">';

                        foreach( $option as $sub_key => $sub_value ) {
                            $selected = ( in_array( $sub_key, $this->value ) ) ? ' selected' : '';
                            echo '<option value="'. $sub_key .'" '. $selected .'>'. $sub_value .'</option>';
                        }

                        echo '</optgroup>';

                    } else {
                        $selected = ( in_array( $option_key, $this->value ) ) ? ' selected' : '';
                        echo '<option value="'. $option_key .'" '. $selected .'>'. $option .'</option>';
                    }

                }

                echo '</select>';

            } else {

                echo ( ! empty( $this->field['empty_message'] ) ) ? $this->field['empty_message'] : esc_html__( 'No data provided for this option type.', 'pf' );

            }

        }

        echo $this->field_after();

    }

}
}
