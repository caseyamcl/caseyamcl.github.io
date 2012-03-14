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
 
  public function render_main_content(Renderlib\Content_item $content_item) {
    
  }
  
  // --------------------------------------------------------------
 
  public function render_output(Renderlib\Content_item $content_item) {

  }
   
  // --------------------------------------------------------------
 
  public function get_404_output() {
    
    $xml = new \SimpleXMLElement("<error></error>");
    $xml->addChild('type', '404');
    $xml->addChild('message', 'Content Not Found');
    return $xml->asXML();
  }
  
  // --------------------------------------------------------------
 
  public function get_500_output($msg = NULL) {
    
    $out_msg = "Internal Server Error";
    if ($msg)
      $out_msg .= ": $msg";
    
    $xml = new \SimpleXMLElement("<error></error>");
    $xml->addChild('type', '500');
    $xml->addChild('message', $out_msg);
    return $xml->asXML();
  }
}

/* EOF: Html.php */