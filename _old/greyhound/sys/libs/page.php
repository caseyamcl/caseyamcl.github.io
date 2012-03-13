<?php

/**
 * Page Class
 */
class Page {
	
	/**
	 * The type of page
	 * 
	 * Is either 'basic_page' or a type of post
	 * 
	 * @var string
	 */
	public $page_type;
	
	/**
	 * An object containing all meta information in the page
	 * @var object
	 */
	public $page_meta;
	
	/**
	 * Main Content File
	 * @var string
	 */
	public $content_file;	

	/**
	 * An array continaing auxiliary files for the page
	 * @var array
	 */
	public $files = array();
	
	/**
	 * Page path (relative to the root of the pages directory / URL of index.php)
	 * @var string 
	 */
	public $page_path;
}

/* EOF: page.class.php */