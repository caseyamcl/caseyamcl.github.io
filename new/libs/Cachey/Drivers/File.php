<?php

namespace Cachey\Drivers;

class File extends Cache_driver {
  
  // --------------------------------------------------------------		
  
  public function set_option($optname, $optval) {
    
    if ( ! in_array($optname, array('filepath', 'default_expiration'))) {
      throw new \Cachey\Cachey_Exception("invalid option: $optname");
    }
    
    return parent::set_option($optname, $optval);    
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
    $fp = $this->get_cache_filepath() . md5($key) . '.cache';
    return @file_put_contents($fp, $this->encode_filecontents($expire, $data));    
  }
  
  // --------------------------------------------------------------		
  
  /**
   * Retrieve a cached version
   * @param string $key
   * @return string|boolean
   */
  public function retrieve_cache_item($key) {
    
    $fp = $this->get_cache_filepath() . md5($key) . '.cache';
    
    $result = $this->decode_filecontents(@file_get_contents($fp));

    if ($result && $result[0] >= time()) {
      return $result[1];      
    }
    else {
      $this->clear_cache($key);
      return FALSE;
    }
  }
  
  // --------------------------------------------------------------		

  public function check_cache_item_exists($key) {   
    return ($this->retrieve_cache_item($key)) ? TRUE : FALSE;
  }
  
  // --------------------------------------------------------------		
    
  public function clear_cache($key = NULL) {
    
    $fp = $this->get_cache_filepath();
    
    if ($key) {
      return @unlink($fp . md5($key) . '.cache');
    }
    else {
      
      $failed_files = array();
      
      foreach(scandir($fp) as $file) {
        
        if (substr($file, -6) == '.cache') {
                    
          if ( ! @unlink($fp . $file)) {
            $failed_files[] = $file;
          }
        }
        
      }
     
      if (count($failed_files == 0)) {
        return TRUE;
      }
      else {
        throw new \Cachey\Cachey_Exception("Error deleting the following cache files: " . implode("; ", $failed_files));
      }
    }
    
  }
  
  // --------------------------------------------------------------		
  
  private function get_cache_filepath() {
    
    if ( ! isset($this->options['filepath'])) {
      throw new \Cachey\Cachey_Exception("No filepath set for file cache!  Use set_option('filepath') to set it!");
    }
    
    $fp = $this->options['filepath'];
    
    if ( ! is_writable($fp)) {
      throw new \Cachey\Cachey_Exception("The filepath does not exist or is not writable for caching: $fp");
    }
    
    return realpath($fp) . DIRECTORY_SEPARATOR;
  }
  
  // --------------------------------------------------------------		

  /**
   * Encode cache file contents
   * 
   * @param int $expiration_timestamp
   * @param string $file_content
   * @return string
   */
  private function encode_filecontents($expiration_timestamp, $file_content) {
    return $expiration_timestamp . "\n" . base64_encode($file_content);    
  }
  
  // --------------------------------------------------------------		

  /**
   * Returns an array with the expiration timestamp and the contents, or FALSE
   * 
   * @param string $raw_file_content
   * @return array|boolean  (FALSE if file was non-readable)
   * @throws Cachey\Cachey_Exception 
   */
  private function decode_filecontents($raw_file_content) {

    if ($raw_file_content === FALSE) {
      return $raw_file_content;
    }

    $ret = explode("\n", $raw_file_content, 2);
    
    if (count($ret) < 2 OR ! is_numeric($ret[0])) {
      throw new Cachey\Cachey_Exception("Malformed file content.  Cannot decode");
    }
    
    $ret[1] = base64_decode($ret[1]);
    
    return $ret;
  }
}

/* EOF: File.php */