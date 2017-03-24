<?php

add_filter('autoptimize_filter_html_before_minify', 
function( $html ){

  $all_css = '';
  $regexp = '/<link.*href=(.*fonts\.googleapis\.com[^\ ]*)[^>]*>/';


  function transient_key( $url ){
    return 'pageperf-fonts-browsers'.md5($url);
  };


  function retreive_font( $url )
  {
    $ieUserAgent = 'Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; GTB7.4; InfoPath.2; SV1; .NET CLR 3.3.69573; WOW64; en-US)';
    $key = transient_key( $url );
    $css = get_transient( $key );
    $ttl = 30 * 24 * 60 * 60;

    if ( $css == false )
    {
      $resp = wp_remote_get( $url );

      if ( is_array( $resp) && 
        is_array($resp['headers']))
      {
        $css = $resp['body'];

        if ( strlen( $css ) > 0 )
        {
          $respIe = wp_remote_get( $url, array(
            'user-agent' => $ieUserAgent
          ));

          if ( is_array( $respIe) && 
               is_array( $respIe['headers']))
          {
            $cssIe = $respIe['body'];
            if ( strlen( $cssIe ) > 0 )
            {
              $css = $css . "\n" . $cssIe;
            }
          }

          set_transient( $key, $css, $ttl  );
        }
      }
    }

    return $css;
  }

  if ( preg_match_all($regexp, $html, $matches) > 0)
  {
    $tags = array_map(function($elem){
      return trim($elem, "\"'");
    },$matches[ 0 ]);

    $hrefs = array_map(function($elem){
      return trim($elem, "\"'");
    },$matches[ 1 ]);

    foreach ($tags as $num => $tag)
    {
      if (strlen( $tag ) > 0)
      {
        $font_css = retreive_font ( $hrefs[ $num ] );

        $all_css .= $font_css . "\n";
        $html = str_replace( $tag, '', $html );
      }
    }
    $all_css = trim ( $all_css );

    if (strlen( $all_css )  > 0)
    {
      $html = str_replace('</head>', 
        sprintf('<style data-pp-googlefonts>%s</style></head>',
        $all_css),
        $html
      );
    }
  }

  return $html;
});
