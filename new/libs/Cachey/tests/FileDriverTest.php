<?php

require_once(__DIR__ . '/../Drivers/Cachedriver.php');
require_once(__DIR__ . '/../Cachey.php');
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
    
    $obj = new Cachey\Drivers\File();
    
    try {
      $obj->set_option('foo', 'bar');
    } catch (Cachey\Cachey_Exception $e) {
      return;
    }
    
    $this->fail("An exception should have been thrown for non-existent option 'foo'");
    
  }
  
  // -----------------------------------------------------------
  
  public function testSetOptionThatDoesExistWorks() {
    
    $obj = new Cachey\Drivers\File();
    
    $this->assertNull($obj->set_option('filepath', $this->content_dir));
    $this->assertNull($obj->set_option('default_expiration', '1500'));
  }
  
  // -----------------------------------------------------------
  
  public function testCreateCacheItemWithoutSetFilepathThrowsException() {
    
    $obj = new Cachey\Drivers\File();
    
    try {
      $obj->create_cache_item('abc', '123');
    } catch (Cachey\Cachey_Exception $e) {
      return;
    }
    
    $this->fail("Creating a cache item should have thrown an Exception with no filepath set!");
  }
  
  // -----------------------------------------------------------
  
  public function testCreateCacheItemWithBadFilepathThrowsException() {
    
    $obj = new Cachey\Drivers\File();
    $obj->set_option('filepath', sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'does_not_exist');
    
    try {
      $obj->create_cache_item('abc', '123');
    } catch (Cachey\Cachey_Exception $e) {
      return;
    }
    
    $this->fail("Creating a cache item in a non-existent path should have failed!");
  }
  
  // -----------------------------------------------------------
  
  public function testCreateCacheItemWithSetFilepathSucceeds() {
    
    $obj = new Cachey\Drivers\File();
    $obj->set_option('filepath', $this->content_dir);
    $obj->create_cache_item('abc', '123');
    $this->assertFileExists($this->content_dir . md5('abc') . '.cache');
  }
  
  // -----------------------------------------------------------
  
  public function testRetrieveCacheItemSucceedsForCacheThatExists() {
    
    $obj = new Cachey\Drivers\File();
    $obj->set_option('filepath', $this->content_dir);
    $obj->create_cache_item('abc', '123', '60');
    $this->assertEquals('123', $obj->retrieve_cache_item('abc'));    
  }
  
  // -----------------------------------------------------------

  public function testRetrieveCacheItemFailsForExpiredCacheItem() {
    
    $obj = new Cachey\Drivers\File();
    $obj->set_option('filepath', $this->content_dir);
    $obj->create_cache_item('abc', '123', 1);
    sleep(2);
    $this->assertFalse($obj->retrieve_cache_item('abc'));
    $this->assertFalse(file_exists($this->content_dir . md5('abc') . '.cache'));
  }

  // -----------------------------------------------------------

  public function testClearCacheItemWithParamClearsOnlyOneItem() {
    
    $obj = new Cachey\Drivers\File();
    $obj->set_option('filepath', $this->content_dir);
    $obj->create_cache_item('abc', '123');
    $obj->create_cache_item('def', '456');
    $obj->clear_cache('abc');
    $this->assertFileExists($this->content_dir . md5('def') . '.cache');
  } 
  
  // -----------------------------------------------------------

  public function testClearCacheItemWithNoParamClearsAllCache() {
    
    $obj = new Cachey\Drivers\File();
    $obj->set_option('filepath', $this->content_dir);
    $obj->create_cache_item('abc', '123');
    $obj->create_cache_item('def', '456');
    $obj->clear_cache();
    $this->assertFalse(file_exists($this->content_dir . md5('abc') . '.cache'));    
    $this->assertFalse(file_exists($this->content_dir . md5('def') . '.cache'));
  }
}

/* EOF: FileDriverTest.php */