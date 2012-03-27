<?php

namespace Renderlib\Outputters;

Interface Outputter {
  
  public function __construct();
  
  public function render_output($content_item);
  
  public function render_404_output();
  
  public function render_error_output($error, $msg = NULL);
  
  public function get_mime_types();
 
  public function set_option($opt_name, $opt_value);
  
}