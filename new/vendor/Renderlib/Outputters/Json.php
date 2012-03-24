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
   
  public function set_option($opt_name, $opt_value) {
    
    //No options
    throw new \InvalidArgumentException("Option name '$opt_name' does not exist!");
  }
  
  // --------------------------------------------------------------
 
  public function render_output($content_item) {
    
    $output_array = array(
      'title'        => $content_item->title,
      'content-type' => 'text/html'
    );
    
    foreach($content_item->meta as $mname => $mvalue) {
      $output_array[$mname] = $mvalue;
    }
    
    return json_encode($output_array);
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