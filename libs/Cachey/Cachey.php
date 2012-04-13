<?php

namespace Cachey;

class Cachey_Exception extends \Exception { /* ... */ }


class Cachey {

  // --------------------------------------------------------------		

  /**
   * Optional static factory method
   * 
   * Calls $this->get_driver;
   * 
   * @param string $cache_driver
   * @param array $options
   */
  public static function factory($cache_driver, $options = array()) {
    
    $that = new Cachey;
    return $that->get_driver($cache_driver, $options);
  } 
  
  // --------------------------------------------------------------		

  public function get_driver($cache_driver, $options = array()) {
    
    $ds = DIRECTORY_SEPARATOR;
    $filename = __DIR__ . $ds . 'Drivers' . $ds . ucfirst($cache_driver) . '.php';
    
    if (include_once($filename)) {
      $classname = 'Cachey\\Drivers\\' . ucfirst($cache_driver);      
      return new $classname($options);
    }
    else {
      throw new \RuntimeException("The cache driver $cache_driver does not exist!");
    }
    
  }
  
  // --------------------------------------------------------------		

  /**
   * Get a list of available drivers and their associated classnames
   * 
   * @return array
   */
  public function get_available_drivers($include_classnames = FALSE) {
    
    return ($include_classnames) ? $this->read_drivers() : array_keys($this->read_drivers());
    
  }
 
  // --------------------------------------------------------------		

  private function read_drivers() {
 
    $drivers = array();
    $driver_dir = __DIR__ . DIRECTORY_SEPARATOR . 'Drivers' . DIRECTORY_SEPARATOR;
    
    //Get all of the outputters that are registered
    foreach(scandir($driver_dir) as $file) {
            
      //Skip hiddens and Outputter.php
      if ($file{0} == '.' OR $file == 'Cache_driver.php')
        continue;
      
      //If it is an actual outputter
      $class_file = $driver_dir . $file;
            
      if (is_readable($class_file)) {
        $classname = 'Cachey\\Drivers\\' . basename($file, '.php');
        $drivers[$class_file] = $classname;
      }
    }
    
    return $drivers;
  }  
  
}

/* EOF: Cachey.php */