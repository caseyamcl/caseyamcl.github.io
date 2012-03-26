<?php

namespace Cachey\Drivers;

abstract class Cache_driver {
  
  const DEFAULT_EXPIRATION = 3600;
  
  // --------------------------------------------------------------		
  
  private $options = array();
  
  // --------------------------------------------------------------		
  
  /**
   * Constructor
   * 
   * @param array $options 
   */
  public function __construct($options = array()) {
    
    foreach($options as $optname => $optval) {
      $this->set_option($optname, $optval);
    }
    
  }
  
  // --------------------------------------------------------------		
  
  /**
   * Set an option
   * @param string $optname
   * @param string $optval 
   */
  public function set_option($optname, $optval) {
    
    $this->options[$optname] = $optval;
  }
  
  // --------------------------------------------------------------		
 
  /**
   * Computes the expiration
   * 
   * @param int $exp
   * @return int
   * @throws Exception 
   */
  private function compute_expiration($exp) {
    
    if (is_null($exp)) {
      
      $exp (isset($this->options['default_expiration']))
        ? $this->options['default_expiration'] : self::DEFAULT_EXPIRATION;
    }
   
    $exp = (int) $exp;
     
      if ($exp < 1) {
        throw new \Cachey\Cachey_Exception("Cannot set a cache time to less than 1 second!");
      }
    
    return $exp;
  }
  
  // --------------------------------------------------------------		
    
  /**
   * Create a cached version
   * 
   * @param string $key
   * @param string $data
   * @param int $expire  Length of time cache should live
   * @return boolean
   */
  abstract public function create_cache_item($key, $data, $expire);
  
  /**
   * Retrieve cached version 
   * 
   * @param string $key
   * @return boolean|string  FALSE if no cached item, or cache expired
   */
  abstract public function retrieve_cache_item($key);
  
  /**
   * Check cache version exists
   * 
   * @param string $key
   * @return boolean 
   */
  abstract public function check_cache_item_exists($key);
  
  /**
   * Clear cache
   * 
   * If key provided, clear a single item.  Default: Clear all items
   * 
   * @param string $key
   * @return int  Cached items deleted
   */
  abstract public function clear_cache($key = NULL);
}

/* EOF: Cache_driver.php */