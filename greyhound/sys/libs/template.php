<?php

class Template
{
	private $template_basepath;
	private $template;
	
	public function __construct($basepath = '.', $template = 'default')
	{
		if ( ! is_readable(realpath($basepath)))
			throw new RuntimeException("Basepath $basepath not readable or does not exist");
		
		$this->template_basepath = realpath($basepath) . DIRECTORY_SEPARATOR;
		
		if ( ! is_readable($this->template_basepath . $template . DIRECTORY_SEPARATOR . 'index.php'))
			throw new Exception("Template $template is not readable! Check template name.");
	}
	
	// --------------------------------------------------------------
	
	
	
}

/* EOF: template.php */