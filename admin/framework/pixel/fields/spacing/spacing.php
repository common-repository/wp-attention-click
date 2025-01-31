<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.0
/**
 *
 * Class Field spacing
 *
 * @since 1.0 2019-03-05 18:03:48 Release
 *
 */
if( ! class_exists( 'PF_Field_spacing' ) ) {
  class PF_Field_spacing extends PF_classFields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    /**
     * Render field
     *
     * @since   1.4.6   2019-06-12 21:16:03     Validation in the unit and show_unit
     * @return  string
     */
    public function render() {

      $args = wp_parse_args( $this->field, array(
        'top_icon'           => '<i class="fa fa-long-arrow-up"></i>',
        'right_icon'         => '<i class="fa fa-long-arrow-right"></i>',
        'bottom_icon'        => '<i class="fa fa-long-arrow-down"></i>',
        'left_icon'          => '<i class="fa fa-long-arrow-left"></i>',
        'all_icon'           => '<i class="fa fa-arrows"></i>',
        'top_placeholder'    => 'top',
        'right_placeholder'  => 'right',
        'bottom_placeholder' => 'bottom',
        'left_placeholder'   => 'left',
        'all_placeholder'    => 'all',
        'top'                => true,
        'left'               => true,
        'bottom'             => true,
        'right'              => true,
        'unit'               => true,
        'show_units'         => true,
        'all'                => false,
        'units'              => array( 'px', '%', 'em' )
      ) );

      $default_values = array(
        'top'    => '',
        'right'  => '',
        'bottom' => '',
        'left'   => '',
        'all'    => '',
        'unit'   => 'px',
      );

      $value = wp_parse_args( $this->value, $default_values );

      echo $this->field_before();

      if( ! empty( $args['all'] ) ) {

        $placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="'. $args['all_placeholder'] .'"' : '';

        echo '<div class="pf--input">';
        echo ( ! empty( $args['all_icon'] ) ) ? '<span class="pf--label pf--label-icon">'. $args['all_icon'] .'</span>' : '';
        echo '<input type="text" name="'. $this->field_name('[all]') .'" value="'. $value['all'] .'"'. $placeholder .' class="pf-number" />';
        echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="pf--label pf--label-unit">'. $args['units'][0] .'</span>' : '';
        echo '</div>';

      } else {

        $properties = array();

        foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
          if( ! empty( $args[$prop] ) ) {
            $properties[] = $prop;
          }
        }

        $properties = ( $properties === array( 'right', 'left' ) ) ? array_reverse( $properties ) : $properties;

        foreach( $properties as $property ) {

          $placeholder = ( ! empty( $args[$property.'_placeholder'] ) ) ? ' placeholder="'. $args[$property.'_placeholder'] .'"' : '';

          echo '<div class="pf--input">';
          echo ( ! empty( $args[$property.'_icon'] ) ) ? '<span class="pf--label pf--label-icon">'. $args[$property.'_icon'] .'</span>' : '';
          echo '<input type="text" name="'. $this->field_name('['. $property .']') .'" value="'. $value[$property] .'"'. $placeholder .' class="pf-number" />';
          echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="pf--label pf--label-unit">'. $args['units'][0] .'</span>' : '';
          echo '</div>';

        }

      }

      if( ! empty( $args['unit'] ) && ! empty( $args['show_units'] ) && count( $args['units'] ) > 1 ) {
        echo '<select name="'. $this->field_name('[unit]') .'">';
        foreach( $args['units'] as $unit ) {
          $selected = ( $value['unit'] === $unit ) ? ' selected' : '';
          echo '<option value="'. $unit .'"'. $selected .'>'. $unit .'</option>';
        }
        echo '</select>';
      }

      echo '<div class="clear"></div>';

      echo $this->field_after();

    }

    public function output() {

      $output    = '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $unit      = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';

      $mode = ( ! empty( $this->field['output_mode'] ) ) ? $this->field['output_mode'] : 'padding';
      $mode = ( $mode === 'relative' || $mode === 'absolute' || $mode === 'none' ) ? '' : $mode;
      $mode = ( ! empty( $mode ) ) ? $mode .'-' : '';

      if( ! empty( $this->field['all'] ) && isset( $this->value['all'] ) && $this->value['all'] !== '' ) {

        $output  = $element .'{';
        $output .= $mode .'top:'.    $this->value['all'] . $unit . $important .';';
        $output .= $mode .'right:'.  $this->value['all'] . $unit . $important .';';
        $output .= $mode .'bottom:'. $this->value['all'] . $unit . $important .';';
        $output .= $mode .'left:'.   $this->value['all'] . $unit . $important .';';
        $output .= '}';

      } else {

        $top     = ( isset( $this->value['top']    ) && $this->value['top']    !== '' ) ?  $mode .'top:'.    $this->value['top']    . $unit . $important .';' : '';
        $right   = ( isset( $this->value['right']  ) && $this->value['right']  !== '' ) ?  $mode .'right:'.  $this->value['right']  . $unit . $important .';' : '';
        $bottom  = ( isset( $this->value['bottom'] ) && $this->value['bottom'] !== '' ) ?  $mode .'bottom:'. $this->value['bottom'] . $unit . $important .';' : '';
        $left    = ( isset( $this->value['left']   ) && $this->value['left']   !== '' ) ?  $mode .'left:'.   $this->value['left']   . $unit . $important .';' : '';

        if( $top !== '' || $right !== '' || $bottom !== '' || $left !== '' ) {
          $output = $element .'{'. $top . $right . $bottom . $left .'}';
        }

      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}
