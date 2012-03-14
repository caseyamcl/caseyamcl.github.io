<?php

namespace Renderlib\Outputters;

Interface Outputter {
  
  public function __construct();
  
  public function render_main_content(Renderlib\Content_item $content_item);
  
  public function render_output(Renderlib\Content_item $content_item);
  
  public function get_404_output();
  
  public function get_500_output($msg = NULL);
  
  public function get_mime_types();
   
}