<?php

/**
 * Template Class
 * 
 * A few rules:
 *  - The template folder can be anywhere in the system, but the URL must be
 *    'templates' directly under the base URL
 *  - Same with the pages folder
 * 
 * @TODO: Clean this up!
 * 
 */
class Template
{
	/**
	 * Base URL, with trailing slash
	 * @var string
	 */
	private $baseurl;
	
	/**
	 * Basepath to the template files directory
	 * @var string
	 */
	private $template_basepath;
	
	/**
	 * Name of the current template in use
	 * @var string
	 */
	private $template;
	
	/**
	 * Basepath to the current template file
	 * @var string
	 */
	private $template_path;
	
	// --------------------------------------------------------------
	
	/**
	 * Constructor
	 * 
	 * @param string $baseurl
	 * @param string $basepath
	 * @param string $template 
	 */
	public function __construct($baseurl, $basepath = '.', $template = 'default')
	{
		//Base URL
		$this->baseurl = $baseurl;
		
		//Basepath
		if ( ! is_readable(realpath($basepath)))
			throw new RuntimeException("Basepath $basepath not readable or does not exist");
		
		$this->template_basepath = realpath($basepath) . DIRECTORY_SEPARATOR;
		
		if ( ! is_readable($this->template_basepath . $template . DIRECTORY_SEPARATOR . 'main.php'))
			throw new Exception("Template $template is not readable! Check template name.");

		//Template name and template path
		$this->template = $template;
		$this->template_path = $this->template_basepath . $template . DIRECTORY_SEPARATOR;
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Render a page
	 * 
	 * @param Page $page_obj 
	 * 
	 * @return string
	 * HTML output for the page
	 */
	public function render_page(Page $page_obj)
	{
		//Work from the inside out
		$page_content = $this->render($page_obj->content_file, NULL, $page_obj);
		
		//If there is a template for the page type, use that.. Otherwise, just
		//echo the page_obj->content
		if (is_readable($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . $page_obj->page_type . '.php'))
			$tpl_file = realpath($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . $page_obj->page_type . '.php');
		elseif (is_readable($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . 'default.php'))
			$tpl_file = realpath($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . 'default.php');
		
		//Render the layout
		if (isset($tpl_file))
			$page_content = $this->render($tpl_file, $page_content, $page_obj);
		else
			$page_content = $page_obj->content;

		//@TODO: Include CSS and JS files, too
		
		//Render the main output
		$main_tpl_file = realpath($this->template_path . 'main.php');
		return $this->render($main_tpl_file, $page_content, $page_obj);
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Render a specific file 
	 */
	private function render($fullpath, $page_content, $page_obj)
	{
		//Set the keys up as local variables for everything except the content itself
		//(that would be redundant)
		foreach($page_obj as $key => $val)
		{
			if ($key != 'content')
				$$key = $val;
		}

		//Also create variables for URL paths
		$base_url = $this->reduce_url_double_slashes($this->baseurl . '/');
		$template_url = $this->reduce_url_double_slashes($this->baseurl . 'templates/' . $this->template . '/');
		$page_url = $this->reduce_url_double_slashes($this->baseurl . 'pages/' . $page_obj->page_path . '/');
		
		//Render the output and return it
		ob_start();
		require($fullpath);
		return ob_get_clean();
	}
	
	// --------------------------------------------------------------

	private function reduce_url_double_slashes($str)
	{
		return preg_replace("#(^|[^:])//+#", "\\1/", $str);
	}
}

/* EOF: template.php */