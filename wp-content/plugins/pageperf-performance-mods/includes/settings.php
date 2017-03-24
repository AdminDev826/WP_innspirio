<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter('autoptimize_js_do_minify',function(){
  return false;
},10,1);

add_filter('autoptimize_css_do_minify',function(){
  return false;
},10,1);

add_filter('autoptimize_filter_js_noptimize', function(){
  return false;
});

add_filter('autoptimize_filter_css_noptimize', function(){
  return false;
});

?>
