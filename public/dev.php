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

require(__DIR__ . '/../src/bootstrap.php');

//Away we go
WebApp::main(WebApp::DEVELOPMENT);

/* EOF: php.php */