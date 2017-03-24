<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once('final-html.php');

add_filter('final_html', function( $output ){

  $cssfile = 'global.css';

  $global = file_get_contents(
    plugin_dir_path( __FILE__ ) . '../' . $cssfile
  );

  if ( $global !== false || strlen( $global ) != 0 )
  {
    $output = str_replace('</head>', 
      sprintf("\n<style>%s</style></head>", $global),
      $output
    );
  }

  return $output;

});
