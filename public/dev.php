<?php

/**
 * @file index.php
 * CaseyMcLaughlin.com Public Index File
 *
 * @package CaseyMcLaughlin.com
 * @author Casey McLaughlin
 */

use Caseyamcl\WebApp;

if (substr($_SERVER['HTTP_HOST'], 0, strlen('localhost')) != 'localhost') {
    header("HTTP/1.1 403 Forbidden");
    die("Nope");
}


//Autoloader
require(__DIR__ . '/../vendor/autoload.php');

//Away we go
\Caseyamcl\WebApp::main(WebApp::DEVELOPMENT);

/* EOF: php.php */