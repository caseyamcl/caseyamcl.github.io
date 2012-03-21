<?php

require_once(__DIR__ . '/../Content_item.php');

class MapperTest extends PHPUnit_Framework_TestCase {
  
  private $content_path;
    
  // --------------------------------------------------------------
  
  function setUp()
  {
    parent::setUp();    
    
    $ds = DIRECTORY_SEPARATOR;
    $this->content_path = sys_get_temp_dir() . $ds . 'phpunit_mapper_test_' . time();
    
    //Setup fake content directory
    mkdir($this->content_path);
    
    //Fake front page
    file_put_contents($this->content_path . $ds . 'content.php', "<p>Front Html</p>");
    file_put_contents($this->content_path . $ds . 'meta.json', json_encode(array('title' => 'Front Page', 'arbitrary' => 'Value')));
    file_put_contents($this->content_path . $ds . 'arbitrary.txt', 'some arbitrary textfile');
  }

  // --------------------------------------------------------------

  function tearDown()
  { 
    $ds = DIRECTORY_SEPARATOR;    
    unlink($this->content_path . $ds . 'content.php');
    unlink($this->content_path . $ds . 'meta.json');
    unlink($this->content_path . $ds . 'arbitrary.txt');
    rmdir($this->content_path);    
    
    parent::tearDown();
  } 
  
  // --------------------------------------------------------------
  
  public function testTestDirectoriesAndFilesAreCorrectlySetup() {
    
    $ds = DIRECTORY_SEPARATOR;  
    $this->assertFileExists($this->content_path . $ds . 'content.php');
    $this->assertFileExists($this->content_path . $ds . 'meta.json');    
    
  }
  
  // --------------------------------------------------------------
  
  public function testConstructWorksWithExistentContentPath() {
    
    $obj = new ContentMapper\Content_item($this->content_path, 'http://localhost/test/');
    $this->assertInstanceOf('ContentMapper\Content_item', $obj);
  }
  
  // --------------------------------------------------------------
  
  public function testExpectedPropertiesExist() {
        
    $obj = new ContentMapper\Content_item($this->content_path, 'http://localhost/test/');
    
    $expected_props = array('path', 'url', 'title', 'meta', 'content', 'file_urls', 'file_paths');
    $unexpected_props = array('_meta_filename', '_content_filename');
    
    foreach($expected_props as $prop) {
      $this->assertObjectHasAttribute($prop, $obj);
    }
    
    foreach($unexpected_props as $prop) {
      
      try {
        $a = $obj->$prop;
      } catch (Exception $e) {
        continue;
      }
      
      $this->fail("The property '$prop' should have generated an exception!");
    }
    
  }
  
  // --------------------------------------------------------------
    
  public function testPropertiesAreCorrectValues() {
    
    //Notice I left off the trailing slash on the URL. it should still work.
    $obj = new ContentMapper\Content_item($this->content_path, 'http://localhost/test');
    
    $this->assertEquals($obj->path, realpath($this->content_path) . DIRECTORY_SEPARATOR);
    $this->assertEquals($obj->url, 'http://localhost/test/');
    $this->assertEquals($obj->title, 'Front Page');
    $this->assertEquals($obj->meta, (object) array('arbitrary' => 'Value'));
    $this->assertEquals($obj->content, "<p>Front Html</p>");
    $this->assertEquals($obj->file_urls, array('http://localhost/test/arbitrary.txt'));
    $this->assertEquals($obj->file_paths, array(realpath($this->content_path) . DIRECTORY_SEPARATOR . 'arbitrary.txt'));
  }
}

/* EOF: Content_ItemTest.php */