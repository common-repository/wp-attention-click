<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Class field Checkbox
 *
 * @since 1.0 2019-03-02 17:24:32 Release
 *
 */
if( ! class_exists( 'PF_Field_checkbox' ) ) {
class PF_Field_checkbox extends PF_classFields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
        parent::__construct( $field, $value, $unique, $where, $parent );
    }

    /**
     * Render field
     *
     * @since   1.4.6   2019-06-12 19:17:37     Change of validation in the check list
     * @return  string
     */
    public function render() {

        $args = wp_parse_args( $this->field, array(
            'inline' => false,
        ) );

        $inline_class = ( $args['inline'] ) ? ' class="pf--inline-list"' : '';

        echo $this->field_before();

        if( isset( $this->field['options'] ) ) {

            $value   = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );
            $options = $this->field['options'];
            $options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options ) );

            if( is_array( $options ) && ! empty( $options ) ) {

                echo '<ul'. $inline_class .'>';
                foreach ( $options as $option_key => $option_value ) {
                    $checked = ( in_array( $option_key, $value ) ) ? ' checked' : '';
                    echo '<li><label><input type="checkbox" name="'. $this->field_name( '[]' ) .'" value="'. $option_key .'"'. $this->field_attributes() . $checked .'/> '. $option_value .'</label></li>';
                }
                echo '</ul>';

            } else {

                echo ( ! empty( $this->field['empty_message'] ) ) ? $this->field['empty_message'] : esc_html__( 'No data provided for this option type.', 'pf' );

            }

        } else {
            echo '<label class="pf-checkbox">';
            echo '<input type="hidden" name="'. $this->field_name() .'" value="'. $this->value .'" class="pf--input"'. $this->field_attributes() .'/>';
            echo '<input type="checkbox" class="pf--checkbox"'. checked( $this->value, 1, false ) .'/>';
            echo ( ! empty( $this->field['label'] ) ) ? ' '. $this->field['label'] : '';
            echo '</label>';
        }

        echo $this->field_after();

    }

}
}
