<?php

class Cache_file implements Cache_interface
{
	private $cache_dir;
	
	// --------------------------------------------------------------

	public function __construct($cache_dir = 'DEFAULT')
	{
		if ($cache_dir == 'DEFAULT')
			$cache_dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'gh_temp_cache' . DIRECTORY_SEPARATOR;
		
		if ( ! is_dir($cache_dir) && ! mkdir($cache_dir))
			throw new Exception("Could not find or auto-create cache directory: $cache_dir");
					
		if ( ! is_writable($cache_dir))
			throw new Exception("Cannot write to cache directory ($cache_dir) for file cache");
		
		$this->cache_dir = $cache_dir;
	}
	
	// --------------------------------------------------------------

	public function create_cache_item($key, $output)
	{
		return file_put_contents($this->cache_dir . $key . '.cache', $output);
	}
	
	// --------------------------------------------------------------

	public function retrieve_cache_item($key)
	{
		if (is_readable($this->cache_dir . $key . '.cache'))
			return file_get_contents($this->cache_dir . $key . '.cache');
		else
			return FALSE;
	}
	
	// --------------------------------------------------------------
	
	public function clear_cache($key = NULL)
	{
		$files_to_delete = array();
		$failed_deletes = array();
		
		//Either we delete only one based on the key or scan the entire directory
		//for all cache files
		if ( ! is_null($key))
			$files_to_delete[] = $this->cache_dir . $key . '.cache'; 
		else
		{
			foreach(scandir($this->cache_dir) as $file)
			{
				if (substr($file, strlen($file) - strlen('.cache')) == '.cache')
					$files_to_delete[] = $this->cache_dir . $file;
			}
		}
		
		//Delete the file(s)
		foreach($files_to_delete as $file)
		{
			if ( ! unlink($file))
				$failed_deletes[] = $file;
		}
			
		//Return the result
		if (count($failed_deletes) > 0)
			throw new Exception("Could not delete the following cache files: " . implode("\n", $failed_deletes));
		
		return TRUE;
	}	
	
	// --------------------------------------------------------------

	public function check_expired($key, $seconds)
	{
		$file = $this->cache_dir . $key . '.cache';
		$created_time = filemtime($file);
		
		return (time() - $created_time > $seconds);
	}
}

/* EOF: cache_file.php */