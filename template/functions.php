<?php

/**
 * @file helpers.php
 * Helpers File for the template 
 */

// ------------------------------------------------------------------------

/**
 * Navigation Builder 
 * 
 * Recursive function to build HTML navigation menu
 * 
 * @param array $nav_array
 * @param $base_url     Specify the base URL
 * @param $current_url  Optionally specify the current path
 * @return string
 */
function build_navigation($nav_array, $base_url, $current_url = NULL) {

  //Append trailing slash to base_url and current_url
  if (substr($base_url, -1) != '/') {
    $base_url .= '/';
  }
  if (substr($current_url, -1) != '/') {
    $current_url .= '/';
  }
  
    
  $items = array();
  foreach($nav_array as $path => $nav) {
    
    //Set item properties
    $item_url = (strlen($path) > 0) ? $base_url . $path . '/' : $base_url;
    $item_disp = (isset($nav['display'])) ? $nav['display'] : $nav['path'];
    
    if (isset($nav['description'])) {
      $item_disp .= "<span>" . $nav['description'] . "</span>";
      $item_title = $nav['description'];
    }
    elseif (isset($nav['title'])) {
      $item_title = $nav['title'];
    }
    else {
      $item_title =  'Link to ' . $item_disp;
    }

    
    //Determine current URL
    $current_html = '';
    if ($current_url) {
      
      //If exact match
      if ($item_url == $current_url) {
        $current_html = " class='current'";
      }
      
      //If is base match
      elseif (strlen($item_url >= $current_url) && substr($item_url, 0, strlen($current_url)) == $current_url) {
        $current_html = (isset($nav['sub'])) ? " class='current_ansecstor'" : " class='current'";
      }
    }
    
    //Build the item HTML
    $item = "<li><a href='$item_url' title='$item_title'$current_html>$item_disp</a></li>";

    //If there are sub items, add those to the HTML
    if (isset($nav['sub'])) {
      $item .= build_navigation($nav['sub'], $item_url);
    }
    
    if ( ! empty($item)) {
      $items[] = $item;
    }
  }
  
  //Return the finished string
  if ($items) {
    return "<ul>\n" . implode("\n", $items) . "\n</ul>";  
  }
  else {
    return NULL;
  }
}

// ------------------------------------------------------------------------

function load_page_specific_css($page_files) {
  
  $out_str = '';
  
  foreach($page_files as $pf) {
    if (substr($pf, -4) == '.css') {
      $out_str .= "<link rel='stylesheet' type='text/css' href='$pf' />\n";
    }
  }
  
  return $out_str;  
}

// ------------------------------------------------------------------------

/**
 * Checks for a compiled version of the LESS CSS code and falls back on slow
 * manual compilation
 * 
 * @param $template_dir The Template Directory
 * @param $template_url The Template URL
 */
function load_less_css($template_dir, $template_url) {

  //Slashes
  if (substr($template_dir, -1) != DIRECTORY_SEPARATOR)
    $template_dir .= DIRECTORY_SEPARATOR;
  if (substr($template_url, -1) != '/')
    $template_url .= DIRECTORY_SEPARATOR;
  
  if (is_readable($template_dir . 'css/main.css')) {
    
    $ss_url = $template_url . 'css/main.css';
    return "<link rel='stylesheet' type='text/css' url='$ss_url' />";
    
  }
  elseif (is_readable($template_dir . 'css/main.less')) {
    
    $js_url = $template_url . 'js/less.js';
    $ss_url = $template_url . 'css/main.less';

    $str  = "<link rel='stylesheet/less' type='text/css' href='$ss_url' />";
    $str .= "<script type='text/javascript' src='$js_url'></script>";

    return $str;
  }
  
}

/* EOF: helpers.php */