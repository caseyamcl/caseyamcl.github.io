<?php

/**
 * @file index.php
 * CaseyMcLaughlin.com Application - All in one file! w00t! 
 * 
 * @package CaseyMcLaughlin.com
 * @author Casey McLaughlin
 * 
 * @TODO: Implement routing rules for asset files (define what assets are)
 *        Whitelist: css, jpg, jpeg, png, gif, css, js
 * 
 * @TODO: Add caching library (w/optional drivers for memcache, etc)
 * 
 * @TODO: Use that Github library for better universal error handling similar
 * to Laravel
 */

/* A. Define Constants
/* -------------------------------------------------------------------------
 */
define('BASEPATH', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);


/* B. Load Resources
/* -------------------------------------------------------------------------
 */

//Load Non-PSR Classes that we'll be using no matter what anyway
require_once(BASEPATH . 'libs/Pimple/Pimple.php');
require_once(BASEPATH . 'libs/Requesty/Browscap.php');

/**
 * PSR-0 Autoloader
 * 
 * From https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 * 
 * @param string $class_name 
 */
function autoload($class_name)
{ 
  if (class_exists($class_name))
    return;

  $class_name = ltrim($class_name, '\\');
  $basepath   = BASEPATH . 'libs' . DIRECTORY_SEPARATOR;
  $file_name  = '';
  $namespace = '';
  $last_ns_pos = strripos($class_name, '\\');
  
  if ($last_ns_pos) {
    $namespace = substr($class_name, 0, $last_ns_pos);
    $class_name = substr($class_name, $last_ns_pos + 1);
    $file_name  = $basepath . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
  }
  $file_name .= str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
  
  require $file_name;
}
spl_autoload_register('autoload');


/* C. Setup Dependency Injection (with Pimple)
/* -------------------------------------------------------------------------
 */

$c = new Pimple();

//Paths
$c['cache_path']   = BASEPATH . 'cache';
$c['content_path'] = BASEPATH . 'content';

//Request/Response Objects
$c['request_obj']  = $c->share(function($c) { return new Requesty\Request(new Browscap($c['cache_path'])); });
$c['response_obj'] = $c->share(function($c) { return new Requesty\Response(); });
$c['url_obj']      = $c->share(function($c) { return new Requesty\Uri(); });

//Content Mapper
$c['content_url'] = $c['url_obj']->get_base_url();
$c['mapper_obj']  = $c->share(function($c) { return new ContentMapper\Mapper($c['content_path'], $c['content_url']); });

//Renderer
$c['render_obj'] = $c->share(function($c) { return new Renderlib\Renderlib(); }); 


/* GO!!
 * =========================================================================
 */


/* 1. Negotiate Request
/* -------------------------------------------------------------------------
 */

//Negotiate Content Type
$content_type = $c['request_obj']->negotiate(
  $c['request_obj']->get_accepted_types(TRUE),
  $c['render_obj']->get_available_content_types(TRUE)
);

//Negotiate Language (English is the only language offered)
$language = $c['request_obj']->negotiate(
  $c['request_obj']->get_languages(TRUE),
  array('en-us', 'en')
);

//Get Path
$req_path = $c['url_obj']->get_path_string();

//Build MD5 String for these three things (for cache purposes)
$request_md5 = md5($req_path . $content_type . $language);


/* 2. Load cache library and check for cached version
/* -------------------------------------------------------------------------
 */

//Skip for now
$cache_data = FALSE;

//@TODO: Write universal cache library and content cache library, and
//generate $output variable if there is cache data


/* 3. No cached version?  Build content item
 * -------------------------------------------------------------------------
 */
if ( ! $cache_data) {
 
  //Get the object from the path...
  try {
       
    //Define renderer options for different formatters
    $render_options = array(
      'Html' => array(
        'template_dir' => BASEPATH . 'template',
        'template_url' => $c['url_obj']->get_base_url()
      )  
    );
    
    //Load a renderer based on the content-type http header
    $renderer = $c['render_obj']->get_outputter_from_mime_type($content_type);
       
    //Load renderer options
    if (isset($render_options[get_base_class($renderer)])) {
      
      foreach($render_options[get_base_class($renderer)] as $opt_name => $opt_value) {
        $renderer->set_option($opt_name, $opt_value);
      }
      
    }
        
    //Load the content item
    $content_item = $c['mapper_obj']->load_content_object($req_path);    
    
    //Get the output by sending the content item to the renderer for rendering
    $output = $renderer->render_output($content_item);
    
  } 
  catch (ContentMapper\MapperException $e) {
    $http_status = 404;
    
    if ( ! isset($renderer)) {
      $renderer = $c['render_obj']->get_outputter_from_mime_type('text/plain');    
    }
    
    $output = $renderer->render_404_output();
  }
  catch (Renderlib\InvalidRenderMimeTypeException $e) {
    $http_status = 415;
       
    //Default to text content type, because we don't know which to use...
    $renderer = $c['render_obj']->get_outputter_from_mime_type('text/plain');
    $output = $renderer->render_error_output($http_status, 'Could not negotiate content-type');    
  }
  
}


/* 4. Render output
/* -------------------------------------------------------------------------
 */

$c['response_obj']->set_http_status(isset($http_status) ? $http_status : 200);
$c['response_obj']->set_output($output);
$c['response_obj']->go();


/* Functions
 * =========================================================================
 */

/**
 * Return the basic name for a namespaced class
 * 
 * @param object $obj
 * @return string
 */
function get_base_class($obj) {
  
  $classname = get_class($obj);
  $arr = explode('\\', $classname);
  return array_pop($arr);  
}

/* EOF: index.php */