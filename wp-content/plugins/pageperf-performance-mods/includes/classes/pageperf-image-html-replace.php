<?php
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once('pageperf-image-cropper.php');
require_once('pageperf-image-cache.php');

class Pageperf_Image_Html_Replace {

  const URL_RESIZER_PARAM = 'pageperf-images-resizer';

  protected $_html;
  protected $_images = array();

  public function __construct( $html  = NULL)
  {
    $this->_html = $html;
  }

  public function echoEmptyGif()
  {
    header('Content-Type: image/gif');
    echo base64_decode(
      "R0lGODdhAQABAIAAAPxqbAAAACwAAAAAAQABAAACAkQBADs=");

    die();
  }

  public function setImages( $images )
  {
    $this->_images = $images;
  }

  public function isImageOK( $image )
  {
    $hasImage = array_key_exists('image', $image);
    $hasWidth = array_key_exists('width', $image);
    $hasHeight = array_key_exists('height', $image);
    $width = $image['width'];
    $height = $image['height'];

    if ( ! $hasImage )
    {
      return false;
    }

    if ( !is_numeric( $width ) && ! is_numeric( $height ) )
    {
      return false;
    }

    return true;
  }

  public function echoImage( $path )
  {
    header('Content-Type: image/jpeg');
    readfile ( $path );
    die();
  }

  public function echoResizedImage( $image )
  {
    if ( ! $this->isImageOK( $image ) )
    {
      $this->echoEmptyGif();
    }

    $path = $this->getFilesystemPath( $image );

    if ( $path )
    {
      $cache = new Pageperf_Image_Cache( $image );
      if ( ! $cache->hasCachedFile() )
      {
        $cropper = new Pageperf_Image_Cropper( $path );
        $cropper->resizeImage( $image['width'], 0);
        $cropper->saveImage( $cache->getCacheImageFullPath() );
      }

      $this->echoImage( $cache->getCacheImageFullPath() );
    }
  }

  public function getFilesystemPath( $image )
  {
    $siteUrl = site_url();
    $sitePath = get_home_path();

    $filePath = sprintf("%s%s", 
      $sitePath,
      str_replace($siteUrl, '', $image['image'])
    );

    $filePath = str_replace('//', '/', $filePath);

    return $filePath;

  }

  public function replaceImage( $search, $replace ) {

    $this->_html = str_replace( 
      sprintf('src="%s"', $search), 
      sprintf('src="%s"', $replace), 
      $this->_html );
  
    $this->_html = str_replace( 
      sprintf('src=%s', $search), 
      sprintf('src="%s"', $replace), 
      $this->_html );
  }

  public function replaceImages() {

    foreach ( $this->_images as $image )
    {
      if ( ! $this->isImageOK( $image ) ) continue;

      $uri = $this->getResizeUri( $image );

      if ( $uri ) 
      {
        $this->replaceImage( $image['image'], $uri );
      }
    }
  }

  public function getHtml()
  {
    return $this->_html;
  }

  public function getResizeUri( $image )
  {
    if ( ! is_array( $image ) )
    {
      return NULL;
    }

    if ( ! array_key_exists('image', $image) )
    {
      return NULL;
    }

    $script = 'index.php';

    $params = base64_encode(
      serialize(
        array(
          'image'  => $image ['image'],
          'width'  => $image ['width'],
          'height' => $image ['height']
    )));


    return sprintf("/%s?%s=%s", $script, self::URL_RESIZER_PARAM, $params);
  }

  public function resizeOneImage( $image )
  {
    $a = $this->getFilesystemPath( $image );
  }

  public function resizeImages()
  {
    if ( $this->_images )
    {
      foreach ( $this->_images as $image )
      {
        $this->resizeOneImage( $image );
      }
    }
  }
}

