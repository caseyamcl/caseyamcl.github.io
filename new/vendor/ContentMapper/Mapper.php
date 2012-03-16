<?php

namespace ContentMapper;

class Mapper {

  const FILEPATH = 1;
  const URLPATH = 2;
  
  /**
   * Content Folder
   * @var string
   */
  private $content_path;
	
	// --------------------------------------------------------------	  
  
  /**
   * @param string $content_folder 
   */
  public function __construct($content_path) {
    
    //Remove trailing slash if it exists
    $content_path = rtrim($content_path, DIRECTORY_SEPARATOR);
    
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
   * @param string $subfolder 
   */
  public function get_sitemap($subdir = '') {

    var_dump($this->scan_content_directory());
    
  }
	
	// --------------------------------------------------------------	
  
  /**
   * Load a page object for a URL or filepath
   * 
   * @param string $path  URL or filepath of object
   * @param int $type     Type of path sent (self::FILEPATH or self::URLPATH)
   */
  public function load_content_object($path, $type = self::URLPATH) {
    
    if ($type == self::URLPATH) {
      $path = $this->map_url_to_filepath($path);
    }
    
    return new Content_item($path);
  }

	// --------------------------------------------------------------	
  
  /**
   * Map a URL path (e.g some/path) to a content item
   * 
   * @param string $path
   * @return boolean|string  FALSE if the content does not exist, the realpath if it does
   */
  public function map_urlpath_to_filepath($urlpath) {
    
    $path = ltrim(str_replace('/', DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
    
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
	 * @param string $path  If NULL, uses $this->content_dir
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
				$pagelist = array_merge($pagelist, $this->scan_page_directory($filepath));
			}
		}
		
		return $pagelist;
	}  
  
}

/* EOF: Mapper.php */