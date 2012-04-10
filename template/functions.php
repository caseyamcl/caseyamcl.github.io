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
    $item_url = $base_url . $path . '/';
    $item_desc = (isset($nav['display'])) ? $nav['display'] : $nav['path'];
    $item_title = (isset($nav['title'])) ? $nav['title'] : 'Link to' . $item_desc;
    if (isset($nav['description'])) {
      $item_desc .= "<span>" . $nav['description'] . "</span>";
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
    $item = "<li><a href='$item_url' title='$item_title'$current_html>$item_desc</a></li>";

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

/* EOF: helpers.php */