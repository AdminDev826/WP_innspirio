<?php

function pageperf_final_html_callback($buffer) {      
    $buffer = apply_filters('final_html', $buffer);
    return $buffer; 
}

function buffer_start() { 
  ob_start("pageperf_final_html_callback"); 
} 

function buffer_end() { ob_end_flush(); }

add_action('after_setup_theme', 'buffer_start');
add_action('shutdown', 'buffer_end');
?>
