<?php

/**
 * Template Class
 * 
 * A few rules:
 *  - The template folder can be anywhere in the system, but the URL must be
 *    'templates' directly under the base URL
 *  - Same with the pages folder
 * 
 */
class Template
{
	/**
	 * URL Class
	 * 
	 * @var Url
	 */
	private $uri;
	
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
	 * @param string $siteurl
	 * @param string $basepath
	 * @param string $template 
	 */
	public function __construct(Uri $uri, $basepath = '.', $template = 'default')
	{
		//Base URL and Site URL
		$this->uri = $uri;
		
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
	 * Render page content as a full HTML page, using the template
	 * 
	 * @param Page $page_obj 
	 * 
	 * @return string
	 * HTML output for the page
	 */
	public function render_main_template(Page $page_obj)
	{
		$page_content = $this->render_page_template($page_obj);
		
		//Render the main output
		$main_tpl_file = realpath($this->template_path . 'main.php');
		$output = $this->render($main_tpl_file, $page_content, $page_obj);
		
		//Embed any CSS and JS files into the output for this content
		$output = $this->add_auxiliary_file_links($output, $page_obj->page_path, $page_obj->files);
		
		//Return the output
		return $output;		
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Render page content, inside of its template file, if there is one
	 * 
	 * @param Page $page_obj 
	 * 
	 * @return string
	 * HTML output for the page
	 */
	public function render_page_template(Page $page_obj)
	{
		$page_content = $this->render_page_content($page_obj);
		
		//Check for a template file for this type of content
		if (is_readable($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . $page_obj->page_type . '.php'))
			$tpl_file = realpath($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . $page_obj->page_type . '.php');
		elseif (is_readable($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . 'default.php'))
			$tpl_file = realpath($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . 'default.php');
		
		//Render the content template file, if applicable
		if (isset($tpl_file))
			$page_content = $this->render($tpl_file, $page_content, $page_obj);

		return $page_content;
	}
	
	// --------------------------------------------------------------

	/**
	 * Render page content
	 * 
	 * @param Page $page_obj 
	 * 
	 * @return string
	 * HTML output for the page
	 */
	public function render_page_content(Page $page_obj)
	{
		//Render the inner content file first
		return $this->render($page_obj->content_file, NULL, $page_obj);		
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Render a specific file 
	 */
	private function render($fullpath, $page_content, $page_obj)
	{
		//Any variables in here will be available to our templates
		
		//Set the keys up as local variables for everything except the content itself
		//(that would be redundant)
		foreach($page_obj as $key => $val)
		{
			if ($key != 'content')
				$$key = $val;
		}
		
		//Create variables for URL paths
		$base_url     = $this->reduce_url_double_slashes($this->uri->get_base_url_path());
		$site_url     = $this->reduce_url_double_slashes($this->uri->get_base_url());
		$current_url  = $this->reduce_url_double_slashes($this->uri->get_current_url());
		$template_url = $this->reduce_url_double_slashes($base_url . 'templates/' . $this->template . '/');
		$page_path    = dirname($fullpath) . DIRECTORY_SEPARATOR;
		$page_url     = $this->reduce_url_double_slashes($base_url . 'pages/' . $page_obj->page_path . '/');
				
		//Render the output and return it
		ob_start();
		require($fullpath);
		return ob_get_clean();
	}
	
	// --------------------------------------------------------------

	private function add_auxiliary_file_links($content, $page_path, $filelist)
	{
		$to_add = array();
		
		$page_uri = $this->uri->get_base_url_path() . 'pages/' . $page_path;
		
		//Add CSS
		if (isset($filelist['css']))
		{
			foreach($filelist['css'] as $file)
				$to_add[] = "<link rel='stylesheet' type='text/css' href='{$page_uri}{$file}' />";
		}
		
		//Add JS
		if (isset($filelist['js']))
		{
			foreach($filelist['js'] as $file)
				$to_add[] = "<script type='text/javascript' src='{$page_uri}{$file}'></script>";
		}
				
		//Embed it...
		if (count($to_add) > 0)
		{
			$embed_str = "\n\t<!-- Embedded by Greyhound -->\n\t" . implode("\n\t", $to_add);			
			
			$content = str_replace('</head>', $embed_str . "\n" . '</head>', $content);
		}
		
		return $content;
	}
	
	private function reduce_url_double_slashes($str)
	{
		return preg_replace("#(^|[^:])//+#", "\\1/", $str);
	}
}

/* EOF: template.php */