<?php

/**
 * @file index.php
 * CaseyMcLaughlin.com Public Index File
 *
 * @package CaseyMcLaughlin.com
 * @author Casey McLaughlin
 */

//Sanity check
if ( ! is_readable(__DIR__ . '/app/vendor/autoload.php')) {
    header("HTTP/1.1 500 Internal Server Error");
    header("Content-type: text/plain");
    die("CaseyMcLaughlin.com is not installed correctly.  Check back later.");
}

//Autoloader
require(__DIR__ . '/app/vendor/autoload.php');


//Away we go
\Caseyamcl\App::main();

/* EOF: index.php */