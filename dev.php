<?php

/**
 * @file index.php
 * CaseyMcLaughlin.com Public Development Index File
 *
 * @package CaseyMcLaughlin.com
 * @author Casey McLaughlin
 */

use Caseyamcl\App;

if (substr($_SERVER['HTTP_HOST'], 0, strlen('localhost')) != 'localhost') {
    header("HTTP/1.1 403 Forbidden");
    die("Nope");
}


//Autoloader
require(__DIR__ . '/app/vendor/autoload.php');

//Away we go
App::main(App::DEVELOPMENT);

/* EOF: php.php */
