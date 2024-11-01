<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Class field Textarea
 *
 * @since 1.0 2019-03-01 18:05:00 Release
 */
if( ! class_exists( 'PF_Field_textarea' ) ) {
class PF_Field_textarea extends PF_classFields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
		parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

        echo $this->field_before();

        echo '<textarea name="'. $this->field_name() .'"'. $this->field_attributes() .'>'. $this->value .'</textarea>';

        echo $this->field_after();

	}

}
}