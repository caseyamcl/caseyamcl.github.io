<?php

require_once(__DIR__ . '/../Request.php');

class UriTest extends PHPUnit_Framework_TestCase {

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

  public function testInstantiateAsObjectSucceeds() {
    
    $obj = $this->get_request_obj();
    $this->assertInstanceOf('Requesty\Request', $obj);
  }
  
  // --------------------------------------------------------------
  
  /**
   * THIS TEST FAILS -- WHY??
   * @depends testInstantiateAsObjectSucceeds
   */
  public function testGetBrowserReturnsCorrectObject() {
    
    $obj = $this->get_request_obj();
    $this->assertEquals($obj->get_browser(), $this->get_browser_arr());
  }
  
  // --------------------------------------------------------------
  
  private function get_request_obj() {
    
    $brstub = $this->getMock('Browscap')
      ->expects($this->any())
      ->method('getBrowser')
      ->with($this->equalTo(NULL), $this->equalTo(TRUE))
      ->will($this->returnValue($this->get_browser_arr()));

    return new \Requesty\Request($brstub);
  }
  
  // --------------------------------------------------------------
  
  private function get_browser_arr() {
    
    $browser_obj = "Tzo4OiJzdGRDbGFzcyI6Mjk6e3M6MTI6ImJyb3dzZXJfbmFtZSI7czo4MDoiTW96aWxsYS81LjAgKFgxMTsgVWJ1bnR1OyBMaW51eCB4ODZfNjQ7IHJ2OjEwLjAuMikgR2Vja28vMjAxMDAxMDEgRmlyZWZveC8xMC4wLjIiO3M6MTg6ImJyb3dzZXJfbmFtZV9yZWdleCI7czo1MjoiXm1vemlsbGEvNVwuMCBcKC4qbGludXguKlwpIGdlY2tvLy4qIGZpcmVmb3gvMTBcLi4qJCI7czoyMDoiYnJvd3Nlcl9uYW1lX3BhdHRlcm4iO3M6NDI6Ik1vemlsbGEvNS4wICgqTGludXgqKSBHZWNrby8qIEZpcmVmb3gvMTAuKiI7czo2OiJQYXJlbnQiO3M6MTI6IkZpcmVmb3ggMTAuMCI7czo4OiJQbGF0Zm9ybSI7czo1OiJMaW51eCI7czo1OiJXaW4zMiI7YjowO3M6NzoiQnJvd3NlciI7czo3OiJGaXJlZm94IjtzOjc6IlZlcnNpb24iO3M6NDoiMTAuMCI7czo4OiJNYWpvclZlciI7aToxMDtzOjY6IkZyYW1lcyI7YjoxO3M6NzoiSUZyYW1lcyI7YjoxO3M6NjoiVGFibGVzIjtiOjE7czo3OiJDb29raWVzIjtiOjE7czoxMDoiSmF2YVNjcmlwdCI7YjoxO3M6MTE6IkphdmFBcHBsZXRzIjtiOjE7czoxMDoiQ3NzVmVyc2lvbiI7aTozO3M6ODoiTWlub3JWZXIiO2k6MDtzOjU6IkFscGhhIjtiOjA7czo0OiJCZXRhIjtiOjA7czo1OiJXaW4xNiI7YjowO3M6NToiV2luNjQiO2I6MDtzOjE2OiJCYWNrZ3JvdW5kU291bmRzIjtiOjA7czo4OiJWQlNjcmlwdCI7YjowO3M6MTU6IkFjdGl2ZVhDb250cm9scyI7YjowO3M6ODoiaXNCYW5uZWQiO2I6MDtzOjE0OiJpc01vYmlsZURldmljZSI7YjowO3M6MTk6ImlzU3luZGljYXRpb25SZWFkZXIiO2I6MDtzOjc6IkNyYXdsZXIiO2I6MDtzOjEwOiJBb2xWZXJzaW9uIjtpOjA7fQ==";
    return (array) unserialize(base64_decode($browser_obj));    
  }
  
}

/* EOF: Request.php */
