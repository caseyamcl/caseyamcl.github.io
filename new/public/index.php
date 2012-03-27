<?php

/**
 * @file index.php
 * CaseyMcLaughlin.com Application - All in one file! w00t!
 *
 * @package CaseyMcLaughlin.com
 * @author Casey McLaughlin
 */

/* Setup Application
 * =========================================================================
 */

try {

  //Constants
  define('BASEPATH', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

  //Load Non-PSR Classes that we'll be using no matter what anyway
  require_once(BASEPATH . 'libs/Pimple/Pimple.php');
  require_once(BASEPATH . 'libs/Requesty/Browscap.php');

  //Register PSR-0 Autoloader
  spl_autoload_register('autoload');

  //Get Libraries (Pimple DI Container)
  $c = get_libraries();

  //Set error wrapper after attempting to load asset
  $c['error_wrapper']->setup();


  /* GO!!
  * =========================================================================
  */

  // Set Ouptut to FALSE
  $output = FALSE;

  // Check if URL is actually an asset, and load that
  if ( ! $output) {

    $output = load_asset($c);
  }
    
  // If Ouptut is False, Negotiate the Request and Attempt to load from Cache
  if ( ! $output) {

    //Negotiate Content Type
    $content_info = negotiate_content_info($c);

    //Build MD5 String for these three things (for cache purposes)
    $output = load_content_from_cache($content_info, $c);
  }
  
  // If Output is still False, Try loading the content Item
  if ( ! $output) {

    $output = load_rendered_content($content_info, $c);

  }

  // Still no Output?  Fail!
  if ( ! $output) {
    throw new Exception("No output was generated during application execution!");
  }
  
  // Render Output
  $c['response_obj']->go();
  
} catch (Exception $e) {
  
  //Check for $c, $c['response_obj']
  if (isset($c) && $c && $c['response_obj']) {
    
    //If we can resolve a content-type, use the built-in error template
    
    //Elseif we can use text/plain, just use text/plain error template
    
    //Else just print some stuff to the screen
    
  }
  else {
    
    //Just print some stuff to the screen
    
  }
  
}

/* Functions
 * =========================================================================
 */

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

// -------------------------------------------------------------------------

/**
 * Get libraries in the form of a Pimple DI Container
 *
 * @return Pimple
 */
function get_libraries() {

  $c = new Pimple();

  //Paths Configuration
  $c['cache_path']    = BASEPATH . 'cache';
  $c['content_path']  = BASEPATH . 'content';
  $c['template_path'] = BASEPATH . 'template';
  
  //Request/Response Objects
  $c['request_obj']  = $c->share(function($c) { return new Requesty\Request(new Browscap($c['cache_path'])); });
  $c['response_obj'] = $c->share(function($c) { return new Requesty\Response(); });
  $c['url_obj']      = $c->share(function($c) { return new Requesty\Uri(); });

  //Cache Configuration and Library
  $c['cache_driver'] = 'file';
  $c['cache_driver_opts'] = array(
    'filepath' => BASEPATH . 'cache',
    'default_expiration' => 86400
  );
  
  $c['cachey'] = $c->share(function($c) { return new Cachey\Cachey(); }); 
  $c['cache'] = $c->share(function($c) { return $c['cachey']->factory($c['cache_driver'], $c['cache_driver_opts']); });
  
  //Content Mapper
  $c['content_url'] = $c['url_obj']->get_base_url();
  $c['mapper_obj']  = $c->share(function($c) { return new ContentMapper\Mapper($c['content_path'], $c['content_url']); });

  //Renderer
  $c['render_obj'] = $c->share(function($c) { return new Renderlib\Renderlib(); });

  //Asset Mapper path configuration
  $c['asset_paths'] = array(
    ''         => $c['content_path'],
    'template' => $c['template_path']
  );
  
  //Error Wrapper
  $c['error_wrapper'] = $c->share(function($c) { return new Requesty\ErrorWrapper(); });

  //Asset Mapper
  $c['asset_obj'] = $c->share(function($c) { return new Assetlib\Assetlib($c['asset_paths']); });

  return $c;
}

// -------------------------------------------------------------------------

/**
 * Return the basic name for a namespaced class (helper function)
 *
 * @param object $obj
 * @return string
 */
function get_base_class($obj) {

  $classname = get_class($obj);
  $arr = explode('\\', $classname);
  return array_pop($arr);
}

// -------------------------------------------------------------------------

/**
 * Negotiate content Information
 *
 * @param Pimple $c
 * @return array
 */
function negotiate_content_info($c) {

  $content_info = array();

  //Allow content_type in query string to override request header
  $req_type = (isset($_GET['content_type']))
    ? array('1' => $_GET['content_type']) : $c['request_obj']->get_accepted_types(TRUE);

  //Negotiate content type
  $content_info['content_type'] = $c['request_obj']->negotiate(
    $req_type,
    $c['render_obj']->get_available_content_types(TRUE),
    'text/plain'
  );

  //Allow lang in query string to override request header
  $req_lang = (isset($_GET['lang'])) ? array('1' => $_GET['lang']) : $c['request_obj']->get_languages(TRUE);
  
  //Negotiate Language (English is the only language offered)
  $content_info['language'] = $c['request_obj']->negotiate(
    $req_lang, array('en-us', 'en'), 'en-us'
  );
  
  //Get Path
  $content_info['req_path'] = $c['url_obj']->get_path_string();

  return $content_info;
}

// -------------------------------------------------------------------------

/**
 * Attempt to load an Asset into Output
 * 
 * @param Pimple $c
 * @return boolean 
 */
function load_asset($c) {

  $mime = $c['asset_obj']->get_asset_mime($c['url_obj']->get_path_string());

  if ($mime) {
    $file = $c['asset_obj']->get_asset_filepath($c['url_obj']->get_path_string());

    if ($file) {
      $c['response_obj']->set_http_status(200);
      $c['response_obj']->set_http_content_type($mime);
      $c['response_obj']->set_output($file, $c['response_obj']::FILEPATH);
      return TRUE;
    }
  }

  //If made it here...
  return FALSE;

}

// -------------------------------------------------------------------------

/**
 * Attempt to load Cached Content into Output
 *
 * Also handles cache overrides if defined in the query string
 * 
 * @param Pimple $c
 * @return boolean
 */
function load_content_from_cache($content_info, $c) {
  
  //Get cache options from query string
  // can be 'skip', 'clear', 'destroy'
  //@TODO: Allow custom headers as well
  $cache_opt = (isset($_GET['cache'])) ? $_GET['cache'] : FALSE;  

  //Build a custom cache key
  $cache_key = md5('content:' . implode('', $content_info));
  
  try {
    switch($cache_opt) {

      case 'skip':
        return FALSE;

      case 'clear':
        if ($c['cache']) {
          $c['cache']->clear_cache($cache_key);
        }
        return FALSE;

      case 'destroy':
        if ($c['cache']) {
          $c['cache']->clear_cache();
        }
        return FALSE;

      default:
        $result = ($c['cache']) ? $c['cache']->retrieve_cache_item($cache_key) : FALSE;
        
        if ($result) {
          $c['response_obj']->set_http_status(200);
          $c['response_obj']->set_http_content_type($content_info['content_type']);
          $c['response_obj']->set_output($result);
          return TRUE;
        }
        else {
          return FALSE;
        }
      break;
    }
  } catch (\Cachey\Cachey_Exception $e) {
    
    return FALSE;
    
  }
}

// -------------------------------------------------------------------------

/**
 * Attempt to load Rendered Content into Output, or show a 400/500 error
 *
 * @param Pimple $c
 * @return boolean
 */
function load_rendered_content($content_info, $c) {

  //Define renderer options for different formatters
  $render_options = array(
    'Html' => array(
      'template_dir' => $c['template_path'],
      'template_url' => $c['url_obj']->get_base_url()
    )
  );

  //Get the object from the path...
  try {
    
    //Load a renderer based on the content-type http header
    $renderer = $c['render_obj']->get_outputter_from_mime_type($content_info['content_type']);

    //Load renderer options
    if (isset($render_options[get_base_class($renderer)])) {
      foreach($render_options[get_base_class($renderer)] as $opt_name => $opt_value) {
        $renderer->set_option($opt_name, $opt_value);
      }
    }

    //Load the content item
    $content_item = $c['mapper_obj']->load_content_object($content_info['req_path']);

    //Render the output
    $rendered_output = $renderer->render_output($content_item);
    
    //If using a cache, attempt to add the item to the cache
    if ($c['cache']) {
      try {
        $cache_key = md5('content:' . implode('', $content_info));
        $c['cache']->create_cache_item($cache_key, $rendered_output);
      } catch (\Cachey\Cachy_Exception $e) {
        //pass...
      }
    }
    
    //Get the output by sending the content item to the renderer for rendering
    $c['response_obj']->set_http_status(200);
    $c['response_obj']->set_http_content_type($content_info['content_type']);
    $c['response_obj']->set_output($rendered_output);
  }
  catch (ContentMapper\MapperException $e) {
    $http_status = 404;

    if ( ! isset($renderer)) {
      $renderer = $c['render_obj']->get_outputter_from_mime_type('text/plain');
    }

    $c['response_obj']->set_http_status(404);
    $c['response_obj']->set_http_content_type($content_info['content_type'] ?: 'text/plain');
    $c['response_obj']->set_output($renderer->render_404_output());
  }
  catch (Renderlib\InvalidRenderMimeTypeException $e) {
    $http_status = 415;

    //Default to text content type, because we don't know which to use...
    $renderer = $c['render_obj']->get_outputter_from_mime_type('text/plain');
    $output = $renderer->render_error_output($http_status, 'Could not negotiate content-type');    
    $c['response_obj']->set_http_status(404);
    $c['response_obj']->set_http_content_type('text/plain');
    $c['response_obj']->set_output($output);    
  }

  return TRUE;
}

/* EOF: index.php */