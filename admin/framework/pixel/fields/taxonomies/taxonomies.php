<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Class field Taxonomies hierarchical list
 *
 * @since   1.4     2019-04-19 15:42:10     Release
 */
if( ! class_exists( 'PF_Field_taxonomies' ) ) {
class PF_Field_taxonomies extends PF_classFields {

    public
    /**
     * Public variable of the arguments in this class
     * @since   1.4.8   2019-07-01 13:43:48     Release
     * @access  public
     */
    $args = [];

    /**
     *Excludes tanoxomies that should not be taken into account
     * @since   1.4.2   2019-04-29 10:08:07     Release
     * @var     array
     */
    public $taxonomies_excludes = ['kc-section-category', 'post_format'];

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
		parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

        $this->args = $args_opt = wp_parse_args( $this->field, array(
            'hierarchical'     => true,
            'hierarchical_nav' => true,
        ) );

        // ─── I call the hierarchical taxonomies ────────
        $taxonomies_names    = $this->get_taxonomies_by_cpt( 'all', $args_opt['hierarchical'] );
        $taxonomies_excludes = $this->taxonomies_excludes + ( ! empty( $this->field['tax_exclude'] ) && is_array($this->field['tax_exclude']) ? $this->field['tax_exclude'] : [] );

		echo $this->field_before();

        // ─── first I create the wrap ────────
        echo '<div class="pf-taxonomy-wrap">';

        if( ! empty( $taxonomies_names ) ){

            // ─── Navegacion ────────
            echo '<ul class="pf-taxonomy-wrap-nav">';
            $i                    = 1;
            $slug_cookie_last_tab = 'pf-lasttab-taxonomies-id-'. $this->field['id'] . '-' . $this->unique;
            $cookie_last_tab      = ! empty( $_COOKIE[$slug_cookie_last_tab] ) ? $_COOKIE[$slug_cookie_last_tab] : 1;

            foreach ( $taxonomies_names as $key => $value ) {

                $nav_active           = ( $i == $cookie_last_tab ) ? 'pf-taxonomy-nav-active' : '';
                $name                 = get_taxonomy( $value );
                $names_cpt            = $this->get_all_post_type_object( $name );

                // if not name no show
                if( !$name || !$names_cpt ){
                    continue;
                }

                // Exclude
                if( in_array($value, $taxonomies_excludes) ){
                    continue;
                }
                //var_dump( $name ); exit;
                echo '<li class="pf-taxonomy-nav '. $nav_active .'" data-name="'. $name->name .'">';

                echo '<a href="#" class="pf-taxonomy-nav-tab" data-id-content="'. $i .'">'. $name->labels->name .' ('. $names_cpt .')</a>';

                echo '</li>';

                $i++;
            }

            echo '</ul>';

            // ─── Content ────────
            $args = [
                'slug_cookie_last_tab' => $slug_cookie_last_tab,
                'taxonomies_names'     => $taxonomies_names,
                'cookie_last_tab'      => $cookie_last_tab,
                'nav_active'           => $nav_active,
                'names_cpt'            => $names_cpt,
                'name'                 => $name,
            ];

            if( $args_opt['hierarchical'] == true ){
                $this->content_hierarchical( $args );
            }else{
                $this->content_no_hierarchical( $args );
            }
        }

        echo '</div>';

		echo $this->field_after();

    }

    private function content_no_hierarchical( $args = [] ){

        extract( $args );
        $i = 1;
        echo '<div class="pf-taxonomy-content pf-taxonomy-content-no-hierarchical pf-field-tag">';

        $taxonomies_excludes = $this->taxonomies_excludes + ( ! empty( $this->field['tax_exclude'] ) && is_array($this->field['tax_exclude']) ? $this->field['tax_exclude'] : [] );

        foreach ( $taxonomies_names as $key => $value ) {

            // if not name no show
            if( !$name || !$names_cpt ){
                continue;
            }

            // Exclude
            if( in_array( $value, $taxonomies_excludes ) ){
                continue;
            }

            // Validate taba active
            $nav_content_active  = ( $i == $cookie_last_tab ) ? 'style="display:block;"' : 'style="display:none;"';

            echo '<div data-name="'. $value .'" class="pf-taxonomy-content-tab pf-taxonomy-content-tab-id-'. $i .'" '. $nav_content_active .' >';
            echo '<textarea data-cpt="'. $value .'" class="pf-tax-no-her-'. $value .' tag-editor" name="'. $this->field_name() . '['. $value .']" >'. ( ! empty( $this->value[$value] ) ? $this->value[$value] : ''  ) .'</textarea>';
            echo '</div>';
            $i++;
        }
        echo '</div>';
    }

    private function content_hierarchical( $args = [] ){

        extract( $args );
        $i = 1;
        echo '<div class="pf-taxonomy-content pf-taxonomy-content-hierarchical">';

        $taxonomies_excludes = $this->taxonomies_excludes + ( ! empty( $this->field['tax_exclude'] ) && is_array($this->field['tax_exclude']) ? $this->field['tax_exclude'] : [] );

        foreach ( $taxonomies_names as $key => $value ) {

            // if not name no show
            if( !$name || !$names_cpt ){
                continue;
            }

            // Exclude
            if( in_array( $value, $taxonomies_excludes ) ){
                continue;
            }

            // Validate taba active
            $nav_content_active  = ( $i == $cookie_last_tab ) ? 'style="display:block;"' : 'style="display:none;"';

            echo '<div data-name="'. ( $value ) .'" class="pf-taxonomy-content-tab pf-taxonomy-content-tab-id-'. $i .'" '. $nav_content_active .' >';

                // Button all
                $tax_active1  = isset($this->value[$value]['all']) ? ' pf--active' : '';
                $tax_active2  = !isset($this->value[$value]['all']) ? ' pf--active' : '';
                $class_hidden = ! empty( $tax_active1 ) ? ' hidden': '';
                $checked      = ! empty( $tax_active1 ) ? ' checked': '';
                $hidden_nav   = $this->args['hierarchical_nav'] == true ? '' : ' hidden';
                $terms_tax_her= '';
                echo '<div class="pf-taxonomy-selected-all pf-field pf-field-button_set'.$hidden_nav.'">';
                    echo '<div class="pf-siblings pf--button-group">';
                        echo '<div class="pf--sibling pf--button '.$tax_active1.'"><input type="radio" name="yuzo['.$this->field['id'].']['.$value.'][all]" value="1" '.$checked.'>'.__('All','pf').'</div>';
                        echo '<div class="pf--sibling pf--button '.$tax_active2.'"><input type="radio" value="">'.__('Individual selection','pf').'</div>';
                    echo '</div>';
                echo '</div>';

                $terms_tax_her .= $this->get_taxonomies_tree( [
                    'TermName'     => $value,
                    'termID'       => 0,
                    'separator'    => '',
                    'parent_shown' => false,
                    'value'        => isset($this->value[$value]) ? $this->value[$value] : [],
                ] );

                if( ! empty( $terms_tax_her ) ){
                    echo '<div class="pf-taxonomy-collapse '.$class_hidden.'">';
                    echo '<a href="#" class="btn-select-all button button-small">'. __('Select all','yuzo')  .'</a>';
                    echo $terms_tax_her;
                    echo '</div>';
                }

            echo '</div>';
            $i++;
        }
        echo '</div>';
    }

    private function get_taxonomies_by_cpt( $type_post = 'post' , $hierarchical = true ){

		$out = [];
		if( $type_post == 'all' ){

			$cpt = $this->get_custom_type_post( [ 'only_array_types' => true ] );

            foreach ($cpt as $cpt_key => $cpt_value) {
				$taxonomies = get_object_taxonomies( $cpt_value );
				if( is_array($taxonomies) ){
					foreach ($taxonomies as $key => $value) {
						// We validate if it is a nested taxonomy
						if( $hierarchical == true && is_taxonomy_hierarchical($value) && ! in_array( $value, $out )  ){
							$out[] = $value;
						}elseif( $hierarchical == false && ! is_taxonomy_hierarchical($value) && ! in_array( $value, $out ) ){
                            $out[] = $value;
                        }
					}
				}
			}
		}else{
			$taxonomies = get_object_taxonomies( $type_post );
			if( is_array($taxonomies) ){
				foreach ($taxonomies as $key => $value) {
					// We validate if it is a nested taxonomy
					if( $hierarchical == true && is_taxonomy_hierarchical($value) && ! in_array( $value, $out ) ){
						$out[] = $value;
					}
				}
			}
        }

		return $out;

    }

    /**
     * Gets the custom post type of the system
     *
     * @since   6.0     2019-04-19 12:23:15     Release
     *
     * @param   bool    $only_array_types       TRUE = array only key, FALSE = array key|name
     * @param   bool    $exclude_menus_attachment_revision      TRUE = return array normal object without 'attachment',
     *                                                          'revision', 'nav_menu_item'
     * @param   array   $exclude_other          add in array the element that not show in post_type, ei: movie
     * @return  array   $array_post_types
     */
    function get_custom_type_post( $args = [] ){

        // ─── Defaults Vs Custom ────────
        $args_defaults = [
            'only_array_types'                  => false,
            'exclude_menus_attachment_revision' => true,
            'exclude_other'                     => []
        ];
        $args_match = pf_wp_parse_args( $args, $args_defaults );

        // ─── Key to vars ────────
        extract( $args_match );

        $array_post_types = array();
        $post_types       = get_post_types( array(), "objects" );

        if( $post_types ){

            $exclude = array();
            if( TRUE === $exclude_menus_attachment_revision ){

                $exclude = array( 'attachment', 'revision', 'nav_menu_item' );

            }elseif( is_array($exclude_other) && $exclude_other ){

                $exclude = $exclude_other;

            }

            foreach ($post_types as $post_type_key => $post_type_value) {

                if( TRUE === in_array( $post_type_value->name, $exclude ) ) continue;

                if($only_array_types == true){

                    $array_post_types[] = $post_type_value->name;

                }else{

                    $array_post_types[$post_type_value->name] = $post_type_value->labels->name;

                }
            }
        }

        return $array_post_types;

    }

    /**
	*	Tree taxonomie
	*
	* 	@todo 	Sort list of custom posts to get view like
	*			a tree of posts under categories and their
	*			children's categories (taxonomies)
	*
	*	@link 	wordpress.stackexchange.com/questions/80193/how-to-sort-list-of-custom-posts-to-get-view-like-a-tree-of-posts-under-categori#answer-182269
	*
	*/
	private function get_taxonomies_tree( $args ){

        // ─── Defaults Vs Custom ────────
        $args_default = [
            'TermName'     => '',
            'termID'       => 0,
            'separator'    => '',
            'parent_shown' => true,
            'value'        => [],
        ];
        $args_match = pf_wp_parse_args( $args, $args_default );
        //var_dump( $args_match );exit;

        // ─── Key to vars ────────
        extract( $args_match );

		$query_tax       = 'hierarchical=1&taxonomy='.$TermName.'&hide_empty=0&orderby=id&parent=';
		$name_field      = $this->field_name(); //$this->unique. '_' .$this->field['id'] ;
        $output          = '';

		if ($parent_shown) {
			$term         = get_term( $termID , $TermName );

			$output       .=  "<div class='pf-taxonomy-wrap-group'>$separator<label id='{$term->slug}_{$term->term_id}' class='label_ck' >";
			$output       .=  "<input type='checkbox' name='{$term->slug}_{$term->term_id}' ";
			$output       .=  "id='{$term->slug}_{$term->term_id}' />$term->name</label></div>";

			$parent_shown = false;
		}
		$terms = get_terms( $TermName, $query_tax . $termID );
		$separator .= '<div class="pf_taxonomies_separate"></div>';
		$separator_wrap = "<section class='pf_taxonomies_separate_wrap'>$separator</section>";
		if( count($terms) > 0 ){

			$current = 0;
			foreach ($terms as $term) {
				$current++;
				$checked =  in_array( $term->slug , (array)$value ) ? "checked": "";
				$output .=  "<div class='pf-taxonomy-wrap-group'>{$separator_wrap}";
				//$output .=  "<input type='checkbox' name='{$name_field}[]' value='$term->term_id' {$checked} ";
                //$output .=  "id='{$name_field}_{$term->slug}_{$term->term_id}' /><label class='label_ck' for='{$name_field}_{$term->slug}_{$term->term_id}' >$term->name</label>";
                $output .=  $this->taxonomies_check_template( $name_field, $checked, $term, $TermName );
                 //$output .=  "<span for='{$name_field}_{$term->slug}_{$term->term_id}' class='pf_taxonomies_label'>$term->name</span></div>";
                $output .= "</div>";
				$output .=  $this->get_taxonomies_tree([
                    'TermName'     => $TermName,
                    'termID'       => $term->term_id,
                    'separator'    => $separator,
                    'parent_shown' => $parent_shown,
                    'value'        => $value
                ]);
            }

		}

		return $output;
    }

    private function taxonomies_check_template( $name_field, $checked, $term, $tax_name ){
        $output =  "<input type='checkbox' name='{$name_field}[$tax_name][]' value='$term->slug' {$checked} ";
        $output .=  "id='{$name_field}_{$term->slug}_{$term->term_id}' /><label class='label_ck' for='{$name_field}_{$term->slug}_{$term->term_id}' >$term->name</label>";

        return apply_filters( 'pf_taxonomies_item_template', $output, $name_field, $checked, $term );
    }

    private function get_all_post_type_object( $data ){

        $names = [];
        if( ! empty( $data ) && ! empty( $data->object_type ) ){

            foreach ($data->object_type as $key => $value) {
                $cpt_data = get_post_type_object( $value );
                $names[] = $cpt_data->labels->name;
            }

        }

        return implode( ",", $names );

    }

}
}