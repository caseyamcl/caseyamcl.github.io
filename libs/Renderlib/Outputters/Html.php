<?php

namespace Renderlib\Outputters;

class Html implements Outputter {
  
  private $template_dir = FALSE;
  private $template_url = FALSE;
  private $base_url = FALSE;
  private $site_url = FALSE;
  private $current_url = FALSE;
  
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
 
  /**
   * Render output
   * 
   * @param \Renderlib\Content_item $content_item
   * @return string
   * @throws \RuntimeException 
   */
  public function render_output($content_item) {
        
    $this->check_required_attrs();
    
    //Possibly render with sub-template based on optional 'type' meta property
    if (isset($content_item->meta->type)) {
      
      $template_file = $this->template_dir . 'templates' . DIRECTORY_SEPARATOR . $content_item->meta->type . '.php';
      
      if (is_readable($template_file)) {
        $content = $this->load_template($template_file, $content_item->content, $content_item);
      }
    }
    
    if ( ! isset($content)) {
      $content = $content_item->content;
    }
    
    //Main template will be $template_dir/template.php
    $template_file = $this->template_dir . DIRECTORY_SEPARATOR . 'template.php';
    return $this->load_template($template_file, $content, $content_item);
  }
  
  // --------------------------------------------------------------
 
  public function render_404_output() {
    
    $this->check_required_attrs();
    
    //If a custom 404 file exists, use that; otherwise use a simple default string
    if (is_readable($this->template_dir . DIRECTORY_SEPARATOR . '_404.php'))
      $content = $this->load_template($this->template_dir . DIRECTORY_SEPARATOR . '_404.php');
    else
      $content = "<p class='error 404'>404 - Page Not Found</p>";
    
    //Main template will be $template_dir/template.php
    $template_file = $this->template_dir . DIRECTORY_SEPARATOR . 'template.php';
    return $this->load_template($template_file, $content);
    
  }
  
  // --------------------------------------------------------------
 
  public function render_error_output($error, $msg = NULL) {
    
    $this->check_required_attrs();
    
    //If a custom error file exists, use that; otherwise use a simple default string
    if (is_readable($this->template_dir . DIRECTORY_SEPARATOR . '_error.php'))
      $content = $this->load_template($this->template_dir . DIRECTORY_SEPARATOR . '_error.php');
    else
      $content = "<p class='error general'>Error ($error) - $msg</p>";

    //Main template will be $template_dir/template.php
    $template_file = $this->template_dir . DIRECTORY_SEPARATOR . 'template.php';
    return $this->load_template($template_file, $content);
  }
  
  // --------------------------------------------------------------
  
  private function load_template($template_file, $content = NULL, $content_item = NULL) {
           
    //Local variables to inject
    $template_path = $this->template_dir;
    $template_url = $this->template_url;
    $base_url = $this->base_url;
    $site_url = $this->site_url;
    $current_url = $this->current_url;
    $content = $content . "\n";

    //If loading an actual page (not an error page)
    if ($content_item) {
      $page_files = $content_item->file_urls;
      $page_children = $content_item->children;
      $page_meta = $content_item->meta;
      $page_title = $content_item->title;
    }
    
    //Run the template
    ob_start();
    include($template_file);
    return ob_get_clean();
  }

  // --------------------------------------------------------------

  private function check_required_attrs() {

    //Check for template directory
    if ( ! $this->template_dir)
      throw new \RuntimeException("Cannot render " . __CLASS__ . ' without a template directory.  Use set_option("template_dir")');
    
    if ( ! is_readable($this->template_dir))
      throw new \RuntimeException("Cannot render " . __CLASS__ . ". Template dir does not exist or is not readable: {$this->template_dir}");
      
    //Check for template directory
    if ( ! $this->template_url)
      throw new \RuntimeException("Cannot render " . __CLASS__ . ' without a template url.  Use set_option("template_url")');    
    
    if ( ! $this->base_url OR ! $this->site_url) {
      throw new \RuntimeException("Cannot render " . __CLASS__ . ' without a base_url and site_url.  Use set_option("base_url")');
    }
    
    if ( ! $this->base_url OR ! $this->site_url) {
      throw new \RuntimeException("Cannot render " . __CLASS__ . ' without a current_url.  Use set_option("current_url")');
    }    
  }
  
}

/* EOF: Html.php */