<?php

namespace Renderlib;

class InvalidRenderMimeTypeException extends \InvalidArgumentException { /* ... */ }

class Renderlib {
  
  private $content_types;
  private $outputters_dir;
  
  // --------------------------------------------------------------		
  
  /**
   * Constructor
   * 
   * @param array $cached_mime_types   Key is mime/type, value is outputter name
   */
  public function __construct($cached_mime_types = NULL) {

    $this->outputters_dir = __DIR__ . DIRECTORY_SEPARATOR . 'Outputters' . DIRECTORY_SEPARATOR;    
    
    if ($cached_mime_types)
      $this->content_types = $cached_mime_types;
    else
      $this->register_content_types();
  }
  
  // --------------------------------------------------------------		
  
  /**
   * Get available content types
   * 
   * @param boolean $mimes_only 
   */
  public function get_available_content_types($mimes_only = FALSE) {
    
    return ($mimes_only) ? array_keys($this->content_types) : $this->content_types;
    
  }
  
  // --------------------------------------------------------------		
  
  /**
   * Factory Method to return an outputter object based on the mime type
   * 
   * @param string $mime 
   * @return \ContentMapper\Content_item
   */
  public function get_outputter_from_mime_type($mime) {
    
    if ( ! isset($this->content_types[$mime]))
      throw new \InvalidRenderMimeTypeException("The mime type '$mime' is not available!");
    
    $filename = $this->outputters_dir . $this->content_types[$mime];
    $classname = "\\Renderlib\\Outputters\\" . $this->content_types[$mime];
        
    return new $classname;
  }
  
  // --------------------------------------------------------------		

  private function register_content_types() {
 
    //Get all of the outputters that are registered
    foreach(scandir($this->outputters_dir) as $file) {
            
      //Skip hiddens and Outputter.php
      if ($file{0} == '.' OR $file == 'Outputter.php')
        continue;
      
      //If it is an actual outputter
      $class_file = $this->outputters_dir . $file;
            
      if (is_readable($class_file)) {
        
        include_once($class_file);
        $classname = 'Renderlib\\Outputters\\' . basename($file, '.php');
        $obj = new $classname;
               
        foreach($obj->get_mime_types() as $mtype) {
          $this->content_types[$mtype] = basename($file, '.php');
        }        
      }
    }
    
    return count($this->content_types);
  }
  
}

/* EOF: Renderlib.php */