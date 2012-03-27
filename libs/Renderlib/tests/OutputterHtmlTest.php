<?php

require_once(__DIR__ . '/../Renderlib.php');
require_once(__DIR__ . '/../Outputters/Outputter.php');
require_once(__DIR__ . '/../Outputters/Html.php');

class OutputterHtmlTest extends PHPUnit_Framework_TestCase {

  private $template_dir;
  
  // --------------------------------------------------------------
  
  function setUp()
  {
    parent::setUp();    
    
    //File Data
    $filedata = '<!doctype html><html><head><meta charset="utf-8"/><title></title></head><body><?php echo $content; ?></body></html>';
    
    //Setup the temp directory
    $this->template_dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 
      'php_renderlib_test_template_dir' . time() . DIRECTORY_SEPARATOR;    
    mkdir($this->template_dir);    
    file_put_contents($this->template_dir . DIRECTORY_SEPARATOR . 'template.php', $filedata);    
  }

  // --------------------------------------------------------------

  function tearDown()
  { 
    unlink($this->template_dir . DIRECTORY_SEPARATOR . 'template.php');
    rmdir($this->template_dir);
    
    parent::tearDown();
  }   
  
  // --------------------------------------------------------------

  public function testBuildObjectSucceeds() {
    $obj = new \Renderlib\Outputters\Html();
    $this->assertInstanceOf('\Renderlib\Outputters\Html', $obj);
  }
  
  // --------------------------------------------------------------

  public function testGetMimeTypesReturnsValidMimeTypes() {
    
    $obj = new \Renderlib\Outputters\Html();
    $this->assertContains('text/html', $obj->get_mime_types());
    $this->assertContains('application/html+xml', $obj->get_mime_types());
    
  }

  // --------------------------------------------------------------

  public function testRenderOutputThrowsExceptionWithoutAttrsSet() {
    
    $obj = new \Renderlib\Outputters\Html();
    
    try {    
      $obj->render_output($this->get_content_item_mock());
    } catch (Exception $e) {
      return;
    }
    
    $this->fail("Render_output should have thrown an excpetion without the template dir set!");
    
  }
  
  // --------------------------------------------------------------

  public function testRenderOutputRendersStringWithAttrsSet() {
    
    $obj = new \Renderlib\Outputters\Html();
    $obj->set_option('template_dir', $this->template_dir);
    $obj->set_option('template_url', 'http://localhost/test/');

    $match_string = '<!doctype html><html><head><meta charset="utf-8"/><title></title></head><body>Test Content</body></html>';
    $this->assertEquals($obj->render_output($this->get_content_item_mock()), $match_string);
  }
  
  // --------------------------------------------------------------
  
  public function testRender404OutputRendersDefaultString() {
    
    $obj = new \Renderlib\Outputters\Html();
    $obj->set_option('template_dir', $this->template_dir);
    $obj->set_option('template_url', 'http://localhost/test/');

    $match_string = '<!doctype html><html><head><meta charset="utf-8"/><title></title></head><body><p class=\'error 404\'>404 - Page Not Found</p></body></html>';
    $this->assertEquals($obj->render_404_output(), $match_string);
  }
  
  // --------------------------------------------------------------
  
  public function testRender404OuptutRendersFileStringWithFileSet() {

    //Setup the file
    file_put_contents($this->template_dir . '_404.php', '<p>It aint here</p>');
    
    $obj = new \Renderlib\Outputters\Html();
    $obj->set_option('template_dir', $this->template_dir);
    $obj->set_option('template_url', 'http://localhost/test/');

    $match_string = '<!doctype html><html><head><meta charset="utf-8"/><title></title></head><body><p>It aint here</p></body></html>';
    $this->assertEquals($obj->render_404_output(), $match_string);
    
    //Tear down the file
    unlink($this->template_dir . '_404.php');
  }
  
  // --------------------------------------------------------------
  
  public function testRenderErrorRendersDefaultString() {

    $obj = new \Renderlib\Outputters\Html();
    $obj->set_option('template_dir', $this->template_dir);
    $obj->set_option('template_url', 'http://localhost/test/');
    
    $match_string = '<!doctype html><html><head><meta charset="utf-8"/><title></title></head><body><p class=\'error general\'>Error (500) - Internal Server Error</p></body></html>';
    $this->assertEquals($obj->render_error_output('500', 'Internal Server Error'), $match_string);
  }

  // --------------------------------------------------------------
 
  public function testRenderErrorRendersFileStringWithFileSet() {

    //Setup the file
    file_put_contents($this->template_dir . '_error.php', '<p>Dang</p>');
        
    $obj = new \Renderlib\Outputters\Html();
    $obj->set_option('template_dir', $this->template_dir);
    $obj->set_option('template_url', 'http://localhost/test/');

    $match_string = '<!doctype html><html><head><meta charset="utf-8"/><title></title></head><body><p>Dang</p></body></html>';
    $this->assertEquals($obj->render_error_output('500', 'Internal Server Error'), $match_string);
    
    //Tear down the file
    unlink($this->template_dir . '_error.php');   
  }
  
  // --------------------------------------------------------------

  private function get_content_item_mock($include_type = TRUE) {

    //Return the content_item object
    $obj = $this->getMock('Content_item');
    $obj->meta = new stdClass();
    $obj->content = "Test Content";
    
    if ($include_type)
      $obj->meta->type = 'test_type';
      
    return $obj;
  }
  
  // --------------------------------------------------------------
  
}

/* EOF: OutputterHtmlTest.php */