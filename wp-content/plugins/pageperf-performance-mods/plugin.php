<?php
/*
* Plugin Name: Pageperf Performance Fixes 
* Plugin URI: http://pageperf.com
* Description: Custom performance fixes 
* descriptions. 
* Version: 1.0
* Author: Pageperf 
* Author URI: http://pageperf.com
* */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function pageperf_log( $str )
{
  file_put_contents("/tmp/pageperf.log", 
    $str . "\n\n----\n\n", FILE_APPEND);
}

require_once('includes/settings.php');
require_once('includes/cache-menu.php');
# //require_once('includes/cache-external-js.php');
# require_once('includes/jetpack-custom-css.php');
require_once('includes/css-defer.php');
require_once('includes/load-css.php');
require_once('includes/final-html.php');
#require_once('includes/google-fonts.php');
# //require_once('includes/move-to-top.php');
# require_once('includes/image-resizer.php');
require_once('includes/domready-js-inject.php');
# require_once('includes/remove-string.php');
require_once('includes/cache-shortcodes.php');
require_once('includes/cache-widgets.php');
require_once('includes/inline-css-global.php');


add_filter('pageperf_defer_css_pattern', function( $in ) {
  return $in;
});

add_filter('autoptimize_filter_js_removables', function($in){
    return $in;
});


add_filter('autoptimize_js_wrap_in_docready_skip', function( $tag ){

  return false;

});

add_filter('final_html', function( $html ){

  $html = str_replace(
    'http://inspirio.com/wp-content/uploads/2016/02/Inspirio-2.jpg', 
    'https://inspirio.com/wp-content/uploads/2016/10/bg.png',
    $html
  );

  return $html;
});

add_filter('final_html', function( $html ){

  $html = str_replace(
    'wp-content/uploads/2015/11/Image-with-centered-cropping-for-web.png', 
    'wp-content/uploads/2016/10/bg.png',
    $html
  );
  return $html;

});


add_filter('final_html', function( $html ){
  $search = "<link rel='stylesheet' id='ut-fontawesome-css'  href='//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css?ver=bda078d8d4b42ab0300d5204092fc440' type='text/css' media='all' />";

  $replace = "<link rel='preload'  href='//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css?ver=bda078d8d4b42ab0300d5204092fc440' onload=\"this.rel='stylesheet'\" as='style' media='all' />";

  return str_replace($search, $replace, $html);

});



add_filter('pageperf_defer_css_pattern', function( $in ){

  return array_merge($in, array(
  
    'fonts.googleapis.com',
    '.woff',
    'font-awesome.min.css',
    'ut-fontawesome-css'
  ));


});

add_filter('pageperf_cache_shortcodes', function( $caches ){

  return $caches;

});


add_filter('pageperf_cache_widgets', function( $tocache ){

  return $tocache;
});


remove_filter('template_redirect', 'redirect_canonical'); 



add_filter('autoptimize_filter_js_exclude', function($exclude){
  $more = array(
    'CherryFramework/js/jquery-1.7.2.min.js',
    'var job_manager_chosen_multiselect_args',
    'monarchSettings',
    'angelleye_frontend = {',
    'var ssbpEmail',
    'var wc_add_to_cart_params',
    'ga("send","pageview"',
    'var wc_cart_fragments_params',
    'var yith_wacp',
    'var woocommerce_params',
    'var ubermenu_data',
    'var accounting_params',
    'var loadCSS',
    'bindReadyQ',
    'var wc_cart_params',
    'var ajaxurl',
    'var colomatduration',
    'var htmlDiv',
    'var nectarLove',
    'var _wpcf7',
    'var lazyload_video_settings',
    'var ajax',
    'var cshero_row_object',
  );
  return $exclude . ',' . join(',', $more );
});
