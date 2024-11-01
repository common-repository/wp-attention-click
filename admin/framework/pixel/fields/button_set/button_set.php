<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.0
/**
 *
 * Class Field button_set
 *
 * @since 1.0 	2019-03-06 01:30:15 Release
 * @since 1.5.1	2019-07-16 09:52:43	Tooltip is added on the buttons
 *
 */
if( ! class_exists( 'PF_Field_button_set' ) ) {
class PF_Field_button_set extends PF_classFields {

	public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
		parent::__construct( $field, $value, $unique, $where, $parent );
	}

	public function render() {

		$args = wp_parse_args( $this->field, array(
			'multiple' => false,
			'options'  => array(),
			) );

		$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

		echo $this->field_before();

		if( ! empty( $args['options'] ) ) {

			echo '<div class="pf-siblings pf--button-group" data-multiple="'. $args['multiple'] .'">';

			foreach( $args['options'] as $key => $option ) {

				$type    = ( $args['multiple'] ) ? 'checkbox' : 'radio';
				$extra   = ( $args['multiple'] ) ? '[]' : '';
				$active  = ( in_array( $key, $value ) ) ? ' pf--active' : '';
				$checked = ( in_array( $key, $value ) ) ? ' checked' : '';
				$attribute_sub = ( isset( $args['attributes_sub'] ) ? $args['attributes_sub'] : null );
				/* if( $this->field['id'] == 'design_screen' ) */
				$tooltip = ! empty( $attribute_sub['tooltip'] ) ? $attribute_sub['tooltip'][$key] : null;
				/* if( $args['id'] == 'design_screen' ){
					echo '<pre>$tooltip<br />'; var_dump($tooltip); echo '</pre>';exit;

				} */
				echo '<div class="pf--sibling pf--button'. $active .'" '. $this->field_tooltip($tooltip) .'>';
				echo '<input type="'. $type .'" name="'. $this->field_name( $extra ) .'" value="'. $key .'"'. $this->field_attributes() . $checked .'/>';
				echo $option;
				echo '</div>';

			}

			echo '</div>';

		}

		echo '<div class="clear"></div>';

		echo $this->field_after();

	}

}
}
