<?php

namespace Renderlib;

class Renderlib {
  
  private $uri;
  
  // --------------------------------------------------------------		
  
  public function __construct(Requesty\Uri $uri) {
    
    $this->uri = $uri;
    
  }
  
  // --------------------------------------------------------------		
  
  public function get_available_content_types($mimes_only = FALSE) {
    
  }
  
  // --------------------------------------------------------------		
  
  public function resolve_mime_to_outputter($mime) {
    
  }
  
  // --------------------------------------------------------------
}

/* EOF: Renderlib.php */