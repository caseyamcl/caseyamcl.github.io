<?php

namespace Requesty;

/**
 * HTTP Client Class
 * 
 * @author Casey McLaughlin
 */
class Request
{
	/**
	 * @var Browscap $browscap
	 */
	private $browscap;
	
	// --------------------------------------------------------------		

	/**
	 * Constructor
	 * 
	 * @param Browscap $browscap 
	 */
	public function __construct(Browscap $browscap)
	{
		//Load dependencies
		$this->browscap = $browscap;
	}
	
	// --------------------------------------------------------------		

	/**
	 * Get the name of the browser
	 * 
	 * @return string 
	 */
	public function get_browser()
	{
		return $this->get_user_agent()->Browser;
	}

	// --------------------------------------------------------------		
	
	/**
	 * Get the client's IP address
	 * 
	 * @return string
	 */
	public function get_ip_address()
	{
		return $_SERVER['REMOTE_ADDR'];
	}

	// --------------------------------------------------------------		
	
	/**
	 * Get the client's browser (or other HTTP client) version
	 * 
	 * @return float
	 */
	public function get_version()
	{
		return (float) $this->get_user_agent()->Version;
	}

	// --------------------------------------------------------------		
	
	/**
	 * Get the user agent
	 * 
	 * @param boolean $raw 
	 * If TRUE, will return the raw user agent
	 * 
	 * @return string|array|object
	 * Return an object or array of key/value pairs if $raw = FALSE, otherwise a string
	 */
	public function get_user_agent($raw = FALSE, $as_array = TRUE)
	{		
		return ($raw) ? $_SERVER['HTTP_USER_AGENT'] : $this->browscap->getBrowser(NULL, $as_array);
	}
	
	// --------------------------------------------------------------		

	/**
	 * Returns TRUE if is CLI, FALSE if otherwise
	 * 
	 * @return boolean
	 */
	public function is_cli()
	{
		return (php_sapi_name() == 'cli');
	}
	
	// --------------------------------------------------------------		

	/**
	 * Returns TRUE if is an AJAX request, FALSE if otherwise
	 * 
	 * @return boolean
	 */
	public function is_ajax()
	{
		return ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');		
	}
	
	// --------------------------------------------------------------		

	/**
	 * Return an array of preferred languages from the client
	 * 
	 * @param boolean $include_weights
	 * @return array
	 */
	public function get_languages($include_weights = FALSE)
	{
		//Get accepted languages from HTTP header
		$langs = $this->_unserialize_header($_SERVER['HTTP_ACCEPT_LANGUAGE']);
				
		//Return them
		return ($include_weights) ? $languages : array_keys($languages);
	}
	
	// --------------------------------------------------------------

	/**
	 * Return an array of accepted encodings from the client
	 * 
	 * @return array
	 */
	public function get_accepted_encodings()
	{
		return $this->_unserialize_header($_SERVER['HTTP_ACCEPT_ENCODING']);
	}
	
	// --------------------------------------------------------------		

	/**
	 * Return an array of accepted charsets the client accepts
	 * 
	 * @param boolean $include_weights
	 * @return array
	 */
	public function get_accepted_charsets($include_weights = FALSE)
	{
		$charsets = $this->_unserialize_header($_SERVER['HTTP_ACCEPT_CHARSET']);
		
		return ($include_weights) ? $charsets : array_keys($charsets);
	}
	
	// --------------------------------------------------------------		

	/**
	 * Return an array of accepted types the client accepts
	 * 
	 * @param boolean $include_weights
	 * @return array
	 */
	public function get_accepted_types($include_weights = FALSE)
	{
		$types = $this->_unserialize_header($_SERVER['HTTP_ACCEPT']);
		
		return ($include_weights) ? $types : array_keys($types);
	}

	// --------------------------------------------------------------		

	/**
	 * Unserialize a Header
	 * 
	 * @param string $header 
	 * @return array
	 */
	private function _unserialize_header($header)
	{
		$items = array_map('trim', explode(',', $header));
		
		//Output
		$output = array();
		
		foreach($items as $item)
		{
			if (strpos(';', $item) !== FALSE)
				list($val, $weight) = array_map('trim', explode(';', $item));
			else
				list($val, $weight) = array($item, 'q=1');
			
			$weight = substr($weight, 2); //pop 'q=' off the front of the quality
			
			$output[$val] = $weight;
		}

		//Sort desc by weight
		asort($output);
		$output = array_reverse($output);
		
		return $output;
	}
}

/* EOF: client.php */