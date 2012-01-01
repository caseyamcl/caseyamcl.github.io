<?php

interface Cache_interface
{
	/**
	 * Create Cache
	 * 
	 * @param string $key
	 * A unique key for the cache item
	 * 
	 * @param string $output
	 * What to store in the cache item
	 * 
	 * @return boolean
	 * TRUE if success
	 */
	public function create_cache_item($key, $output);
	
	/**
	 * Retrieve cache item
	 * 
	 * @param string $key
	 * 
	 * @return string
	 * Cache output
	 */
	public function retrieve_cache_item($key);
	
	/**
	 * Clear a cache item or the entire cache
	 * 
	 * @param string $key
	 * If NULL, then clear all caches
	 * 
	 * @return boolean
	 * TRUE if success
	 */
	public function clear_cache($key = NULL);
	
	/**
	 * Check to see if a cache item has expired, based on the number
	 * of seconds to keep it in the cache
	 * 
	 * @param string $key
	 * 
	 * @param int $seconds
	 * The number of seconds to keep the item in the cache before expiring
	 * 
	 * @return boolean
	 * TRUE if the cache has expired, FALSE if the cache is still good or no cache item exists
	 */
	public function check_expired($key, $seconds);
}

/* EOF: _cache_interface.php */