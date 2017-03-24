<?php
require_once('final-html.php');
require_once('classes/pageperf-image-html-replace.php');

add_action('pageperf_replace_images', 
  function( $html, $imagesToResize ){

    $a = $imagesToResize;

    if ( $imagesToResize && 
      is_array( $imagesToResize ) && 
      !empty( $imagesToResize ))
    {
      $resizer = new Pageperf_Image_Html_Replace( $html );
      $resizer->setImages( $imagesToResize );
      $resizer->replaceImages();

      $html =  $resizer->getHtml();
    }

    add_filter('pageperf_resize_images_final_html', 
      function() use ( $html ){
        return $html;
      });

}, 10, 2);

add_filter('final_html', function( $html ){

  $regexps = apply_filters('pageperf_resize_images_rules', array());

  $imagesToResize = array();

  foreach ( $regexps as $reg )
  {
    preg_match_all( $reg['regexp'], $html, $matches );

    if ( is_array( $matches[1] ) )
    {
      foreach ($matches[1] as $image )
      {
        $imagesToResize[] = array(
          'image' => $image,
          'width' => $reg['width']
        );
      }

    }
  }

  do_action('pageperf_replace_images', $html, $imagesToResize);
  $html = apply_filters('pageperf_resize_images_final_html', '');

  return $html;

});

add_filter('query_vars', function( $vars){
    $vars[] = Pageperf_Image_Html_Replace::URL_RESIZER_PARAM;
    return $vars;
});

add_action('parse_request', function( $wp){
  $urlParam = Pageperf_Image_Html_Replace::URL_RESIZER_PARAM;
  if (array_key_exists($urlParam, $_GET)){
    $resizer = new Pageperf_Image_Html_Replace();

    $params = unserialize( base64_decode( $_GET[$urlParam] ) );

    if ( !$params )
    {
      $resizer->echoEmptyGif();
    }

    $resizer->echoResizedImage( $params );
    die();

  }
});
