<?php

namespace Renderlib\Outputters;

class Json implements Outputter {
  
  public function __construct() {
    
  }
  
 // --------------------------------------------------------------
  
 public function get_mime_types() {
    
    return array(
      'application/json'
    );
    
  }
  
  // --------------------------------------------------------------
 
  public function render_output(Renderlib\Content_item $content_item) {
    
  }
  
  // --------------------------------------------------------------
 
  public function render_404_output() {
   
    return json_encode(array('error' => 'Content not found', 'type' => '404'));
    
  }
  
  // --------------------------------------------------------------
 
  public function render_error_output($error, $msg = NULL) {
    
    return json_encode(
      array(
       'error' => 'Internal Server Error', 
       'type' => '500',
       'message' => ($msg ?: 'Unknown Error')
      ));
    
  }
}

/* EOF: Html.php */