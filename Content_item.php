<?php

class Content_item {
  
  private $path;
  
  private $url;
  
  private $title;

  private $meta;

  private $content;
  
  private $files = array();
  
	// --------------------------------------------------------------	

  public function __construct($path, $url) {
    
    $this->path = realpath($path);
    
    $this->url = $url;
    
    $this->init();
  }
  
	// --------------------------------------------------------------	
  
  public function __get($item) {
    
    return $this->$item;
    
  }
  
	// --------------------------------------------------------------	

  private function init() {
    
    //read meta and title from json file
   
    //read files from the folder
  }
  
	// --------------------------------------------------------------	
  
  private function read_meta() {
    
  }
  
	// --------------------------------------------------------------	
  
  private function read_content() {
    
  }
  
	// --------------------------------------------------------------	
  
  private function read_files() {
    
  }
}

/* EOF: Content_item.php */