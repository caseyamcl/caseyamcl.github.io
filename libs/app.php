<?php

/**
 * @file index.php
 * CaseyMcLaughlin.com Application - All in one file! w00t!
 *
 * @package CaseyMcLaughlin.com
 * @author Casey McLaughlin
 */

//One line to make her run
App::go();


/* Application Class
 * =========================================================================
 */

class App {
   
  //Services we'll need every time
  private $req_object;
  private $url_object;
  private $cache_driver;
  private $asset_mapper;
  private $config;
  private $error_wrapper;  
  
  //Services we may not need depending on application execution
  private $content_mapper;
  private $render_mapper;

  //Calcualted Request Info
  private $req_content_type;
  private $req_language;
  
  /**
   * @var \Requesty\Response
   */
  private $output = FALSE;
  
  // -----------------------------------------------------------------------
  
  public static function go() {
            
    $that = new App();
    $that->run();
  }
  
  // -----------------------------------------------------------------------
   
  /**
   * Application Constructor
   * 
   * Anything that fails during execution here won't throw a friendly error,
   * so put the bare minimum here. 
   */
  public function __construct() {
    
    //Setup Basepath
    $ds = DIRECTORY_SEPARATOR;
    define('BASEPATH', realpath(__DIR__ . $ds . '..') . $ds);
    
    //Register PSR-0 Autoloader
    spl_autoload_register('autoload');   
    
    //Setup error handling
    $this->error_wrapper = \Requesty\ErrorWrapper::invoke();
    
  }
  
  // -----------------------------------------------------------------------
  
  /**
   * Run the Application
   * 
   * Produces output
   */
  public function run() {
        
    //Go!
    try {
    
      
      //Convenient helper
      $ds = DIRECTORY_SEPARATOR;
      
      //Require the only non-PSR-0 library
      require_once(BASEPATH . "libs{$ds}Requesty{$ds}Browscap.php");

      //Setup Configuration
      $this->load_configuration();
      
      //Load the cache library
      if ($this->config->cache_driver) {
        $cache_opts = $this->config->cache_options[$this->config->cache_driver] ?: array();
        $this->cache_driver = \Cachey::factory($this->config->cache_driver, $cache_opts);
      }
      
      //Load some prerequisite libraries
      $this->url_object = new \Requesty\Uri();
            
      //Load Content or throw an exception
      if ($this->load_content() === FALSE) {
        throw new Exception("No output was generated during application execution!");        
      }
      
      
    } catch (Exception $e) {
      return $this->fail($e);
    }
    
  }
  
  // -----------------------------------------------------------------------
 
  /**
   * Load Default Configuration and Configuration Object 
   */
  private function load_configuration() {

    $defaults = array();
    $defaults['content_path']          = BASEPATH . 'content';
    $defaults['template']              = BASEPATH . 'template';
    $defaults['environment']           = 'production';
    $defaults['cache_method']          = FALSE;
    $defaults['cache_options']['file'] = array(
      'filepath'           => BASEPATH . 'cache',
      'default_expiration' => 86400
    );
        
    $this->config = new Configurator\Config($defaults);
  }
 
  // -----------------------------------------------------------------------
 
  /**
   * Attempt to load content
   * 
   * @return boolean 
   */
  private function load_content() {
         
    //1. Attempt to load the content as an asset
    if ($this->output === FALSE) {
      $this->output = $this->load_content_asset();
    }
    
    //If not an asset, we'll need to load some info about the request
    $this->negotiate_request();
    
    //2. Attempt to load the content from cache
    if ($this->output === FALSE) {
      $this->output = $this->load_content_item_from_cache();
    }
    
    //3. Attempt to load the content by rendering it through the system
    if ($this->output === FALSE) {
      $this->ouptut = $this->render_content();
    }
    
    return (boolean) $this->output;
  }
  
  // -----------------------------------------------------------------------

  private function negotiate_request() {

    //Load the request object
    $cache_dir = $this->config->cache_options['file']['filepath'] ?: sys_get_temp_dir();
    $this->req_object = new Requesty\Request(new Browscap($cache_dir));

    //LEFT OFF HERE FIRST!
    
    //Content type -- override with:
    // - content_type in GET (corresponds to content_type - priority)
    // - format in GET (corresponds to classname of content types)
    
    //Language
    // - lang in GET
  }
  
  /**
   * Load Content Asset
   * 
   * @return \Requesty\Response|boolean 
   * FALSE if no asset could be found at that URL
   */
  private function load_content_asset() {
    
    //Get the mappings to which folders things go
    $asset_path_mappings = array(
      ''         => $this->config->content_path,
      'template' => $this->config->template_path
    );
    
    //Load the asset loader
    $asset_loader = new Assetlib\Assetlib($asset_path_mappings);
    
    //Get the path string
    $path_string = $this->url_object->get_path_string();

    //See if the object is associated with a mime
    $mime = $asset_loader->get_asset_mime($path_string);
    
    if ($mime) {

      $file = $asset_loader->get_asset_filepath($path_string);
      if ($file) {
        //Return content
        return new Requesty\Response($file, 200, $mime, Requesty\Response::FILEPATH);
      }
    }
    
    //If made it here...
    return FALSE;
  }
  
  // -----------------------------------------------------------------------

  private function load_content_item_from_cache() {
    
    //If no cache enabled, do nothing
    if ( ! $this->cache_driver) {
      return FALSE;
    }
    
    //If pragma: no-cache set, then skip caching
    if ($this->req_object->get_header('Pragma') == 'no-cache') {
      return FALSE;
    }
    
    //If query string specifies no cache, then skip caching
    if (isset($_GET['cache']) && $_GET['cache'] == FALSE) {
      return FALSE;
    }
    else {
       $result = ($c['cache']) ? $c['cache']->retrieve_cache_item($cache_key) : FALSE;
       
       //LEFT OFF HERE SECOND!
    }
    
  }
  
  // -----------------------------------------------------------------------

  /**
   * Fail Method
   * 
   * Attempts to show a pretty failure message, and if that fails, then
   * shows a generic error message
   * 
   * @param Exception $e 
   */
  private function fail($e) {
    
  }
  
  // -----------------------------------------------------------------------

  /**
   * PSR-0 Autoloader
   *
   * From https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
   *
   * @param string $class_name
   */
  public function autoload($class_name)
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
}


/* EOF: app.php */