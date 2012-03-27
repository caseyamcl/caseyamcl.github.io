<?php

require_once(__DIR__ . '/../Mapper.php');
require_once(__DIR__ . '/../Contentitem.php');

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
    file_put_contents($this->content_path . $ds . 'meta.json', json_encode(array('title' => 'Front Page')));

    //Fake some_content page
    mkdir($this->content_path . $ds . 'some_content');
    file_put_contents($this->content_path . "{$ds}some_content{$ds}" . 'content.php', "<p>Some Content Html</p>");
    file_put_contents($this->content_path . "{$ds}some_content{$ds}" . 'meta.json', json_encode(array('title' => 'Some Content')));

    //Fake some_content/subcontent page
    mkdir($this->content_path . $ds . 'some_content' . $ds . 'subcontent');
    file_put_contents($this->content_path . "{$ds}some_content{$ds}subcontent{$ds}" . 'content.php', "<p>Subcontent Html</p>");
    file_put_contents($this->content_path . "{$ds}some_content{$ds}subcontent{$ds}" . 'meta.json', json_encode(array('title' => 'Subcontent')));

    //Fake some_other_content page
    mkdir($this->content_path . $ds . 'some_other_content');
    file_put_contents($this->content_path . "{$ds}some_other_content{$ds}" . 'content.php', "<p>Some Other Content Html</p>");
    file_put_contents($this->content_path . "{$ds}some_other_content{$ds}" . 'meta.json', json_encode(array('title' => 'Some Other Content')));


  }

  // --------------------------------------------------------------

  function tearDown()
  {    
    $ds = DIRECTORY_SEPARATOR;

    unlink($this->content_path . "{$ds}some_other_content{$ds}" . 'content.php');
    unlink($this->content_path . "{$ds}some_other_content{$ds}" . 'meta.json');
    rmdir($this->content_path . $ds . 'some_other_content');

    unlink($this->content_path . "{$ds}some_content{$ds}subcontent{$ds}" . 'content.php');
    unlink($this->content_path . "{$ds}some_content{$ds}subcontent{$ds}" . 'meta.json');
    rmdir($this->content_path . "{$ds}some_content{$ds}subcontent");

    unlink($this->content_path . "{$ds}some_content{$ds}" . 'content.php');
    unlink($this->content_path . "{$ds}some_content{$ds}" . 'meta.json');
    rmdir($this->content_path . $ds . 'some_content');

    unlink($this->content_path . $ds . 'content.php');
    unlink($this->content_path . $ds . 'meta.json');
    rmdir($this->content_path);

    parent::tearDown();
  } 

  // --------------------------------------------------------------

  public function testInstantiateAsObjectSucceeds() {

    $obj = new ContentMapper\Mapper($this->content_path, 'http://localhost/content/');
    $this->assertInstanceOf('ContentMapper\Mapper', $obj);
  }

  // --------------------------------------------------------------

  public function testTestDirectoriesAndFilesAreCorrectlySetup() {

    $ds = DIRECTORY_SEPARATOR;

    $this->assertFileExists($this->content_path . $ds . 'content.php');
    $this->assertFileExists($this->content_path . $ds . 'meta.json');
    $this->assertFileExists($this->content_path . $ds . 'some_content' . $ds . 'content.php');
    $this->assertFileExists($this->content_path . $ds . 'some_content' . $ds . 'meta.json');
    $this->assertFileExists($this->content_path . $ds . 'some_content' . $ds . 'subcontent' . $ds . 'content.php');
    $this->assertFileExists($this->content_path . $ds . 'some_content' . $ds . 'subcontent' . $ds . 'meta.json');
    $this->assertFileExists($this->content_path . $ds . 'some_other_content' . $ds . 'content.php');
    $this->assertFileExists($this->content_path . $ds . 'some_other_content' . $ds . 'meta.json');    
  }

  // --------------------------------------------------------------

  /**
   * @depends testTestDirectoriesAndFilesAreCorrectlySetup
   */
  public function testGetSiteMapReturnsCorrectFullSiteMap() {

    $obj = new ContentMapper\Mapper($this->content_path, 'http://localhost/content/');
    
    $match_array = array(
      $this->content_path . '/some_content/' => 'Some Content',
      $this->content_path . '/some_content/subcontent/' => 'Subcontent',
      $this->content_path . '/some_other_content/' => 'Some Other Content',
    );
    
    $this->assertEquals($match_array, $obj->get_sitemap());
  }

  // --------------------------------------------------------------

  public function testMapUrlPathToFilePathMapsCorrectly() {
        
    $ds = DIRECTORY_SEPARATOR;
        
    $obj = new ContentMapper\Mapper($this->content_path, 'http://localhost/content/');
    $result = $obj->map_urlpath_to_filepath('some_content/subcontent');
    $this->assertEquals($this->content_path . $ds . 'some_content' . $ds . 'subcontent', $result);
  }
  
  // --------------------------------------------------------------
  
  public function testMapUrlReturnsCorrectResultForFront() {
    
    $ds = DIRECTORY_SEPARATOR;
    
    $obj = new ContentMapper\Mapper($this->content_path, 'http://localhost/content/');
    $result = $obj->map_urlpath_to_filepath('');
    
    $this->assertEquals($this->content_path . $ds, $result);
  }
  
  // --------------------------------------------------------------
  
  public function testLoadContentObjectReturnsContentObject() {
    
    $obj = new ContentMapper\Mapper($this->content_path, 'http://localhost/content/');
    $result = $obj->load_content_object('some_content/subcontent');
    
    $this->assertInstanceOf('ContentMapper\Contentitem', $result);    
  }
  
  // --------------------------------------------------------------
  
  public function testLoadContentObjectReturnsContentObjectForFront() {
    
    $obj = new ContentMapper\Mapper($this->content_path, 'http://localhost/content/');
    $result = $obj->load_content_object('');
    
    $this->assertInstanceOf('ContentMapper\Contentitem', $result);     
  }
  
  // --------------------------------------------------------------
  
  public function testExceptionOccursWhenTryingToLoadNonexistentContentItem() {
    
    try {
      $obj = new ContentMapper\Mapper($this->content_path, 'http://localhost/content/');
      $result = $obj->load_content_object('certainly_does_not_exist');
      
    } catch (ContentMapper\MapperException $e) {
      return;
    }
    
    $this->fail("Mapping a non-existent path should have thrown an exception!");
    
  }
}

/* EOF: MapperTest.php */