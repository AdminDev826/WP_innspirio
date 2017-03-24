<?php

class Pageperf_Image_Cache {

  protected $_image = array();

  public function __construct( $image )
  {
    $this->_image = $image;
    $this->createCacheDir();
  }

  public function hasCachedFile()
  {
    return file_exists( $this->getCacheImageFullPath() );
  }

  public function getCacheKey()
  {
    return md5(serialize($this->_image));
  }

  public function getCachedName()
  {
    return $this->getCacheKey() . '.jpeg';
  }

  public function getCacheDir()
  {
    return WP_CONTENT_DIR . '/cache/pageperf/images';
  }

  public function getCacheImageFullPath()
  {
    return sprintf( "%s/%s", 
      $this->getCacheDir(), $this->getCachedName() );
  }

  public function createCacheDir()
  {
    if ( ! file_exists( $this->getCacheDir() ) )
    {
      mkdir( $this->getCacheDir(), 0777, true );
    }
  }

}
