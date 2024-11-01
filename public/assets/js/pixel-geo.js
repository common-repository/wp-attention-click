// Plugin Cookie: A simple, lightweight JavaScript API for handling browser cookies
// https://github.com/js-cookie/js-cookie
!function(e){var n;if("function"==typeof define&&define.amd&&(define(e),n=!0),"object"==typeof exports&&(module.exports=e(),n=!0),!n){var t=window.Cookies,o=window.Cookies=e();o.noConflict=function(){return window.Cookies=t,o}}}(function(){function e(){for(var e=0,n={};e<arguments.length;e++){var t=arguments[e];for(var o in t)n[o]=t[o]}return n}function n(e){return e.replace(/(%[0-9A-Z]{2})+/g,decodeURIComponent)}return function t(o){function r(){}function i(n,t,i){if("undefined"!=typeof document){"number"==typeof(i=e({path:"/"},r.defaults,i)).expires&&(i.expires=new Date(1*new Date+864e5*i.expires)),i.expires=i.expires?i.expires.toUTCString():"";try{var c=JSON.stringify(t);/^[\{\[]/.test(c)&&(t=c)}catch(e){}t=o.write?o.write(t,n):encodeURIComponent(String(t)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),n=encodeURIComponent(String(n)).replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent).replace(/[\(\)]/g,escape);var f="";for(var u in i)i[u]&&(f+="; "+u,!0!==i[u]&&(f+="="+i[u].split(";")[0]));return document.cookie=n+"="+t+f}}function c(e,t){if("undefined"!=typeof document){for(var r={},i=document.cookie?document.cookie.split("; "):[],c=0;c<i.length;c++){var f=i[c].split("="),u=f.slice(1).join("=");t||'"'!==u.charAt(0)||(u=u.slice(1,-1));try{var a=n(f[0]);if(u=(o.read||o)(u,a)||n(u),t)try{u=JSON.parse(u)}catch(e){}if(r[a]=u,e===a)break}catch(e){}}return e?r[e]:r}}return r.set=i,r.get=function(e){return c(e,!1)},r.getJSON=function(e){return c(e,!0)},r.remove=function(n,t){i(n,"",e(t,{expires:-1}))},r.defaults={},r.withConverter=t,r}(function(){})});

/*
  Complete library for the management of GEO and
  Devices to save in the database.
 */
// FIXED: Add more documentation of the database
var pf_geo = function(){

  // --- Helpers for the end ---------------
  this.helpers = {
    // Get a cookie
    get_cookie: function( name ) {
      return Cookies.get( name );
    }.bind(this),

    // Set cookie
    set_cookie: function( name, value, expires, path = '', domain, secure ) {
      Cookies.set(name, value, { expires: expires });
    }.bind(this),

    // Remove a cookie
    remove_cookie: function( name, path, domain, secure ) {
      Cookies.remove( name );
    }
  }



  // --- Functions ---------------
  this.funcs = {
    getBrowserInfo : {
      options: [],
      header: [navigator.platform, navigator.userAgent, navigator.appVersion, navigator.vendor, window.opera],
      dataos: [
        { name: 'Windows Phone', value: 'Windows Phone', version: 'OS' },
        { name: 'Windows', value: 'Win', version: 'NT' },
        { name: 'iPhone', value: 'iPhone', version: 'OS' },
        { name: 'iPad', value: 'iPad', version: 'OS' },
        { name: 'Kindle', value: 'Silk', version: 'Silk' },
        { name: 'Android', value: 'Android', version: 'Android' },
        { name: 'PlayBook', value: 'PlayBook', version: 'OS' },
        { name: 'BlackBerry', value: 'BlackBerry', version: '/' },
        { name: 'Macintosh', value: 'Mac', version: 'OS X' },
        { name: 'Linux', value: 'Linux', version: 'rv' },
        { name: 'Palm', value: 'Palm', version: 'PalmOS' }
      ],
      databrowser: [
        { name: 'Chrome', value: 'Chrome', version: 'Chrome' },
        { name: 'Firefox', value: 'Firefox', version: 'Firefox' },
        { name: 'Safari', value: 'Safari', version: 'Version' },
        { name: 'Internet Explorer', value: 'MSIE', version: 'MSIE' },
        { name: 'Opera', value: 'Opera', version: 'Opera' },
        { name: 'BlackBerry', value: 'CLDC', version: 'CLDC' },
        { name: 'Mozilla', value: 'Mozilla', version: 'Mozilla' }
      ],
      init: function () {
        var agent = this.header.join(' '),
          os = this.matchItem(agent, this.dataos),
          browser = this.matchItem(agent, this.databrowser);

        return { os: os, browser: browser };
      },
      matchItem: function (string, data) {
        var i = 0,
          j = 0,
          html = '',
          regex,
          regexv,
          match,
          matches,
          version;

        for (i = 0; i < data.length; i += 1) {
          regex = new RegExp(data[i].value, 'i');
          match = regex.test(string);
          if (match) {
            regexv = new RegExp(data[i].version + '[- /:;]([\\d._]+)', 'i');
            matches = string.match(regexv);
            version = '';
            if (matches) { if (matches[1]) { matches = matches[1]; } }
            if (matches) {
              matches = matches.split(/[._]+/);
              for (j = 0; j < matches.length; j += 1) {
                if (j === 0) {
                  version += matches[j] + '.';
                } else {
                  version += matches[j];
                }
              }
            } else {
              version = '0';
            }
            return {
              name: data[i].name,
              version: parseFloat(version)
            };
          }
        }
        return { name: 'unknown', version: 0 };
      },
      printInfo: function () {
        var e = this.init();
        var a = 'os.name=' + e.os.name + '|' +
        'os.version=' + e.os.version + '|' +
        'browser.name=' + e.browser.name + '|' +
        'browser.version=' + e.browser.version + '|' +

        'navigator.userAgent=' + navigator.userAgent + '|' +
        'navigator.appVersion=' + navigator.appVersion + '|' +
        'navigator.platform=' + navigator.platform + '|' +
        'navigator.vendor=' + navigator.vendor + '|';
        //console.log( a );
        return a;
      }
    },

    device_detector : function() {
      var b = navigator.userAgent.toLowerCase(),
          a = function(a) {
            void 0 !== a && (b = a.toLowerCase());
            return /(ipad|tablet|(android(?!.*mobile))|(windows(?!.*phone)(.*touch))|kindle|playbook|silk|(puffin(?!.*(IP|AP|WP))))/.test(b) ? "tablet" : /(mobi|ipod|phone|blackberry|opera mini|fennec|minimo|symbian|psp|nintendo ds|archos|skyfire|puffin|blazer|bolt|gobrowser|iris|maemo|semc|teashark|uzard)/.test(b) ? "phone" : "desktop"
          };
      return {
        device: a(),
        detect: a,
        isMobile: "desktop" != a() ? !0 : !1,
        userAgent: b
      }
    }.bind(this),

    // Get country code
    get_country_code : function ( name_cookie_current_country = "wp_country" ){
      var prefix = name_cookie_current_country;
      var code   = this.helpers.get_cookie( prefix );
      if( ! code || code === undefined ){
        var helpers = this.helpers; // ← Because within success 'this' does not work anymore
        jQuery.ajax({
          /*url: "//gd.geobytes.com/GetCityDetails?callback=?",*/
          /*url: "//ip-api.com/json",*/
          /*url: "//freegeoip.app/json/",*/
          /*url: "//extreme-ip-lookup.com/",*/
          url: "https://json.geoiplookup.io",
          dataType: "json",
          success: function(e){
            helpers.set_cookie( prefix, e.country_code, 86400 * 30 );
            return e.country_code;
          }, error: function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("Status: " + textStatus); console.log("Error: " + errorThrown); 
            console.log( XMLHttpRequest );
          }
        });
      }
      return code;
    }.bind(this),

    country_validate : function( string_code_countries = '' ) {
      if( ! string_code_countries ) return true;
      if( ! ( string_code_countries.includes('all') ) ){
        var current_country_code =  this.get_country_code();
        if( current_country_code && string_code_countries.includes( current_country_code ) ){
          return true;
        }
      }else if( string_code_countries.includes('all') ) {
        return true;
      }
      return false;
    }
  }




  // --- Validators ---------------
  // Get the current device type
  this.validator = {
    get_device : function(){
      var r = this.funcs.device_detector();
      if( r.device == 'desktop' ) return 'd';
      else if( r.device == 'tablet' ) return 't';
      else return 'm';
    }.bind(this)
  }



  // --- Actions ---------------
  this.actions = {

    save_geo : function( data = {} ){
      // Defaults
      data.nonce    = data.nonce || '';
      data.action   = data.action || '';
      data.post_id  = data.post_id || '';
      data.url      = data.url || '';
      data.where_is = data.where_is || '';
      data.ajaxurl  = data.ajaxurl || '';
      data.others   = data.others || {};

      var browser_info = this.funcs.getBrowserInfo.printInfo();
      var device       = this.validator.get_device();
      //console.log( device, browser_info );
      jQuery.ajax({
        type:'POST',
        url: data.ajaxurl,
        data: {
          action         : data.action,
          post_id        : parseInt(data.post_id),
          url            : data.url,
          where_is       : data.where_is,
          browser_details: browser_info,
          device         : device,
          nonce          : data.nonce,
          others         : data.others, // Here you place all custom data to send to process
        },
        success: function (result) {
          console.log(result + "-");
        },
        error: function (xhr, status) {
          console.log("Sorry, there was a problem! ", xhr, status);
        },
        complete: function (xhr, status) {
          null;
        }
      });
    }.bind(this),

    save_others : function( data = {} ){
      // Defaults
      data.nonce    = data.nonce || '';
      data.action   = data.action || '';
      data.post_id  = data.post_id || '';
      data.ajaxurl  = data.ajaxurl || '';
      data.others   = data.others || {};

      jQuery.ajax({
        type:'POST',
        url: data.ajaxurl,
        data: {
          action         : data.action,
          post_id        : parseInt(data.post_id),
          nonce          : data.nonce,
          others         : data.others, // Here you place all custom data to send to process
        },
        success: function (result) {
          console.log(result);
        },
        error: function (xhr, status) {
          console.log("Sorry, there was a problem! ", xhr, status);
        },
        complete: function (xhr, status) {
          null;
        }
      });
    }.bind(this)
  }


}