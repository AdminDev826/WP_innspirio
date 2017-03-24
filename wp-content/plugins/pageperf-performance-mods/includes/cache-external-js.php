<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function pageperf_strpos_arr($haystack, $needle) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $what) {
        if(($pos = strpos($haystack, $what))!==false) return $pos;
    }
    return false;
}

add_filter('autoptimize_cache_external_js', function( $url ){

  if (! has_filter('autoptimize_cache_external_js_patterns') )
  {
    return false;
  }

  $patterns =
    apply_filters('autoptimize_cache_external_js_patterns', '');

  if ( pageperf_strpos_arr( $url , $patterns) === false )
  {
    return false;
  }

  if ( has_filter('autoptimize_cache_external_js_ttl') )
  {
    $ttl = (int) apply_filters(
      'autoptimize_cache_external_js_ttl', '');
  }
  else
  {
    $ttl = 7 * 24 * 60 * 60;
  }

  if ( preg_match('#^//#', $url) > 0 )
  {
    $url = 'https:' . $url;
  }

  $key = 'pageperf_external_js_'  . substr(md5( $url ), 9, 5);

  $js = get_transient( $key );

  if ( $js == false )
  {
    $resp = wp_remote_get( $url );
    
    if ( is_array( $resp) && 
         is_array($resp['headers']))
    {
      $js = $resp['body'];

      if ( strlen( $js ) > 0 )
      {
        set_transient( $key, $js, $ttl  );
      }
    }
  }

  if ( $js && strlen ( $js ) > 0 )
  {
    return $js;
  }

  return false;

});
