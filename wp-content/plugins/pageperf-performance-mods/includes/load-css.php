<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter('autoptimize_filter_html_before_minify',
  function($output) {

    $loadcss = file_get_contents(
      plugin_dir_path( __FILE__ ) . '../js/loadCSS.min.js'  
    );

    $tpl = <<<EOT
<head><script type="text/javascript">%s</script>\n
EOT;
    $headreplace = sprintf(
      $tpl,
      $loadcss);

    if ( strlen( $loadcss ) > 0  )
    {
      $output = str_replace('<head>', $headreplace, $output);
    }

    return $output;
}, 10, 1);



global $wp_version;

if ( $wp_version >= 4.5 ) {

  add_filter('style_loader_tag', 
    function( $html, $handle, $href, $media ){

      $href = 1;
      $media = 1;

      $defer = "<link rel='preload'  href='%s' as=\"style\" onload=\"this.rel='stylesheet'\" media='%s' />\n";

      $patterns = apply_filters('pageperf_defer_css_pattern',
        array());

      if ( empty( $patterns ) )
      {
        return $html;
      }

      foreach( $patterns as $pattern )
      {
        if ( strpos( $href, $pattern ) !== false )
        {
          return sprintf( $defer, $href, $media );
        }
      }

      return $html;

    }, 10, 4);

} else {

  add_filter('style_loader_tag', 
    function( $html, $handle ){

      $patterns = apply_filters('pageperf_defer_css_pattern',
        array());

      if ( empty( $patterns ) )
      {
        return $html;
      }

      foreach( $patterns as $pattern )
      {
        if ( strpos( $html, $pattern ) !== false )
        {
          $html = preg_replace(
            "#['\"]stylesheet['\"]#",
            "\"preload\"", 
            $html, -1); 

          $html = preg_replace(
            "#type=['\"]text/css['\"]#", 
            "as=\"style\"", 
            $html, -1); 

          $html = preg_replace(
            "#<link#", 
            "<link onload=\"this.rel='stylesheet'\"", 
            $html);

          return $html;

        }
      }

      return $html;

    }, 10, 4);
}
