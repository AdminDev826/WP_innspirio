<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('wp_footer', function(){
  echo '<!-- cssdefer -->';
});
