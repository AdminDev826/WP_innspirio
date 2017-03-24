<?php
add_filter('autoptimize_cache_external_js_patterns',
  function(){
    return array(
      'maps.googleapis'
    );
},10,1);
