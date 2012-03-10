<?php

require_once('../Negotiator.php');

class NegotiatorTest extends PHPUnit_Framework_TestCase
{
  function setUp()
  {
    parent::setUp();
  }

  // --------------------------------------------------------------

  function tearDown()
  {
    $this->_cli_class = NULL;
    parent::tearDown();
  }

  // --------------------------------------------------------------
  
  function testNegotiateWithClientData()
  {
    
  }

}

/* EOF: NegotiatorTest.php */