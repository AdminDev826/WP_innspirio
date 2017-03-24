<?
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Pageperf_Minify_Abstract
{
  protected $_command;

  public function getCommand() {
    return '';
  }

  public function ifCommandExists()
  {
    $binary = strtok( $this->getCommand(), ' ' );
    if (! file_exists($binary) )
    {
      return false;
    }

    return true;
  }

  public function action($source){

    $minified = $this->_cmd( $source );

    if (strlen( $minified ) == 0 )
    {
      $minified = $source;
    }

    return $minified;
  }

  protected function _cmd( $source )
  {
    if ( ! $this->ifCommandExists() )
    {
      return NULL;
    }

    $tmpHandle = tmpfile();
    $handle = stream_get_meta_data($tmpHandle);
    $tmpFileName = $handle['uri'];

    fwrite ($tmpHandle, $source, strlen($source) );

    $fullCommand = sprintf( $this->getCommand(), $tmpFileName );

    exec($fullCommand, $minified, $returnCode);

    fclose( $tmpHandle );

    if ( sizeof($minified) == 0  || $returnCode != 0)
    {
      return $source;
    }

    return join("\n", $minified);
  }

}
