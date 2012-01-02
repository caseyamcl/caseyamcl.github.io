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
		
	//Filepaths
	$c['cache_filepath'] = gh_path('greyhound/data/cache');
	$c['page_filepath'] = gh_path('pages');
	
	//Load Config
	$c['config_path'] = gh_path('greyhound/config/');
	$c['config'] = $c->share(function ($c) { return new Config($c['config_path']); });
	
	//Load URI
	$c['uri'] = $c->share(function ($c) { return new Uri(); });		
	
	//Client Request Information
	$c['browscap'] = $c->share(function($c) { return new Browscap($c['cache_filepath']); });
	$c['client'] = $c->share(function ($c) { return new Client($c['browscap']); });		

	//Cache class
	$c['cache'] = $c->share(function ($c) { return new Cache($c['config'], $c['uri']); });

	//Output class
	$c['output'] = $c->share(function($c) { return new Output($c[$cache]); });

	//Get the accepted mime types from the client, and attempt to match them
	//up to an existing mime type that we have in the system.  If no matching
	//mime-type, then deliver a 401 invalid request with an explanation.
	//@TODO: This - In the meantime, just set it to application/html
	$c['mimetype'] = 'application/html';
	
	//Cache - At this point, we've read the config, uri, and determined a response format.
	//We can now check for a cached version of the output.  If one exists, just display it
	//and exit.  If not, then proceed.	
	if ($cached_version = $c['cache']->retrieve_cache_version())
	{
		$c['output']->set_http_status('304');
		$c['output']->set_output($cached_version);
	}
	else //Load the system
	{
		//Load more dependencies
		$c['pageloader'] = $c->share(function ($c) { return new Pageloader($c['config'], $c['page_filepath'], 'pages'); });
		
		//Read the URI
		$uri = $c['uri']->get_path_string();
		
		//Does the page exist?
		$page_exists = $c['pageloader']->check_page_exists($uri);
		
		if ($page_exists)
		{
			$c['pageloader']->load_page($uri);
			
		}
		else
		{
			$c['output']->set_http_status('404');
			
			//Load a 404
			if ($c['pageloader']->check_page_exists('_404'))
				$c['output']->set_output($c['pageloader']->load_page('_404'));			
			elseif (is_readable(gh_path('greyhound/includes/error_404_default.html')))
				$c['output']->set_output(file_get_contents(gh_path('greyhound/includes/error_404_default.html')));
			else
				throw new Exception("Cannot find default 404 page!");
		}

	}
	
}

// --------------------------------------------------------------

// Away we go!
main();


/* EOF: main.php */