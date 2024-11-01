<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Class field Notice
 *
 * @since 1.0 2019-03-02 17:03:25 Release
 *
 */
if( ! class_exists( 'PF_Field_notice' ) ) {
class PF_Field_notice extends PF_classFields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
        parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

        $style = ( ! empty( $this->field['style'] ) ) ? $this->field['style'] : 'normal';

        echo ( ! empty( $this->field['content'] ) ) ? '<div class="pf-notice pf-notice-'. $style .'">'. $this->field['content'] .'</div>' : '';

    }

}
}
