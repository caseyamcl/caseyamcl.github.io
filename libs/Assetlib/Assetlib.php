<?php

namespace Assetlib;

class InvalidURLMappingException extends \RuntimeException { /* ... */ }
class UnmappedMimeTypeException extends \RuntimeException { /* ... */ }

class Assetlib {
  
  /**
   * Path Mappings ([url] => filepath)
   * @var array
   */
  private $path_mappings = array();
  
	// --------------------------------------------------------------	
 
  /**
   * MIME Type File Extension Mappings
   * @var array
   */
  private $mime_types = array(
    'bmp'  => 'image/bmp',
    'gif'  => 'image/gif',
    'jpeg' => 'image/jpeg',
    'jpg'  => 'image/jpeg',
    'jpe'  => 'image/jpeg',
    'png'  => 'image/png',
    'css'  => 'text/css',
    'less' => 'text/css',
    'js'   => 'application/x-javascript',
    'ico'  => 'image/x-icon',
    'eot'  => 'application/vnd.ms-fontobject',
    'svg'  => 'image/svg',
    'ttf'  => 'application/octet-stream',
    'otf'  => 'application/octet-stream',
    'woff' => 'application/x-font-woff',
  );
  
	// --------------------------------------------------------------	
 
  /**
   * Constructor
   * 
   * @param array $path_mappings 
   */
  public function __construct($path_mappings = array()) {
    
    foreach($path_mappings as $url_path => $filepath) {
      $this->define_url_mapping($url_path, $filepath);
    }
    
  }

  // --------------------------------------------------------------	

  /**
   * Define a URL mapping
   *  
   * @param string $url_path
   * @param string $filepath
   * @throws \RuntimeException 
   */
  public function define_url_mapping($url_path, $filepath) {
    
    //Check for errors
    if ( ! is_readable($filepath)) {
      throw new InvalidURLMappingException("The filepath $filepath is unreadable!");
    }
    if ( ! is_dir($filepath)) {
      throw new InvalidURLMappingException("The filepath $filepath must be a directory!");
    }
    
    //Add the mapping
    $this->path_mappings[$url_path] = realpath($filepath) . DIRECTORY_SEPARATOR;
    
    //Sort the array by key strlen descending
    uksort($this->path_mappings, function($a, $b) {
      if (strlen($a) == strlen($b))
        return 0;
      else
        return (strlen($a) > strlen($b)) ? -1 : 1;
    });
  }
  
	// --------------------------------------------------------------	

  /**
   * 
   * @param string $url_path
   * @return string|boolean  The full filepath, or FALSE if a mime mapping
   * doesn't exist
   */
  public function get_asset_mime($url_path) {
    
    $ext = pathinfo($url_path, PATHINFO_EXTENSION);    
    return ($ext && isset($this->mime_types[$ext])) ? $this->mime_types[$ext] : FALSE;    
  }
  
	// --------------------------------------------------------------	
  
  /**
   * Return the filepath for an asset URL
   * 
   * @param string $url_path 
   * @return string|boolean  The full filepath, or FALSE if it doesn't exist
   */
  public function get_asset_filepath($url_path) {
    
    if ( ! $this->get_asset_mime($url_path))
      throw new UnmappedMimeTypeException("There is no MIME type associated with that filetype in the Assetlib!");

    //Assume the file doesn't exist until we find it
    $filepath = FALSE;
    
    //Go through the url mappings, and find an existent file
    foreach($this->path_mappings as $url => $fp) {
      
      if (strlen($url_path) >= strlen($url) && substr($url_path, 0, strlen($url)) == $url) {
        
        $filename_portion = trim(substr($url_path, strlen($url)), DIRECTORY_SEPARATOR);
        $filepath = $fp . $filename_portion; 
        break;
      }
    }
    
    //Return the filepath
    return ($filepath && is_readable($filepath)) ? $filepath : FALSE;
  }
  
}

/* EOF: Assetlib.php */