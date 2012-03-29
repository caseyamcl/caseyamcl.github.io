<?php

require_once(__DIR__ . '/../Renderlib.php');
require_once(__DIR__ . '/../Outputters/Outputter.php');

class RenderlibTest extends PHPUnit_Framework_TestCase {
      
  // --------------------------------------------------------------
  
  function setUp()
  {
    parent::setUp();    
  }

  // --------------------------------------------------------------

  function tearDown()
  { 
    parent::tearDown();
  } 
  
  // --------------------------------------------------------------
  
  public function testRegisterContentTypesInConstructorWorks() {
    
    $obj = new \Renderlib\Renderlib();
    $this->assertInstanceOf('\Renderlib\Renderlib', $obj);    
  }
  
  // --------------------------------------------------------------
  
  public function testSomeExpectedContentTypesExist() {
    
    $obj = new \Renderlib\Renderlib();
    
    $match = array(
      'text/html' => 'Html',
      'application/html+xml' => 'Html',
      'application/json' => 'Json',
      'application/pdf' => 'Pdf',
      'text/plain' => 'Txt',
      'text/x-markdown' => 'Txt',
      'application/xml' => 'Xml'
    );
    
    $this->assertEquals($match, $obj->get_available_content_types());
  }
  
  // --------------------------------------------------------------
  
  public function testExpectedValuesExistForGetContentTypesMimesOnly() {
    
    $obj = new \Renderlib\Renderlib();    
    
    $match = array(
      'text/html',
      'application/html+xml',
      'application/json',
      'application/pdf',
      'text/plain',
      'text/x-markdown',
      'application/xml'
    );    

    $this->assertEquals($match, $obj->get_available_content_types(TRUE));
  }
  
  // --------------------------------------------------------------

  public function testGetOutputterFromClassNameWorksForValidClassname() {

    $obj = new \Renderlib\Renderlib();   
        
    $this->assertInstanceOf('\Renderlib\Outputters\Json', $obj->get_outputter_from_classname('json'));
    $this->assertInstanceOf('\Renderlib\Outputters\Html', $obj->get_outputter_from_classname('html'));
    
  }
  
  // --------------------------------------------------------------

  public function testGetOutputterThrowsExceptionForInvalidClassname() {
    
    $obj = new \Renderlib\Renderlib();   
    
    try {
      $obj->get_outputter_from_classname('does_not_exist');
    } catch (Renderlib\InvalidRenderMimeTypeException $e) {
      return;
    }
    
    $this->fail("In invalid classname should have thrown an Exception!");
  }
}

/* EOF: RenderlibTest.php */