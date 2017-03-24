<?php

function pageperf_inline_script_move_to_headtop(
  $tag, $content) {

  $patterns = apply_filters(
    'autoptimize_inline_script_move_to_headtop_patterns', '');

  if ( empty($patterns) ) 
  {
    return $content;
  }

  foreach( $patterns as $pattern )
  {
    if ( strpos($tag, $pattern) !== false )
    {
      $content = str_replace($tag, '', $content);

      $content = str_replace('<head>', 
        sprintf("<head>\n%s\n", $tag ), 
        $content);

      break;
    }
  }

  return $content;

};
add_filter(
  'autoptimize_inline_script_move_to_headtop', 
  'pageperf_inline_script_move_to_headtop', 10, 2);
