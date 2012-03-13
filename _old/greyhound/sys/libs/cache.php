<?php

class Cache
{	
	//Settings
	private $cache_method;
	private $cache_expire;
	private $cache_clearcache;
	private $fail_gracefully;
	
	//Depencies
	private $uri;

	//Private variables
	private $cache_obj;
	private $cache_key;
	
	// --------------------------------------------------------------

	/**
	 * Constructor 
	 * 
	 * @param Config $config
	 * @param Uri $uri
	 */
	public function __construct(Config $config, Uri $uri)
	{
		//External Dependencies
		$this->uri = $uri;
		
		//Load interface
		require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache_methods' . DIRECTORY_SEPARATOR . 'cache_interface.php');
		
		//Set config
		$config = $config->get_item('cache') ?: array();
		$this->cache_method = (isset($config['method'])) ? $config['method'] : FALSE;
		$this->cache_expire = (isset($config['expire'])) ? $config['expire'] : 7200;
		$this->cache_clearcache = (isset($config['clearcache'])) ? $config['clearcache'] : FALSE;
		$this->fail_gracefully = (isset($config['fail_gracefully'])) ? $config['fail_gracefully'] : TRUE;
		
		//If method != NULL, attempt to load it
		if ($this->cache_method)
		{
			try {

				$this->cache_obj = $this->factory($this->cache_method);
				
			} catch (Exception $e) {
				if ( ! $this->fail_gracefully)
				{
					$this->cache_method = FALSE;
					throw $e;
				}
			}
		}
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Check and return cache content, if it exists.
	 * 
	 * Returns FALSE if no cache items exist
	 * 
	 * @param $check_expired
	 * If TRUE, will clear the cache item and return FALSE if the cache expired
	 */
	public function retrieve_cache_version($check_expired = TRUE)
	{
		if ($this->cache_method)
		{
			$key = $this->get_cache_key_from_uri();
			
			$cache_item = $this->cache_obj->retrieve_cache_item($key);
			
			//Check expired
			if ($cache_item && $check_expired && $this->cache_obj->check_expired($key, $this->cache_expire))
			{
				$this->cache_obj->clear_cache($key);
				return FALSE;
			}
			else
				return $cache_item;
		}
		else
			return FALSE;
	}
	
	// --------------------------------------------------------------

	public function create_cache_version($content)
	{
		if ($this->cache_method)
		{
			$key = $this->get_cache_key_from_uri();
			return $this->cache_obj->create_cache_item($key, $content);
		}
		else
			return FALSE;
	}
	
	// --------------------------------------------------------------

	private function get_cache_key_from_uri()
	{
		$url = $this->uri->get_path_string();
		
		$q = $this->uri->get_query();
		if ($q)
			$url .= "?" . $q;
		
		return md5($url);
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Private factory method for loading cache based on driver
	 * 
	 * @param string $cache_driver
	 * @return class 
	 */
	private function factory($cache_driver)
	{
		if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache_methods' . DIRECTORY_SEPARATOR . 'cache_' . $cache_driver . '.php'))
		{
			require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache_methods' . DIRECTORY_SEPARATOR . 'cache_' . $cache_driver . '.php');
			$class = 'Cache_' . $cache_driver;
			return new $class;
		}
		else
			throw new Exception("Cache driver '$cache_driver' not found");
	}
}

/* EOF: cache.php */