<?php

/**
 * CaseyMcLaughlin.com Bootstrap File
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */

// ------------------------------------------------------------------

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Eloquent\Asplode\Asplode;

//Vendor Autoload
require_once(__DIR__ . '/../vendor/autoload.php');

//Autoload Src Folder
$loader = new UniversalClassLoader();
$loader->registerNamespace('Caseyamcl', __DIR__);
$loader->register();

//Asplode Error Management
Asplode::instance()->install();

/* EOF: bootstrap.php */