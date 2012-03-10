<?php

namespace Requesty;

class Negotiator
{  
  /**
   * Negotiate a content-type, language, etc. from a request header
   * 
   * The $requested paramater should be in the format:
   * key is type/language/whatever
   * value is the weight (between 0 and 1)
   * 
   * Example:
   *  array(
   *    'en-us' => 1
   *    'en'    => 0.8
   *    'de'    => 0.5
   *    '*'     => 0
   *  );
   * 
   * @param array $requested
   * @param array $available
   * @param boolean $strict  If strict, and no '*' or '*[slash]*', then return FALSE
   * @param string $default  The default to send back if no match. 
   * If NULL, send back the first item in the $available array
   */
  public function negotiate($requested, $available, $strict = FALSE, $default = NULL) {
    
    //Manipulate the requested array
    asort($requested);
    $requested = array_reverse($requested);
    
    //Look for a match
    foreach($requested as $item => $weight) {
      if (in_array($item, $available))
        return $item;      
    }
    
    //If made it here, no match
    if (in_array('*', $requested) OR in_array('*/*', $requested) OR ! $strict) {
      return $default ?: array_shift($available);
    }    
    else {
      return FALSE;
    }
  }
}

/* EOF: Negotiator.php */