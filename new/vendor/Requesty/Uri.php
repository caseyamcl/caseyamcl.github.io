<?php

namespace Requesty;

/**
 * URI Class
 *
 * Detects the current URI as best as possible, and returns information
 * about it.  Useful for MVC frameworks, or any other URI-driven routing-based
 * application.
 *
 * @author Casey McLaughlin
 * @link http://bitbucket.org/caseyamcl/snippets
 */
class Uri
{
	private $protocol;
	private $port;
	private $host_name;
	private $host_ip;
	private $base_path;
	private $script_name;
	private $path_array;
	private $query_info;

	// --------------------------------------------------------------

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->detect_info();
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Is HTTPS
	 * 
	 * @return boolean
	 */
	public function is_https()
	{
		return ($this->protocol == 'https');
	}
	
	// --------------------------------------------------------------

	/**
	 * Get a specific item from the get query
	 * 
	 * @param string $key
	 * @return string
	 */
	public function get_query_item($key)
	{
		return (isset($this->query_info[$key])) ? $this->query_info[$key] : FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Get query string as an associative array
	 * 
	 * @return array
	 */
	public function get_query_array()
	{
		return $this->query_info;
	}

	// --------------------------------------------------------------

	/**
	 * Get query string as a string
	 * 
	 * @return string
	 */
	public function get_query()
	{
		$out_items = array();
		foreach($this->query_info as $k => $v)
			$out_items[] = "$k=$v";

		return implode('&', $out_items);
	}

	// --------------------------------------------------------------

	/**
	 * Returns a single segment from the routing path or FALSE if it doesn't exist
	 *
	 * @param int $i
	 * @return mixed
	 */
	public function get_segment($i = 0)
	{
		return (isset($this->path_array[$i])) ? $this->path_array[$i] : FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Returns the routing path as an array
	 * 
	 * @return array
	 */
	public function get_path_segments()
	{
		return $this->path_array;
	}

	// --------------------------------------------------------------

	/**
	 * Returns the routing path as a string
	 *
	 * @return string
	 */
	public function get_path_string()
	{
		return implode('/', $this->path_array);
	}


	// --------------------------------------------------------------

	/**
	 * Returns the current URL
	 *
	 * @param boolean $include_query_string
	 * @return string
	 */
	public function get_current_url($include_query_string = TRUE)
	{
		$curr_url = $this->get_base_url() . implode('/', $this->path_array);

		if ($include_query_string && count($this->query_info) > 0)
		{
			//Remove the trailing slash
			$curr_url = substr($curr_url, 0, strlen($curr_url)-1);

			//Append the query string
			$curr_url .= '?' . $this->get_query();
		}

		return $curr_url;
	}

	// --------------------------------------------------------------

	/**
	 * Get the base URL without the script file
	 * 
	 * This is useful for including axuiliary files (CSS, JS, etc)
	 * 
	 * @return string
	 */
	public function get_base_url_path()
	{
		return $this->protocol . '://' . $this->reduce_double_slashes($this->host_name . '/' . $this->base_path . '/');
	}
	
	// --------------------------------------------------------------

	/**
	 * Get the base URL (omits the index.php file if it is not in-use)
	 *
	 * @return string
	 */
	public function get_base_url()
	{
		if (($this->protocol == 'https' && $this->port != 443) OR ($this->protocol == 'http' && $this->port != 80))
			$port = ':' . $this->port;
		else
			$port = '';

		return $this->protocol . '://' . $this->reduce_double_slashes($this->host_name . '/' . $this->base_path . '/' . $this->script_name . '/');
	}

	// --------------------------------------------------------------

	/**
	 * Attempt to automaticlly detect URL info the index.php file using $_SERVER data
	 *
	 * Tested In: Apache 2.2
	 * @TODO: Check if this works in NGINX, IIS6, and Lighttpd
	 *
	 * @param boolean $omit_index_php
	 */
	private function detect_info()
	{
		//Only run this once
		if ( ! empty($this->host_ip))		
			return;
		
		//Get the protocol and port
		$this->port = $_SERVER['SERVER_PORT'];
		$this->protocol = ($this->port == 443 || ( ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) ? 'https' : 'http';

		//Get the server name
		$this->host_name = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
		$this->host_ip = $_SERVER['SERVER_ADDR'];

		//Get the base URL path & script name
		$script_name = basename($_SERVER['SCRIPT_FILENAME']);
		$this->base_path = str_replace($script_name, '', $_SERVER['SCRIPT_NAME']);

		//set the script name.
		if (strpos($_SERVER['REQUEST_URI'], $script_name) !== FALSE)
			$this->script_name = $script_name;
		else
			$this->script_name = '';

		//Set the request_uri
		if (strpos($_SERVER['REQUEST_URI'], '?') !== FALSE)
		{
			list($request_uri, $get_query) = explode('?', $_SERVER['REQUEST_URI'], 2);
			$this->query_info = $this->parse_query($get_query);
		}
		else
		{
			$request_uri = $_SERVER['REQUEST_URI'];
			$this->query_info = array();
		}

		//Get the PATH.. Use PATH_INFO if possible
		$this->path_array = array();
		if (isset($_SERVER['PATH_INFO']) && ! empty($_SERVER['PATH_INFO']))
			$path_info = $_SERVER['PATH_INFO'];
		else
			$path_info = substr($request_uri, strlen($this->base_path . $this->script_name));
		if ( ! empty($path_info))
		{
			$arr = array_values(array_filter(explode('/', $path_info)));
			for($i = 0; $i < count($arr); $i++)
				$this->path_array[($i+1)] = $arr[$i];
		}

	}
	
	// --------------------------------------------------------------

	/**
	 * Reduces all sets of double slashes down to a single slash
	 *
	 * @param string $str
	 * @return string
	 */
	private function reduce_double_slashes($str)
	{
		while(strpos($str, '//') !== FALSE)
			$str = str_replace('//', '/', $str);
		
		return $str;
	}
	
	// --------------------------------------------------------------

	/**
	 * Converts a GET Query to an associative array
	 *
	 * Returns an empty array if no get query, or empty string
	 *
	 * @param string $str
	 * @return array
	 */
	private function parse_query($str)
	{
		$out_array = array();
		
		foreach(explode('&', $str) as $item)
		{
			if (strpos($item, '='))
			{
				list($key, $val) = explode('=', $item, 2);
				$out_array[$key] = $val;
			}
		}
		
		return $out_array;
	}
}

/* EOF: uri.php */