<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter('autoptimize_filter_js_exclude', function($exclude){
  return $exclude . ',bindReadyQ';
});

add_filter('autoptimize_js_after_no_minify', function( $code ){
    $jqueryBottom = file_get_contents(
      plugin_dir_path( __FILE__ ) . '../js/jquery-bottom.js'  
    );

    if ( strlen( $jqueryBottom )  > 0 )
    {
      return $code . " \n " . $jqueryBottom;
    }

    return $code;
});

add_filter('autoptimize_filter_html_before_minify',
  function($output) {

    $jqueryTop = file_get_contents(
      plugin_dir_path( __FILE__ ) . '../js/jquery-top.js'  
    ) . ";\n";

    $jqueryTop .= file_get_contents(
      plugin_dir_path( __FILE__ ) . '../js/l.min.js'  
    ) . ";\n";

    $tpl = <<<EOT
<head><script type="text/javascript">\n%s\n</script>\n
EOT;
    $headreplace = sprintf(
      $tpl,
      $jqueryTop);

    if ( strlen ( $jqueryTop ) > 0)
    {
      $output = str_replace('<head>', $headreplace, $output);
    }

    return $output;

}, 10, 1);

add_filter('autoptimize_js_inline_script', 
  function( $tag ){

    if ( 
      (preg_match('/document\ *\)\.ready/i', $tag)  > 0)  ||  
      (preg_match('#document\)\.on\(\'?"?ready#i', $tag)  > 0 )
    )
    {
      return $tag;
    }

    if ( apply_filters('autoptimize_js_wrap_in_docready_skip',
      $tag) )
    {
      return $tag;
    }

    $wraps = array(
      'jQuery',
      '$(',
      'OptinMonster'
    );

    $wraps = apply_filters('autoptimize_js_wrap_in_docready', 
      $wraps);

    $orig = $tag;

    foreach ( $wraps as $wrap )
    {
      if ( strpos( $tag, $wrap )          !== false    &&
        strpos( $tag, 'w.bindReadyQ' ) === false  
      )
      {
        //DavesWordPressLiveSearchConfig
        if ( strpos($tag, 'var DavesWordPressLiveSearchConfig =')  !== false )
        {
          continue;
        }
        $tag = preg_replace('/<script[^>]*>/i', 
          "<script type=\"text/javascript\">\n$(document).ready(function(){", $tag);

        $tag = str_replace('</script>', "});\n</script>", $tag);

        //gravity forms
        $tag = str_replace("if(typeof gf_global == 'undefined') var gf_global =",
          "if(typeof window.gf_global == 'undefined')  window.gf_global =", $tag);

      }


      if ( strpos($tag, 'tpj=jQuery') !== false )
      {
        $tag = str_replace('var tpj=jQuery;', '', $tag);
        $tag = str_replace('tpj.noConflict();', '', $tag);
        $tag = str_replace('tpj', 'jQuery', $tag);

      }

      if ( $tag != $orig )
      {
        //changed, do not wrap twice
        return $tag;
      }
    }

    return $tag;

});
