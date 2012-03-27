<?php

require_once(__DIR__ . '/../Assetlib.php');

class AssetLibTest extends PHPUnit_Framework_TestCase {
  
  private $content_path;
  
  // --------------------------------------------------------------

  function setUp() {
    
    parent::setUp();    
    
    $ds = DIRECTORY_SEPARATOR;
    $this->content_path = sys_get_temp_dir() . $ds . 'phpunit_assetlib_test_' . time();
    
    //Setup fake content directory
    mkdir($this->content_path);

    //Fake front page
    mkdir($this->content_path . $ds . 'content');
    file_put_contents($this->content_path . $ds . 'content' . $ds . 'main.css', 'SOME CSS CONTENT');
    file_put_contents($this->content_path . $ds . 'content' . $ds .  'img.jpg', 'SOME IMAGE CONTENT');

    //Fake some_content page
    mkdir($this->content_path . $ds . 'content' . $ds . 'some_content');
    file_put_contents($this->content_path . $ds . 'content' . "{$ds}some_content{$ds}" . 'main.css', "SOME CSS CONTENT");
    file_put_contents($this->content_path . $ds . 'content' . "{$ds}some_content{$ds}" . 'img.jpg', "SOME IMAGE CONTENT");

    //Fake template files
    mkdir($this->content_path . $ds . 'template');
    file_put_contents($this->content_path . $ds . 'template' . $ds . 'main.css', "SOME CSS CONTENT");
    file_put_contents($this->content_path . $ds . 'template' . $ds . 'img.jpg', "SOME IMAGE CONTENT");
  }
  
  // --------------------------------------------------------------

  function tearDown() {

    $ds = DIRECTORY_SEPARATOR;
    
    //Delete files
    unlink($this->content_path . $ds . 'content' . $ds . 'main.css');
    unlink($this->content_path . $ds . 'content' . $ds .  'img.jpg');
    unlink($this->content_path . $ds . 'content' . "{$ds}some_content{$ds}" . 'main.css');
    unlink($this->content_path . $ds . 'content' . "{$ds}some_content{$ds}" . 'img.jpg');
    unlink($this->content_path . $ds . 'template' . $ds . 'main.css');
    unlink($this->content_path . $ds . 'template' . $ds . 'img.jpg');
    
    //Delete directories
    rmdir($this->content_path . $ds . 'template');
    rmdir($this->content_path . $ds . 'content' . $ds . 'some_content');
    rmdir($this->content_path . $ds . 'content');
    rmdir($this->content_path);
    
    parent::tearDown();
    
  }
  
  // --------------------------------------------------------------

  public function testTestDirectoriesAndFilesAreCorrectlySetup() {

    $ds = DIRECTORY_SEPARATOR;

    $this->assertFileExists($this->content_path . $ds . 'content' . $ds . 'main.css');
    $this->assertFileExists($this->content_path . $ds . 'content' . $ds .  'img.jpg');
    $this->assertFileExists($this->content_path . $ds . 'content' . $ds . "some_content" . $ds . 'main.css');
    $this->assertFileExists($this->content_path . $ds . 'content' . $ds . "some_content" . $ds . 'img.jpg');
    $this->assertFileExists($this->content_path . $ds . 'template' . $ds . 'main.css');
    $this->assertFileExists($this->content_path . $ds . 'template' . $ds . 'img.jpg');  
  }
  
  // --------------------------------------------------------------

  public function testDefineUrlMappingsWorkForGoodPaths() {
    
    $ds = DIRECTORY_SEPARATOR;
    
    $obj = new Assetlib\Assetlib();
    $obj->define_url_mapping('', $this->content_path . $ds . 'content');
    $obj->define_url_mapping('template', $this->content_path . $ds . 'template');
  }
  
  // --------------------------------------------------------------

  public function testDefineUrlMappingThrowsExceptionForBadPath() {
    
    $ds = DIRECTORY_SEPARATOR;
    
    $obj = new Assetlib\Assetlib();

    try {
      $obj->define_url_mapping('template', $this->content_path . $ds . 'DOES_NOT_EXIST');
    } catch(Assetlib\InvalidURLMappingException $e) {
      return;
    }
    
    $this->fail('Creation of a URL mapping to a non-existent path should have thrown an Exception');
    
  }
  
  // --------------------------------------------------------------

  public function testGetAssetMimeReturnsCorrectMimeForExistentResource() {
    
    $ds = DIRECTORY_SEPARATOR;

    $obj = new Assetlib\Assetlib();
    $obj->define_url_mapping('', $this->content_path . $ds . 'content');
    $obj->define_url_mapping('template', $this->content_path . $ds . 'template');
    
    $this->assertEquals('text/css', $obj->get_asset_mime('main.css'));
    $this->assertEquals('image/jpeg', $obj->get_asset_mime('img.jpg'));    
  }
  
  // --------------------------------------------------------------

  public function testGetAssetMimeReturnsCorrectMimeForNonexistentResource() {
    
    $ds = DIRECTORY_SEPARATOR;

    $obj = new Assetlib\Assetlib();
    $obj->define_url_mapping('', $this->content_path . $ds . 'content');
    $obj->define_url_mapping('template', $this->content_path . $ds . 'template');

    $this->assertFalse($obj->get_asset_mime('nope.arf'));
  }
  
  // --------------------------------------------------------------
  
  public function testGetAssetFilePathReturnsCorrectFilePathForMappedExistentResource() {
    
    $ds = DIRECTORY_SEPARATOR;

    $obj = new Assetlib\Assetlib();
    $obj->define_url_mapping('', $this->content_path . $ds . 'content');
    $obj->define_url_mapping('template', $this->content_path . $ds . 'template');
   
    $this->assertEquals($this->content_path . '/content/main.css', $obj->get_asset_filepath('main.css'));
    $this->assertEquals($this->content_path . '/template/main.css', $obj->get_asset_filepath('template/main.css'));
  }
  
  // --------------------------------------------------------------
  
  public function testGetAssetFilePathThrowsExceptionForNonMappedExistentResource() {
    
    $ds = DIRECTORY_SEPARATOR;

    $obj = new Assetlib\Assetlib();
    $obj->define_url_mapping('', $this->content_path . $ds . 'content');
    $obj->define_url_mapping('template', $this->content_path . $ds . 'template');
   
    file_put_contents($this->content_path . $ds . 'template'. $ds . 'file.arg', 'stuff');
    
    try {
      $this->assertFalse($obj->get_asset_filepath('template/file.arg'));
      $this->fail("Unmapped type should have thrown a Exception!");
    } catch (Assetlib\UnmappedMimeTypeException $e) {
      /* pass */
    }
    
    unlink($this->content_path . $ds . 'template' . $ds . 'file.arg');
  }
  
  // --------------------------------------------------------------

  public function testGetAssetFilePathReturnsFalseForMappedNonExistentResource() {
    
    $ds = DIRECTORY_SEPARATOR;

    $obj = new Assetlib\Assetlib();
    $obj->define_url_mapping('', $this->content_path . $ds . 'content');
    $obj->define_url_mapping('template', $this->content_path . $ds . 'template');

    $this->assertFalse($obj->get_asset_filepath('template/does_Not_exist.css'));
  }  
}

/* EOF: AssetLibTest.php */