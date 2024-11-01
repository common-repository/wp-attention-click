<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Class field Radio
 *
 * @since 1.0 2019-03-02 18:00:12 Release
 *
 */
if( ! class_exists( 'PF_Field_radio' ) ) {
class PF_Field_radio extends PF_classFields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
        parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

        $args = wp_parse_args( $this->field, array(
            'inline' => false,
        ) );

        $inline_class = ( $args['inline'] ) ? ' class="pf--inline-list"' : '';

        echo $this->field_before();

        if( isset( $this->field['options'] ) ) {

            $options = $this->field['options'];
            $options = ( is_array( $options ) ) ? $options : array_filter( $this->field_data( $options ) );

        if( ! empty( $options ) ) {

            echo '<ul'. $inline_class .'>';
            foreach ( $options as $option_key => $option_value ) {
                $checked = ( $option_key === $this->value ) ? ' checked' : '';
                echo '<li><label><input type="radio" name="'. $this->field_name() .'" value="'. $option_key .'"'. $this->field_attributes() . $checked .'/> '. $option_value .'</label></li>';
            }
            echo '</ul>';

        } else {

            echo ( ! empty( $this->field['empty_message'] ) ) ? $this->field['empty_message'] : esc_html__( 'No data provided for this option type.', 'pf' );

        }

        } else {
            $label = ( isset( $this->field['label'] ) ) ? $this->field['label'] : '';
            echo '<label><input type="radio" name="'. $this->field_name() .'" value="1"'. $this->field_attributes() . checked( $this->value, 1, false ) .'/> '. $label .'</label>';
        }

        echo $this->field_after();

    }

}
}
