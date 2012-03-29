<?php

namespace Renderlib\Outputters;

class Txt implements Outputter {
  
  private $convert_to_markdown = FALSE;
  
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
    
     if (isset($this->$opt_name))
      $this->$opt_name = $opt_value;
    else        
      throw new \InvalidArgumentException("Option name '$opt_name' does not exist!");
  
  }
  
  // --------------------------------------------------------------
   
  public function render_output($content_item) {
    
    $output  = $content_item->title;
    $output .= "\n";
    foreach($content_item->meta as $mname => $mvalue) {
      $output .= $mname . ": " . $mvalue . "\n";
    }
    
    if (count($content_item->children) > 0) {
      $output .= '~~~~~~~~~~~~~~~~~~~~~~~~~~' . "\n\n";


      $output .= 'Directory:' . "\n";

      foreach($content_item->children as $url => $title) {
        $output .= "$title\t\t\t$url";
      }

      $output .= "\n\n";
    }
    
    $output .= '~~~~~~~~~~~~~~~~~~~~~~~~~~' . "\n\n";
    
    if ($this->convert_to_markdown)
      $output .= $this->convert_to_markdown($content_item->content) . "\n\n";
    else
      $output .= $content_item->content . "\n\n";     
      
    return $output;
  }
   
  // --------------------------------------------------------------
 
  public function render_404_output() {
    return "404 - Content not Found";
  }
  
  // --------------------------------------------------------------
 
  public function render_error_output($error, $msg = NULL) {
    return "Error: $error\n\n$msg";
  }
}

/* EOF: Html.php */