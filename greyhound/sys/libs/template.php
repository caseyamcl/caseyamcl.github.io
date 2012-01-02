<?php

class Template
{
	private $template_basepath;
	private $template;
	private $template_path;
	
	public function __construct($basepath = '.', $template = 'default')
	{
		if ( ! is_readable(realpath($basepath)))
			throw new RuntimeException("Basepath $basepath not readable or does not exist");
		
		$this->template_basepath = realpath($basepath) . DIRECTORY_SEPARATOR;
		
		if ( ! is_readable($this->template_basepath . $template . DIRECTORY_SEPARATOR . 'main.php'))
			throw new Exception("Template $template is not readable! Check template name.");

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
		
		//If there is a template for the page type, use that.. Otherwise, just
		//echo the page_obj->content
		if (is_readable($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . $page_obj->page_type . '.php'))
			$tpl_file = realpath($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . $page_obj->page_type . '.php');
		elseif (is_readable($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . 'default.php'))
			$tpl_file = realpath($this->template_path . 'layouts' . DIRECTORY_SEPARATOR . 'default.php');
		
		//Render the layout
		if (isset($tpl_file))
			$page_content = $this->render($tpl_file, $page_obj->content, $page_obj);
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
		
		//Render the output and return it
		ob_start();
		require($fullpath);
		return ob_get_clean();
	}
}

/* EOF: template.php */