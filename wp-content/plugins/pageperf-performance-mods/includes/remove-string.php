<?php

require_once('final-html.php');

add_filter('final_html', function( $html ){

  if ( has_filter('pageperf_remove_string') )
  {
    $remove = apply_filters('pageperf_remove_string', array());

    if ( ! empty( $remove ) )
    {
      $html = str_replace( $remove, '', $html );
    }
  }

  return $html;
});
