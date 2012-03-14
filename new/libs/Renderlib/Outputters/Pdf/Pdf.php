<?php

namespace Renderlib\Outputters;

class Pdf extends Html implements Outputter {
  
  public function __construct() {
    
    parent::construct();
    
  }
  
 // --------------------------------------------------------------
  
 public function get_mime_types() {
    
    return array(
      'application/pdf'
    );
    
  }
  
  // --------------------------------------------------------------
 
  public function render_main_content(Renderlib\Content_item $content_item) {
    
    $content = parent::render_main_content();
  }
  
  // --------------------------------------------------------------
 
  public function render_output(Renderlib\Content_item $content_item) {
    
    $content = parent::render_output();
  }
  
  // --------------------------------------------------------------
 
  public function get_404_output() {
    
    $content = parent::get_404_output();
  }
  
  // --------------------------------------------------------------
 
  public function get_500_output($msg = NULL) {
    
    $content = parent::get_500_output();
  }
}

/* EOF: Html.php */