<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

remove_filter('safecss_embed_style', 
    array( 'Jetpack_Custom_CSS', 
    'should_we_inline_custom_css' ), 10, 2 );

add_filter('safecss_embed_style', function(){ return true; } );

add_action( 'wp_enqueue_scripts', function(){
  wp_dequeue_script( 'devicepx' );
}, 20 );
