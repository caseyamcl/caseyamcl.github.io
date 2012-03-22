<?php

namespace Renderlib\Outputters;

class Xml implements Outputter {
  
  public function __construct() {
       
  }
  
 // --------------------------------------------------------------
  
 public function get_mime_types() {
    
    return array(
      'application/xml',
    );
    
  }

  // --------------------------------------------------------------
 
  public function render_output(Renderlib\Content_item $content_item) {

  }
   
  // --------------------------------------------------------------
     
  public function set_option($opt_name, $opt_value) {
    
  }
  
  // --------------------------------------------------------------
   
  public function render_404_output() {
    
    $xml = new \SimpleXMLElement("<error></error>");
    $xml->addChild('type', '404');
    $xml->addChild('message', 'Content Not Found');
    return $xml->asXML();
  }
  
  // --------------------------------------------------------------
 
  public function render_error_output($error, $msg = NULL) {
    
    $out_msg = $msg ?: 'An Error Occured';
    $xml = new \SimpleXMLElement("<error></error>");
    $xml->addChild('type', $error);
    $xml->addChild('message', $out_msg);
    return $xml->asXML();
  }
}

/* EOF: Html.php */