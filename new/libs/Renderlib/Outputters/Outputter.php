<?php

namespace Renderlib\Outputters;

Interface Outputter {
  
  public function __construct();
  
  public function render_output(Renderlib\Content_item $content_item);
  
  public function render_404_output();
  
  public function render_error_output($error, $msg = NULL);
  
  public function get_mime_types();
   
}