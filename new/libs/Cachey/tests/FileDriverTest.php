<?php

require_once(__DIR__ . '/../Drivers/Cache_driver.php');
require_once(__DIR__ . '/../Drivers/File.php');

class FileDriverTest extends PHPUnit_Framework_TestCase {

  private $content_dir;

  // -----------------------------------------------------------
   
  public function setUp() {
    
    parent::setUp();
    
    $ds = DIRECTORY_SEPARATOR;
    $this->content_dir = sys_get_temp_dir() . $ds . 'cache_filedriver_test' . $ds;
    mkdir($this->content_dir);
    
  }
  
  // -----------------------------------------------------------
   
  public function tearDown() {
    
    $ds = DIRECTORY_SEPARATOR;
    
    //Remove files
    if (is_readable($this->content_dir)) {
      
      foreach(scandir($this->content_dir) as $item) { 
        if ($item != '.' && $item != '..') {
          unlink($this->content_dir . $item);
        }
      }
    }
    
    //Remove directory
    rmdir($this->content_dir = sys_get_temp_dir() . $ds . 'cache_filedriver_test' . $ds);
    parent::tearDown();
  }
  
  // -----------------------------------------------------------
  
  public function testCreateObjectSucceeds() {
    
    $obj = new Cachey\Drivers\File();
    $this->assertInstanceOf('\\Cachey\Drivers\\File', $obj);
    
  }
  
  // -----------------------------------------------------------
  
  public function testSetOptionThatDoesntExistThrowsException() {
    
  }
  
  // -----------------------------------------------------------
  
  public function testSetOptionThatDoesExistWorks() {
    
  }
  
  // -----------------------------------------------------------
  
  public function testCreateCacheItemWithoutSetFilepathThrowsException() {
    
    $obj = new Cachey\Drivers\File();
    
  }
  
  // -----------------------------------------------------------
  
  public function testCreateCacheItemWithBadFilepathThrowsException() {
    
  }
  
  // -----------------------------------------------------------
  
  public function testCreateCacheItemWithSetFilepathSucceeds() {
    
  }
  
  // -----------------------------------------------------------
  
  public function testRetrieveCacheItemSucceedsForCacheThatExists() {
    
  }
  
  // -----------------------------------------------------------

  public function testRetrieveCacheItemFailsForExpiredCacheItem() {
    
  }

  // -----------------------------------------------------------

  public function testClearCacheItemWithParamClearsOnlyOneItem() {
    
  } 
  
  // -----------------------------------------------------------

  public function testClearCacheItemWithNoParamClearsAllCache() {
    
  }
}

/* EOF: FileDriverTest.php */