<?php

/**
 * Autoloader Class
 *
 * Manages auto-loading of classes in PHP so we don't have to bother ourselves
 * with a whole bunch of includes() and requires(). :)
 *
 * Singleton
 * Based on: http://www.php.net/manual/en/language.oop5.autoload.php#101190
 *
 * @author Casey McLaughlin
 * @link http://bitbucket.org/caseyamcl/snippets
 */
class Autoloader
{
	public static $instance;
	private $paths = array();
	private $exts = array('.php', '.class.php', '.lib.php');

	/* initialize the autoloader class */
	public static function init($basepath = 'scripts')
	{
		if (self::$instance == NULL)
			self::$instance = new self($basepath);

		return self::$instance;
	}

	// --------------------------------------------------------------

	/* put the custom functions in the autoload register when the class is initialized */
	private function __construct($basepath = 'scripts')
	{
		$this->paths = $this->detect_paths($basepath);
		spl_autoload_register(array($this, 'clean'));
		spl_autoload_register(array($this, 'dirty'));
	}

	// --------------------------------------------------------------

	/* the clean method to autoload the class without any includes, works in most cases */
	private function clean($class)
	{
		global $docroot;
	
		spl_autoload_extensions(implode(',', $this->exts));

		foreach($this->paths as $resource)
			set_include_path($docroot . $resource);
		
		spl_autoload($class);
	}

	// --------------------------------------------------------------

	/* the dirty method to autoload the class after including the php file containing the class */
	private function dirty($class)
	{		
		foreach($this->paths as $resource)
		{
			foreach($this->exts as $ext)
			{
				if (is_readable($resource . $class . $ext))
					@include($resource . $class . $ext);
			}
		}
		
		spl_autoload($class);
	}
	
	// --------------------------------------------------------------

	/**
	 * Detects all of the subdirectories in the supplied paths
	 * so that we can autolaod from them, too. Recursive.
	 *
	 * @param mixed $basepath String or Array
	 * @return array
	 */
	private function detect_paths($basepath)
	{
		if ( ! is_array($basepath))
			$basepath = array($basepath);

		$out_array = array();

		foreach($basepath as $bp)
		{
			if ( ! is_readable($bp))
				continue;

			if (substr($bp, strlen($bp)-1) != '/')
				$bp .= '/';

			$out_array[] = $bp;
			foreach(scandir($bp) as $item)
			{
				if ($item != '.' && $item != '..' && is_dir($item))
					$out_array = array_merge($out_array, $this->detect_paths($bp . $item));
			}
		}

		return $out_array;
	}

}

/* EOF: autoloader.php */