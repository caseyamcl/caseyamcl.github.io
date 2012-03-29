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
 
  public function render_output($content_item) {

    $output = new \SimpleXMLElement("<content></content>");
    $output->addChild("title", $content_item->title);
    
    if (count($content_item->children) > 0) {
      $children = $output->addChild('directory');
      foreach($content_item->children as $url => $title) {
        $c = $children->addChild($title);
        $c->addAttribute('url', $url);
      }      
    }
    
    $meta = $output->addChild('meta');
    foreach($content_item->meta as $mname => $mvalue) {
      $meta->addChild($mname, $mvalue);
    }
    $output->addChild('content_type', 'text/html');
    $output->addChild('content', $content_item->content);
    
    return $output->asXML();
  }
   
  // --------------------------------------------------------------
     
  public function set_option($opt_name, $opt_value) {
    
    //No options
    throw new \InvalidArgumentException("Option name '$opt_name' does not exist!");
    
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