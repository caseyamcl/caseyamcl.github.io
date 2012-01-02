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
	 * Main Content
	 * @var string
	 */
	public $content;	

	/**
	 * An array continaing auxiliary files for the page
	 * @var array
	 */
	public $files = array();
}

/* EOF: page.class.php */