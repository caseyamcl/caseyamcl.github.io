<?php

namespace ContentMapper;

class Malformed_ContentItem_Exception extends \Exception { /* ... */ }

/**
 * Content Item Class
 * 
 * @author Casey McLaughlin
 * @package ContentMapper 
 */
class Content_item {
  
  /**
   * @var string  The realpath for this content
   */
  private $path;
  
  /**
   * @var string  The URL for this content
   */
  private $url;
  
  /**
   * @var string  The title of this content
   */
  private $title;

  /**
   * @var array  An object with the meta properties for this content
   */
  private $meta;

  /**
   * @var string  The actual content
   */
  private $content;
  
  /**
   * @var array  An array offiles in this content folder (URLs)
   */
  private $file_urls = array();

  /** 
   * @var array  An array of files in this content folder (system paths)
   */
  private $file_paths = array();
  
  /**
   * @var The meta file name to use
   */
  private $_meta_filename = 'meta.json';
  
  /**
   * @var The content file name to use
   */
  private $_content_filename = 'content.php';
  
	// --------------------------------------------------------------	

  /**
   * Construct a content item
   * 
   * @param string $path A working path to the content item
   * @param string $url  The URL is the full URL to the item
   * @throws Exception   If the path doesn't actually exit
   */
  public function __construct($path, $url) {
    
    $this->path = realpath($path). DIRECTORY_SEPARATOR;
    
    if ( ! is_readable($this->path))
      throw new Exception("The path $path does not actually exist or is not readable!");
    
    //Append a trailing slash to the URL
    if (substr($url, -1) != '/')
      $url .= '/';
            
    $this->url = $url;
    
    //Read meta, content and files
    $this->read_meta();
    $this->read_files();
    $this->read_content();
  }
  
	// --------------------------------------------------------------	
  
  public function __get($item) {
  
    if ($item{0} != '_')
      return $this->$item;
    else
      throw new \Exception("Cannot access private property: $item");
  }
  
	// --------------------------------------------------------------	
  
  /**
   * Reads meta from meta file
   * 
   * @return int  The number of meta properties
   * @throws Malformed_ContentItem_Exception
   */
  private function read_meta() {
        
    if ( ! is_readable($this->path . $this->_meta_filename)) {
      throw new Malformed_ContentItem_Exception("Cannot find required meta file ({$this->_meta_filename})");
    }
    
    $meta = json_decode(file_get_contents($this->path . $this->_meta_filename));
    
    if ( ! $meta) {
      throw new Malformed_ContentItem_Exception("Malformed meta.json file.  Check JSON");
    }
    
    if ( ! isset($meta->title)) {
      throw new Malformed_ContentItem_Exception("Malformed meta.json file.  Missing required title attribute");
    }
    
    $this->title = $meta->title;
    unset($meta->title);
    $this->meta = $meta;
    
    return count($this->meta);
  }
  
	// --------------------------------------------------------------	
  
  /**
   * Read content from the content file
   * 
   * @return int  The character count of the content
   * @throws Malformed_ContentItem_Exception 
   */
  private function read_content() {
   
    if ( ! is_readable($this->path . $this->_content_filename)) {
      throw new Malformed_ContentItem_Exception("Cannot find required content file ({$this->_content_filename})");      
    }
    
    //Create local variables from object properties
    $page_url = $this->url;
    $page_path = $this->path;
    
    ob_start();
    include($this->path . $this->_content_filename);
    $this->content = ob_get_clean();
    
    return strlen($this->content);
  }
  
	// --------------------------------------------------------------	
  
  /**
   * Read files from the folder
   * 
   * @return int  The number of files
   */
  private function read_files() {
    
    $files = $this->read_dir_to_array($this->path);
    
    //Convert the files to URLS
    $this->file_urls = array_map(array($this, 'generate_file_urls_callback'), $files);
    
    //Convert the files to realpaths
    $this->file_paths = array_map(array($this, 'generate_file_paths_callback'), $files);
    
    return count($files);
  }
  
  // --------------------------------------------------------------	
  
  /**
   * Callback for read_files() method
   * 
   * @param string $val
   * @return string
   */
  private function generate_file_urls_callback($val) {
    return $this->url . $val;
  }
  
  // --------------------------------------------------------------	
  
  /**
   * Callback for read_files() method
   * 
   * @param string $val
   * @return string
   */
  private function generate_file_paths_callback($val) {
    return realpath($this->path . str_replace('/', DIRECTORY_SEPARATOR, $val));
  }
  
  // --------------------------------------------------------------	
   
  /**
	 * Recursively scan a directory and returns an array of files
	 * 
	 * Ignores hidden files
	 * 
	 * @param string $path
	 */
	private function read_dir_to_array($path)
	{
		$out_files = array();
		
		foreach(scandir($path) as $file)
		{
			if ($file{0} == '.' OR $file == $this->_content_filename OR $file == $this->_meta_filename)
				continue;
			elseif (is_file($path . DIRECTORY_SEPARATOR . $file))
				$out_files[] = $file;
			else
				$out_files = array_merge($out_files, $this->read_dir_to_array($path . DIRECTORY_SEPARATOR . $file));
		}
		
		return $out_files;
	}  
}

/* EOF: Content_item.php */