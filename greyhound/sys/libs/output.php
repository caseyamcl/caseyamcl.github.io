<?php

/**
 * HTTP Output Class
 */
class Output
{
	private $http_headers = array();
	private $http_status_code = 200;
	private $http_status_text = 'OK';
	private $http_content_type = 'text/html';
	private $output_content = NULL;
	
	// --------------------------------------------------------------		
	
	/**
	 * Set HTTP Status Header
	 *
	 * License issues (came from CIv2.1.0)?
	 * 
	 * @access	public
	 * @param	int		the status code
	 * @param	string
	 * @return	void
	 * @link https://github.com/EllisLab/CodeIgniter/blob/develop/system/core/Common.php
	 */
	public function set_http_status($code = 200, $text = '')
	{
		$stati = array(
			200	=> 'OK',
			201	=> 'Created',
			202	=> 'Accepted',
			203	=> 'Non-Authoritative Information',
			204	=> 'No Content',
			205	=> 'Reset Content',
			206	=> 'Partial Content',

			300	=> 'Multiple Choices',
			301	=> 'Moved Permanently',
			302	=> 'Found',
			304	=> 'Not Modified',
			305	=> 'Use Proxy',
			307	=> 'Temporary Redirect',

			400	=> 'Bad Request',
			401	=> 'Unauthorized',
			403	=> 'Forbidden',
			404	=> 'Not Found',
			405	=> 'Method Not Allowed',
			406	=> 'Not Acceptable',
			407	=> 'Proxy Authentication Required',
			408	=> 'Request Timeout',
			409	=> 'Conflict',
			410	=> 'Gone',
			411	=> 'Length Required',
			412	=> 'Precondition Failed',
			413	=> 'Request Entity Too Large',
			414	=> 'Request-URI Too Long',
			415	=> 'Unsupported Media Type',
			416	=> 'Requested Range Not Satisfiable',
			417	=> 'Expectation Failed',
			422	=> 'Unprocessable Entity',

			500	=> 'Internal Server Error',
			501	=> 'Not Implemented',
			502	=> 'Bad Gateway',
			503	=> 'Service Unavailable',
			504	=> 'Gateway Timeout',
			505	=> 'HTTP Version Not Supported'
		);

		if ($code == '' OR ! is_numeric($code))
		{
			show_error('Status codes must be numeric', 500);
		}

		if (isset($stati[$code]) AND $text == '')
		{
			$text = $stati[$code];
		}

		if ($text == '')
		{
			throw new Exception('No status text available.  Please check your status code number or supply your own message text.', 500);
		}

		$this->http_status_code = $code;
		$this->http_status_text = $text;
	}
	
	// --------------------------------------------------------------		

	/**
	 * Set HTTP Content MIME Type
	 * 
	 * @param string $type 
	 */
	public function set_http_content_type($type)
	{
		$this->http_content_type = $type;
	}
	
	// --------------------------------------------------------------		
	
	/**
	 * Set a custom HTTP header
	 * 
	 * @param string $header_txt 
	 */
	public function set_http_header($header_txt)
	{
		$this->http_headers = $header_txt;
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Set Output
	 * 
	 * @param string $output 
	 * Typically HTML text, but can be any UTF-8 Text
	 */
	public function set_output($output)
	{
		$this->output_content = $output;
	}

	// --------------------------------------------------------------		

	/**
	 * GO - Generate the output
	 * 
	 * @param boolean $return
	 * If set to TRUE, will generate HTTP output but just return the output contents
	 * 
	 * @return null|string
	 * If $return is TRUE, this function will return the output string.  Otherwise NULL
	 */
	public function go($return = FALSE)
	{
		ob_start();
		
		//Output all of the HTTP headers
		$this->output_http_headers();
		
		//@TODO: Do I need to do this, or will Apache?
		//Also generate a header-length HTTP header
		//header('Content-Length: ' . $filesize);
		//header('X-Content-Length: ' . $filesize);
			
		echo $this->output_content;		
		
		if ($return)
		{
			$data = ob_get_contents();
			ob_end_clean();
			
			return $data;
		}
		else
			ob_end_flush();
	}
	
	// --------------------------------------------------------------		
	
	/**
	 * Output HTTP Headers
	 */
	public function output_http_headers()
	{
		//Set the status header
		$code = $this->http_status_code;
		$text = $this->http_status_text;
		
		$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

		if (substr(php_sapi_name(), 0, 3) == 'cgi')
			header("Status: {$code} {$text}", TRUE);
		elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
			header($server_protocol." {$code} {$text}", TRUE, $code);
		else
			header("HTTP/1.1 {$code} {$text}", TRUE, $code);		
			
		//Output content type header
		
			
		//Output custom headers
		foreach($this->http_headers as $header)
			header($header);
	}	

}

/* EOF: output.php */