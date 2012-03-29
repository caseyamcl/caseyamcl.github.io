<?php

namespace ContentMapper;

class MapperException extends \Exception { /* ... */ }

class Mapper {

  const FILEPATH = 1;
  const URLPATH = 2;
  
  /**
   * Content Folder (sans trailing slash)
   * @var string
   */
  private $content_path;
	
  /**
   * Content URL (with trailing slash)
   * @var string
   */
  private $content_url;
  
	// --------------------------------------------------------------	  
  
  /**
   * Constructor 
   * 
   * @param string $content_folder   The base path content items
   * @param string $content_url      A full base url to content items
   */
  public function __construct($content_path, $content_url) {
    
    //Remove trailing slash from content path if it exists
    $content_path = rtrim($content_path, DIRECTORY_SEPARATOR);
    
    //Add trailing slash to URL if it doesn't exist
    if (substr($content_url, -1) != '/')
      $content_url .= '/';
   
    $this->content_url = $content_url;
    
    //Ensure path exists
    if ( ! is_readable($content_path) OR ! is_dir($content_path)) {
      throw new \RuntimeException("Cannot read from the content folder: $content_folder");
    }
        
    $this->content_path = $content_path;
  }
	
	// --------------------------------------------------------------	  
  
  /**
   * Get a sitemap in array key/value format
   * 
   * Key is full URL & Value is the Title of the Item
   * 
   * @param string $subfolder  If defined, starts at the specified subfolder
   * @return array
   */
  public function get_sitemap($subdir = '') {

    return $this->scan_content_directory($subdir);
    
  }
	
	// --------------------------------------------------------------	
  
  /**
   * Load a page object for a URL or filepath
   * 
   * @param string $path  URL or filepath of object
   * @param int $type     Type of path sent (self::FILEPATH or self::URLPATH)
   */
  public function load_content_object($path, $type = self::URLPATH) {
    
    $realpath = ($type == self::URLPATH) ? $this->map_urlpath_to_filepath($path) : $path;
    
    if ($realpath === FALSE) {
      throw new MapperException("Cannot find content item at " . (($type == self::URLPATH) ? 'url' : 'path') . ": $path");
    }
    
    $subpath = substr($realpath, strlen($this->content_path));
    return new Contentitem($realpath, $this->content_url . $path, $this->get_sitemap($subpath));
  }

	// --------------------------------------------------------------	
  
  /**
   * Map a URL path (e.g some/path) to a content item
   * 
   * @param string $path
   * @return boolean|string  FALSE if the content does not exist, the realpath if it does
   */
  public function map_urlpath_to_filepath($urlpath) {
    
    $path = ltrim(str_replace('/', DIRECTORY_SEPARATOR, $urlpath), DIRECTORY_SEPARATOR);
    
    if (file_exists($this->content_path . DIRECTORY_SEPARATOR . $path)) {
      return $this->content_path . DIRECTORY_SEPARATOR . $path;
    }
    else {
      return FALSE;
    }
      
  }
  
	// --------------------------------------------------------------	
   
	/**
	 * Recursive directory to scan pages and return objects 
	 * 
	 * @param string $path  Directory (system path) If NULL, uses $this->content_dir
	 */
	private function scan_content_directory($path = NULL)
	{
		$pagelist = array();
		
		foreach(scandir($this->content_path . DIRECTORY_SEPARATOR . $path) as $file)
		{
			$filepath = $this->content_path . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $file;
			
			//We're only interested in directories
			if ( ! is_dir($filepath))
				continue;
			
			//Ignore hidden files
			if ($file{0} == '.')
				continue;
			
			//See if it's a page
			try {
				$item = $this->load_content_object($filepath, self::FILEPATH);
			} catch (Exception $e) {
				$item = FALSE;
			}
			
			//If so, add it, and see what's underneath
			if ($item)
			{			
				$pagelist[$item->path] = $item->title;
				$pagelist = array_merge($pagelist, $this->scan_content_directory($path . DIRECTORY_SEPARATOR . $file));
			}
		}
		
		return $pagelist;
	}  
  
}

/* EOF: Mapper.php */