<?php

namespace Renderlib\Outputters;

class Html implements Outputter {
  
  public function __construct() {
    
  }
  
 // --------------------------------------------------------------
  
 public function get_mime_types() {
    
    return array(
      'text/html',
      'application/html+xml'
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
 
  public function get_500_output() {
    
  }
}

/* EOF: Html.php */