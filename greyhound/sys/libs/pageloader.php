<?php

/**
 * Pageloader Class
 *
 */
class Pageloader
{	
	/**
	 * Basepath to the page files on the system
	 * @var string
	 */
	private $page_basepath;
		
	/**
	 * Config object
	 * @var Config
	 */
	private $config;
		
	// --------------------------------------------------------------

	/**
	 * Constructor
	 * 
	 * @param Config $config
	 * The config object
	 * 
	 * @param string $base_path
	 * The directory, relative to main application script, where the pages are located
	 */
	public function __construct(Config $config, $base_path = 'pages')
	{
		//Path
		if ( ! is_readable(realpath($base_path)))
			throw new RuntimeException("Basepath $base_path not readable or does not exist");
		$this->page_basepath = realpath($base_path) . DIRECTORY_SEPARATOR;
		
		//Config
		$this->config = $config;
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Check page exists
	 * 
	 * @param string $uri_path
	 * URI path to page (path/to/page)
	 * 
	 * @return boolean
	 * TRUE if the URI resolves succesfully to a URL
	 */
	public function check_page_exists($uri_path)
	{
		$path = $this->resolve_uri_to_path($uri_path);
		
		return (is_readable($path . 'index.php'));
	}
	
	// --------------------------------------------------------------

	/**
	 * Load a page object
	 * 
	 * @param string $uri_path
	 * Path included in the URI
	 * 
	 * @return Page
	 */
	public function load_page($uri_path)
	{
		if ( ! $this->check_page_exists($uri_path))
			throw new RuntimeException ("Page with URI $uri_path does not exist!");
		
		$path = $this->resolve_uri_to_path($uri_path);

		//Setup an output Object
		$page_obj = new Page();
		$page_obj->content_file = realpath($path . 'index.php');
		$page_obj->page_meta = $this->get_page_meta($path);
		$page_obj->page_type = $page_obj->page_meta->page_type;
		$page_obj->page_path = ($uri_path == '/' OR $uri_path == '') ? '_front/' : $uri_path . '/';
		$page_obj->files = $this->get_page_auxilary_files($path);

		//Return output object
		return $page_obj;
	}
	
	// --------------------------------------------------------------

	/**
	 * Get page meta
	 * 
	 * @param string $path
	 * Full path to page directory
	 */
	private function get_page_meta($path)
	{		
		//If a meta file exists, read it
		if (is_readable($path . 'meta.json'))
		{
			$raw_meta = file_get_contents($path . 'meta.json');
			
			if (strlen(trim($raw_meta)) > 0)
			{
				$page_meta = json_decode(trim($raw_meta));
				
				if ( ! $page_obj->page_meta)
					throw new RuntimeException ("Malformed JSON for page at path $uri_path");						
			}
		}
		
		//If no meta file exists, just return an empty object
		if ( ! isset($page_meta))
			$page_meta = new stdClass();

		//Page type defined?
		if ( ! isset($page_meta->page_type))
			$page_meta->page_type = 'basic_page';
		
		$defined_meta_fields = $this->get_required_meta_fields($page_meta->page_type);
		
		foreach($defined_meta_fields as $key => &$item)
			$item = (isset($page_meta->$key)) ? $page_meta->$key : NULL;
				
		return (object) $defined_meta_fields;
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Get required meta fields from the configuration
	 * 
	 * @param string $page_type
	 * @return array
	 */
	private function get_required_meta_fields($page_type)
	{
		 $rm = $this->config->get_item('meta_fields');
		 
		 //page_type and title are built-in
		 $fields = array(
			'title' => 'Page Title',
			'page_type' => 'Page Type'
		 );
		 
		 //Go through the configured meta fields for all page types
		 foreach($rm as $key => $item)
		 {
			 if ( ! is_array($item))
				 $fields[$key] = $item;
		 }
		 
		 //if there are configured meta fields for this specific page type,
		 //include those too
		 if (isset($rm[$page_type]))
		 {
			 foreach($rm[$page_type] as $key => $item)
			 {
				 if ( ! is_array($item))
					 $fields[$key] = $item;
			 }
		 }
		 
		 return $fields;
	}
	
	// --------------------------------------------------------------

	/**
	 * Include a page and return the output
	 * 
	 * @param string $fullpath
	 */
	private function get_page_content($fullpath)
	{
		ob_start();		
		require($fullpath);
		$content = ob_get_clean();
		
		return $content;
	}
	
	// --------------------------------------------------------------	
	
	/**
	 * Returns all files associated with a page, sorted by filename
	 * 
	 * @param string $path
	 * Path to files
	 * 
	 * @param boolean $include_mainfiles
	 * If TRUE, index.php and meta.json will also be included
	 * 
	 * @return array
	 */
	private function get_page_auxilary_files($path, $include_mainfiles = FALSE)
	{
		$out_files = array();
		
		foreach($this->read_dir_to_array($path) as $file)
		{
			if ( ! $include_mainfiles && ($file == 'index.php' OR $file == 'meta.json'))
				continue;
						
			$out_files[pathinfo($file, PATHINFO_EXTENSION)][] = $file;
		}
		
		return $out_files;
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
			if ($file{0} == '.')
				continue;
			elseif (is_file($path . DIRECTORY_SEPARATOR . $file))
				$out_files[] = $file;
			else
				$out_files = array_merge($out_files, $this->read_dir_to_array($path . DIRECTORY_SEPARATOR . $file));
		}
		
		return $out_files;
	}
	
	// --------------------------------------------------------------	
	
	private function resolve_uri_to_path($uri)
	{
		$path = ($uri == '/' OR empty($uri)) ? '_front' : $uri;			
		return $this->page_basepath . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR;
	}

}


/* EOF: router.php */