<?php

function pageperf_menu_key( $args  ) {

  return sprintf('pageperf-menu-cache-%s', md5(
    serialize( $args )
  ));
}

add_filter('pre_wp_nav_menu', function( $output, $args ){

  $cached = get_transient( pageperf_menu_key( $args ) );

  if ( $cached )
  {
    return $cached;
  }
  return $output;

}, 10, 2);

add_filter('wp_nav_menu', function( $output, $args ){

  $key = pageperf_menu_key( $args );

  $cached = get_transient( $key );

  if ( ! $cached  )
  {
    set_transient( $key, $output, 24 * 60 * 60 );
  }

  return $output;


}, 10, 2);
