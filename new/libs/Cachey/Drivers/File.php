<?php

namespace Cachey\Drivers;

class File extends Cache_driver {
  
  // --------------------------------------------------------------		
  
  public function set_option($optname, $optval) {
    
    if ( ! in_array($optname, array('filepath', 'default_expiration'))) {
      throw new \RuntimeException("invalid option: $optname");
    }
    
    parent::set_option($optname, $optval);    
  }
  
  // --------------------------------------------------------------		
   
  /**
   * Create a cached version
   * @param string $key     Cache key (must be unique)
   * @param string $data    Data to cache
   * @param int $expire     Expiration, in seconds (defaults to 3600, or 1hr)
   * @return boolean
   * @throws Cachey\Cachey_Exception 
   */
  public function create_cache_item($key, $data, $expire = NULL) {
    
    $expire = time() + $this->compute_expiration($expire);
    $fp = $this->get_cache_filepath();
    
    if (check_cache_version_exists($key)) {
      throw new Cachey\Cachey_Exception("The cache item with key '$key' already exists!");
    }

    return @file_put_contents($fp . $key . '.cache', $data);    
  }
  
  // --------------------------------------------------------------		
  
  /**
   * Retrieve a cached version
   * @param string $key
   * @return string|boolean
   */
  public function retrieve_cache_item($key) {
    
    $fp = $this->get_cache_filepath();
    
    return @file_get_contents($fp . $key . '.cache');
  }
  
  // --------------------------------------------------------------		

  public function check_cache_item_exists($key) {
    
    $fp = $this->get_cache_filepath();
    return is_readable($fp . $key . '.cache');
    
  }
  
  // --------------------------------------------------------------		
    
  public function clear_cache($key = NULL) {
    
    $fp = $this->get_cache_filepath();
    
    if ($key) {
      
      if ($this->check_cache_version_exists($key)) {
        return @unlink($fp . $key . '.cache');
      }
      else {
        return FALSE;
      }
      
    }
    else {
      
      $failed_files = array();
      
      foreach(scandir($fp) as $file) {
        
        if (substr($file, -6) == '.cache') {
                    
          if (@unlink($fp . $file)) {
            $failed_files[] = $file;
          }
        }
        
      }
     
      if (count($failed_files == 0)) {
        return TRUE;
      }
      else {
        throw new Cachey\Cachey_Exception("Error deleting the following cache files: " . implode("; ", $failed_files));
      }
    }
    
  }
  
  // --------------------------------------------------------------		
  
  private function get_cache_filepath() {
    
    if ( ! isset($this->options['filepath'])) {
      throw new Cachey\Cachey_Exception("No filepath set for file cache!  Use set_option('filepath') to set it!");
    }
    
    $fp = $this->options['filepath'];
    
    if ( ! is_writable($fp)) {
      throw new Cachey\Cachey_Exception("The filepath does not exist or is not writable for caching: $fp");
    }
    
    return realpath($fp) . DIRECTORY_SEPARATOR;
  }
}

/* EOF: File.php */