<?php

/**
 * @file Greyhound CMS Index.php file
 */

/*
 * Define the Basepath
 */
define('BASEPATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

// --------------------------------------------------------------

/**
 * A simple function to intelligently resolve paths for this application
 * 
 * @param string $path
 * @return string
 */
function gh_path($path) {	
	return realpath(BASEPATH . str_replace('/', DIRECTORY_SEPARATOR, $path));
}

// --------------------------------------------------------------

/*
 * Load up the Application
 */
require_once(gh_path('greyhound/sys/main.php'));

/* EOF: index.php */