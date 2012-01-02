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
	
	// --------------------------------------------------------------

	/**
	 * Constructor
	 * 
	 * @param string $uri 
	 * @param string $basepath
	 */
	public function __construct($basepath = '.')
	{
		if ( ! is_readable(realpath($basepath)))
			throw new RuntimeException("Basepath $basepath not readable or does not exist");
		
		$this->page_basepath = realpath($basepath);		
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
	
	//THIS WILL JUST RETURN AN ARRAY AND PASS THE ITEMS TO A (NEW) TEMPLATE CLASS
	public function load_page($uri_path)
	{
		$path = $this->resolve_uri_to_path($uri_path);
		
		//Determine what type of page it is
		// - node if it contains node_meta.json
		// - list if it contains list_meta.json
		// - basic page if contains nothing
		
		//Return an output object with the HTML content of the file and the meta info
	}
	
	// --------------------------------------------------------------
	
	private function resolve_uri_to_path($uri)
	{
		$path = ($uri == '/' OR empty($uri)) ? '_front' : $uri;			
		return $this->page_basepath . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR;
	}

}


/* EOF: router.php */