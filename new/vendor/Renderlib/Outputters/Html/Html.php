<?php

namespace Renderlib\Outputters;

class Html implements Outputter {
  
  private $template_dir;
  
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
  
  public function set_option($opt_name, $opt_value) {
    
    switch($opt_name) {
      case 'template_dir':
        $this->template_dir = $opt_value;
      break;
      default:
        throw new \InvalidArgumentException("Option name '$opt_name' does not exist!");
    }
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