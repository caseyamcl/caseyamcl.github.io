<?php

namespace Renderlib\Outputters;

class Html implements Outputter {
  
  private $template_dir;
  private $template_url;
  
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
    
    if (isset($this->$opt_name))
      $this->$opt_name = $opt_value;
    else        
      throw new \InvalidArgumentException("Option name '$opt_name' does not exist!");
  }
  
  // --------------------------------------------------------------
 
  public function render_output(Renderlib\Content_item $content_item) {
    
    //Check for template directory
    if ( ! $this->template_dir OR ! is_readable($this->template_dir))
      throw new \RuntimeException("Cannot render " . __CLASS__ . ' without a template directory.  Use set_option("template_dir")');
    
    //Possibly render with sub-template based on optional 'type' meta property
    if (isset($content_item->meta->type)) {
      
      $template_file = $this->template_dir . 'templates' . DIRECTORY_SEPARATOR . $this->meta->type . '.php';
      
      if (is_readable($template_file)) {
        $content = $this->load_template($template_file, $content_item->content);
      }
    }
    else {
      $content = $content_item->content;
    }
    
    //Main template will be $template_dir/template.php
    $template_file = $this->template_dir . DIRECTORY_SEPARATOR . 'template.php';
    return $this->load_template($template_file, $content);
  }
  
  // --------------------------------------------------------------
 
  public function render_404_output() {
    
    //If a custom 404 file exists, use that; otherwise use a simple default string
    if (is_readable($this->template_dir . DIRECTORY_SEPARATOR . '_404.php'))
      $content = $this->load_template($this->template_dir . DIRECTORY_SEPARATOR . '_404.php');
    
    //Main template will be $template_dir/template.php
    $template_file = $this->template_dir . DIRECTORY_SEPARATOR . 'template.php';
    return $this->load_template($template_file, $content);
    
  }
  
  // --------------------------------------------------------------
 
  public function render_error_output($error, $msg = NULL) {
    
    //If a custom error file exists, use that; otherwise use a simple default string
    if (is_readable($this->template_dir . DIRECTORY_SEPARATOR . '_error.php'))
      $content = $this->load_template($this->template_dir . DIRECTORY_SEPARATOR . '_error.php');

    //Main template will be $template_dir/template.php
    $template_file = $this->template_dir . DIRECTORY_SEPARATOR . 'template.php';
    return $this->load_template($template_file, $content);
  }
  
  // --------------------------------------------------------------
  
  private function load_template($template_file, $content = NULL) {
    
    //Local variables to inject
    $template_path = $this->template_dir;
    $template_url = $this->template_url;
    //$content also...
    
    //Run the template
    ob_start();
    include($template_file);
    return ob_get_clean();
  }

}

/* EOF: Html.php */