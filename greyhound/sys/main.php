<?php

/**
 * @file Greyhound Main
 */

//Define a few more constants that we'll need later
define('TEMPLATEPATH', gh_path('templates/'));

//Include the autoloader
require_once(gh_path('greyhound/sys/libs/autoloader.php'));
Autoloader::init(array(
	gh_path('greyhound/sys/libs/'),
	gh_path('greyhound/sys/vendor/')
));

// --------------------------------------------------------------

/**
 * Main Execution Script for Greyhound CMS
 */
function main()
{
	$c = new Pimple();
		
	//Cache filepath
	$c['cache_filepath'] = gh_path('greyhound/data/cache');
	
	//Load Config
	$c['config_path'] = gh_path('greyhound/config/');
	$c['config'] = $c->share(function ($c) { return new Config($c['config_path']); });
	
	//Load URI
	$c['uri'] = $c->share(function ($c) { return new Uri(); });		
	
	//Client Request Information
	$c['browscap'] = $c->share(function($c) { return new Browscap($c['cache_filepath']); });
	$c['client'] = $c->share(function ($c) { return new Client($c['browscap']); });		
	
	//Get the accepted mime types from the client, and attempt to match them
	//up to an existing mime type that we have in the system.  If no matching
	//mime-type, then deliver a 401 invalid request with an explanation.
	//@TODO: This - In the meantime, just set it to application/html
	$c['mimetype'] = 'application/html';
	
	//Cache - At this point, we've read the config, uri, and determined a response format.
	//We can now check for a cached version of the output.  If one exists, just display it
	//and exit.  If not, then proceed.
	$c['cache'] = $c->share(function ($c) { return new Cache($c['config'], $c['uri']); });
	
	if ($cached_version = $c['cache']->retrieve_cache_version())
	{
		$output =& $cached_version;
	}
	else //Load the system
	{
		//Read the URI

		//Does the page exist?

		//Determine what type of page it is
		// - node if it contains node_meta.json
		// - list if it contains list_meta.json
		// - basic page if contains nothing

		//Formulate output.  If JSON or XML, then just generate one of those

		//If HTML, then load the template system, and use that to generate a response	
	}
	
}

// --------------------------------------------------------------

// Away we go!
main();


/* EOF: main.php */