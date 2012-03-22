<?php

namespace Renderlib\Outputters;

class Txt implements Outputter {
  
  public function __construct() {
       
  }
  
 // --------------------------------------------------------------
  
 public function get_mime_types() {
    
    return array(
      'text/plain',
      'text/x-markdown' /* not official, but seems to have some support */
    );
    
  }

  // --------------------------------------------------------------
     
  public function set_option($opt_name, $opt_value) {
    
  }
  
  // --------------------------------------------------------------
   
  public function render_output(Renderlib\Content_item $content_item) {

  }
   
  // --------------------------------------------------------------
 
  public function render_404_output() {
    
  }
  
  // --------------------------------------------------------------
 
  public function render_error_output($error, $msg = NULL) {
    
  }
}

/* EOF: Html.php */