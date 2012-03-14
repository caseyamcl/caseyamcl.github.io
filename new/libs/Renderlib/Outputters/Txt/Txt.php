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
 
  public function render_main_content(Renderlib\Content_item $content_item) {
    
  }
  
  // --------------------------------------------------------------
 
  public function render_output(Renderlib\Content_item $content_item) {

  }
   
  // --------------------------------------------------------------
 
  public function get_404_output() {
    
  }
  
  // --------------------------------------------------------------
 
  public function get_500_output($msg = NULL) {
    
  }
}

/* EOF: Html.php */