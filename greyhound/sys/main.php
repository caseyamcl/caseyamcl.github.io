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
try {
	
	$c = new Pimple();

	//Filepaths
	$c['cache_filepath'] = gh_path('greyhound/data/cache');
	$c['page_filepath'] = gh_path('pages');
	$c['config_path'] = gh_path('greyhound/config/');

	//Load Classes
	$c['config'] = $c->share(function ($c) { return new Config($c['config_path']); });
	$c['uri'] = $c->share(function ($c) { return new Uri(); });		
	$c['browscap'] = $c->share(function($c) { return new Browscap($c['cache_filepath']); });
	$c['client'] = $c->share(function ($c) { return new Client($c['browscap']); });		
	$c['cache'] = $c->share(function ($c) { return new Cache($c['config'], $c['uri']); });
	$c['output'] = $c->share(function($c) { return new Output($c[$cache]); });

	//Get the accepted mime types from the client, and attempt to match them
	//up to an existing mime type that we have in the system.  If no matching
	//mime-type, then deliver a 401 invalid request with an explanation.
	//@TODO: This - In the meantime, just set it to application/html
	$c['mimetype'] = 'application/html';

	
	//Check for redirect
	$redirects = $c['config']->get_item('redirects');
	if (in_array($c['uri']->get_path_string(), array_keys($redirects)))
	{
		$to = $c['uri']->get_base_url() . $redirects[$c['uri']->get_path_string()];
		$c['output']->redirect($to, '301');
	}
	
	
	//Check for cached version
	if ($cached_version = $c['cache']->retrieve_cache_version())
	{		
		$output = $cached_version;
	}
	
	
	//Load the page through the system
	else 
	{
		//Load template helpers
		foreach(scandir(gh_path('greyhound/sys/helpers')) as $file) {
			if ($file{0} != '.' && substr($file, strlen($file)-strlen('.php')) == '.php')
				require_once(gh_path('greyhound/sys/helpers/'. $file));
		}
		
		//Load more dependencies
		$c['pageloader'] = $c->share(function ($c) { return new Pageloader($c['config'], $c['page_filepath'], 'pages'); });
		$c['pagelister'] = $c->share(function ($c) { return new Pagelister($c['pageloader']); });
		$c['template'] = $c->share(function ($c) { return new Template($c['uri'], TEMPLATEPATH, 'default'); }); //@TODO: default is configurable

		//Read the URI
		$uri = $c['uri']->get_path_string();

		//Does the page exist?  Load it!		
		if ($c['pageloader']->check_page_exists($uri))
		{
			$page = $c['pageloader']->load_page($uri);
			$output = $c['template']->render_main_template($page);
			
			//Also created a cached version
			$c['cache']->create_cache_version($output);			
		}
		else //404
		{
			$c['output']->set_http_status('404');

			//Load a 404
			if ($c['pageloader']->check_page_exists('_404'))
			{
				$page = $c['pageloader']->load_page('_404');
				$output = $c['template']->render_main_template($page);
			}
			elseif (is_readable(gh_path('greyhound/includes/error_404_default.html')))
				$c['output']->set_output(file_get_contents(gh_path('greyhound/includes/error_404_default.html')));
			else
				throw new Exception("Cannot find default 404 page!");
		}
	}

	
	//Output the output, yo.
	$c['output']->set_output($output);
	$c['output']->go();
}
catch(Exception $e) {
	
	//If we can, set the status code
	if (isset($c['output']))
		$c['output']->set_http_status(500);
	
	//If development environment, just throw $e along
	if (isset($c['config']) && $c['config']->get_item('environment') == 'development')
		throw $e;
	
	//Next, check to see if we can load the template
	try {
		
		$page = $c['pageloader']->load_page('_500');
		$c['output']->set_output($c['template']->render_main_template($page));
		$c['output']->go();
		
	} catch (Exception $exp) {
	
		if (is_readable(gh_path('greyhound/includes/error_500_default.html')))
			include(gh_path('greyhound/includes/error_500_default.html'));
		else
			echo "Internal Server Error";
	}
}

/* EOF: main.php */