/*
|--------------------------------------------------------------------------
| WP Attention Click Scripts
|--------------------------------------------------------------------------
|
| Libraries and functions that allow the correct functioning.
| All based on the front-end.
|
*/
/**
 * @since 0.1 Release
 * @since 0.7 Add var 'geo' instance of {pf_geo}
 */
;(function( $, window, document, undefined ) {
  'use strict';

  // --- Constants ---------------
  var WPAC = WPAC || {};

  WPAC.vars = {
    onloaded : false,
    $window  : $(window),
    $document: $(document),
    geo      : new pf_geo(),

    name_cookie_for_bar  : 'wpac_bar',
    name_cookie_for_popup: 'wpac_popup',
  };

  // --- Functions declare ---------------
  WPAC.funcs = {};

  // --- Helpers ---------------
  WPAC.helper = {

    // uID user
    uid: function ( prefix ) {
      return ( prefix || '' ) + Math.random().toString(36).substr(2, 9);
    },

    // Quote regular expression characters
    preg_quote: function( str ) {
      return (str+'').replace(/(\[|\-|\])/g, "\\$1");
    },

    // Remove element
    removeEle : function( class_ele = 'wpac', time_remove = 5 ){
      $('.'+class_ele).removeClass('active');
      setTimeout(function(){
          $('.'+class_ele).remove();
      }, time_remove * 1000 );
    }

  };

  // --- Functions ---------------
  WPAC.funcs.isCookieActive = function( name_cookie = 'wpac' ){
    if( WPAC.vars.geo.helpers.get_cookie( name_cookie ) ) return true;
    return;
  };

  // Validate if the plugin is activated & test || Cookie active (bar,popup,content,etc..)
  WPAC.funcs.valida_show = function( countries, object, object_test, string_cookie ){

    var allow_countries = countries;
    var cookie_validate = string_cookie
    var $object_active  = object;
    var $object_test    = object_test;

    // --- Valida if country allow ---------------
    if( ! WPAC.vars.geo.funcs.country_validate( allow_countries ) ) return;

    // --- Valida if show per options and cookie active ---------------
    if( $object_active.val() == 1 && ( $object_test.val() == 1 && wpac_vars.is_logged == 1 ) ){
      return true;
    }else if(  $object_active.val() && ! $object_test.val() && ! WPAC.funcs.isCookieActive( cookie_validate ) ){
      return true;
    }else if( $object_active.val() && ! WPAC.funcs.isCookieActive( cookie_validate ) ){
      return true;
    }
    return;
  }

  WPAC.funcs.save_click = function( type = 'b' ){ // b=bar,p=popup
    WPAC.vars.geo.actions.save_geo({
      action  : 'wpac-save-click',
      post_id : wpac_vars.post_id,
      url     : wpac_vars.url,
      where_is: wpac_vars.where_is,
      ajaxurl : wpac_vars.ajaxurl,
      nonce   : $("#wpac_nonce").val(), //wpac_vars.nonce,
      others  : JSON.stringify({type: type}),
    });
  }

  /* WPAC.funcs.save_view = function( type = 'b' ){ // b=bar,p=popup,bc=bar close,pc=popup close
    WPAC.vars.geo.actions.save_geo({
      action  : 'wpac-save-view',
      post_id : wpac_vars.post_id,
      url     : wpac_vars.url,
      where_is: wpac_vars.where_is,
      ajaxurl : wpac_vars.ajaxurl,
      nonce   : $("#wpac_nonce").val(), //wpac_vars.nonce,
      others  : JSON.stringify({type: type}),
    });
  } */

  // Closed bar attention
  WPAC.funcs.close = function( class_remove, close_object, string_cookie, appears_again, type = 'bc' ){
    close_object.on('click', function(){
      WPAC.vars.geo.helpers.set_cookie( string_cookie, '1', appears_again );
      WPAC.helper.removeEle(class_remove);
      WPAC.funcs.save_click(type);
    });
  }


  // --- Show Bar ---------------
  $.fn.show_click_bar = function() {

    // Initial values
    var appears_again = parseInt($("#wpac_appear_again").val()) * 86400;

    // Active close
    WPAC.funcs.close(
      'wpac',
      $(".wpac_close"),
      WPAC.vars.name_cookie_for_bar,
      appears_again
    )

    // if active click content, then remove element
    if( parseInt( $("#wpac_click_bar").val() ) != 0 ){
      $(".wpac_center_a").on('click', function(){
        WPAC.vars.geo.helpers.set_cookie( WPAC.vars.name_cookie_for_bar, '1', appears_again );
        WPAC.helper.removeEle('wpac');
        WPAC.funcs.save_click();
      });
    }

    // Active click for button
    if( $(".wpac_button").length > 0 ){
      $(".wpac_button").on('click', function(){
        WPAC.vars.geo.helpers.set_cookie( WPAC.vars.name_cookie_for_bar, '1', appears_again );
        WPAC.helper.removeEle('wpac');
        WPAC.funcs.save_click();
      });
    }

    return this.each( function() {
      var if_show = WPAC.funcs.valida_show(
        $("#wpac_allow_country").val(),
        $("#wpac_activate"),
        $("#wpac_mode_test"),
        WPAC.vars.name_cookie_for_bar
      );
      if( ! if_show ) return;
      var $this = $(this);

      setTimeout(function(){
        $this.addClass('active');
        //WPAC.funcs.save_view();
      }, $("#wpac_time_appear").val() * 1000 );
    });

  };

  // --- Show Popup ---------------
  $.fn.show_click_popup = function() {

    // Initial values
    var appears_again = parseInt($("#wpac_popup_appear_again").val()) * 86400;

    // Active close
    WPAC.funcs.close(
      'wpac_popup',
      $(".wpac_popup_close"),
      WPAC.vars.name_cookie_for_popup,
      appears_again,
      'pc'
    )

    // if active click content or click button, then remove element
    /*if( parseInt( $("#wpac_popup_click").val() ) ){
      $(".wpac_popup_content").on('click', function(){
        WPAC.vars.geo.helpers.set_cookie( WPAC.vars.name_cookie_for_popup, '1', appears_again );
        WPAC.helper.removeEle('wpac_popup');
        WPAC.funcs.save_click('p');
      });
    }*/

    // Active click for button
    if( $(".wpac_popup_button").length > 0 ){
      $(".wpac_popup_button").on('click', function(){
        WPAC.vars.geo.helpers.set_cookie( WPAC.vars.name_cookie_for_popup, '1', appears_again );
        WPAC.helper.removeEle('wpac_popup');
        WPAC.funcs.save_click('p');
      });
    }

    return this.each( function() {
      var if_show = WPAC.funcs.valida_show(
        $("#wpac_popup_allow_country").val(),
        $("#wpac_popup_activate"),
        $("#wpac_popup_mode_test"),
        WPAC.vars.name_cookie_for_popup
      );
      if( ! if_show ) return;

      var $this = $(this);

      setTimeout(function(){
        $this.addClass('active');
        //WPAC.funcs.save_view('p');
      }, $("#wpac_popup_time_appear").val() * 1000 );
    });


  };


    // --- Document ready and run scripts ---------------
  $(document).ready( function() {
    $('.wpac').show_click_bar();
    $('.wpac_popup').show_click_popup();
  });

} )( jQuery, window, document );;