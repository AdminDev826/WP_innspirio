<?php

add_action( 'wp_loaded', function () {
  global $shortcode_tags;
  $sc_to_cache = NULL;

  if (has_filter('pageperf_cache_shortcodes'))
  {
    $sc_to_cache = apply_filters('pageperf_cache_shortcodes',
      array());
  }

  if ( empty( $sc_to_cache ) )
  {
    return;
  }

  foreach ( $shortcode_tags as $tag => $function ) {

    if ( ! in_array( $tag, $sc_to_cache ) )
    {
      continue;
    }
    $a = $tag;
    $b = $function;

    $shortcode_tags[$tag] = 
      function ( $attr, $content = null ) use ( $tag, $function ) 
      {
        $expires = 60 * 60 * 24 * 7 ;
        $expires = apply_filters( 
          'shortcode_cache_expires', $expires, $tag );

        $cache_key = $tag . md5( serialize( $attr ) ) . get_the_ID();
        $cached = get_transient( $cache_key );

        if ( $cached === false ) {

          $cached = call_user_func( $function, $attr, $content );
          set_transient( $cache_key, $cached, $expires );
        }
        return $cached;
    };
  }
});
