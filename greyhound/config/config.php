<?php

/**
 * @file Greyhound CMS main configuration file
 */

// --------------------------------------------------------------

/*
 * Set the environment.  Allowed settings:
 * 
 * 'development' = Allow development functionality
 * 'production'  = Development functionality off
 * 
 * This should really be set in config.local.php as well 
 */
$config['environment'] = 'development';

// --------------------------------------------------------------

/*
 * Default template
 */
$config['default_template'] = 'default';

// --------------------------------------------------------------

/*
 * Cache Settings
 * 
 * Options include using any cache system that is installed. Currently,
 * those include:
 *  'file'     - Flat file cache
 *  'memcache' - Memcache (if available)
 *  'redis'    - REDIS (if available)
 */
$config['cache']['method'] = FALSE;

/*
 * How long until the cache automatically expires per page?
 */
$config['cache']['expire'] = 7200;

/*
 * Clearcache command (FALSE to disable)
 * 
 * If set to a string, then you can append ?cc=<cmd> to a URL
 * to manually clear the cache
 */
$config['cache']['clearcache'] = 'clear';

/*
 * Fail gracefully if caching does not succeed?
 */
$config['cache']['fail_gracefully'] = FALSE;

// --------------------------------------------------------------

/*
 * Node Types
 */
$config['node_types'] = array(
	'article'    => 'Article',
	'resource'   => 'Resource',
	'work'       => 'Work'
);

/*
 * Custom Node Meta Fields
 * (title is built-in)
 */
$config['meta_fields'] = array(
	'date_published' => 'Date Published',
	'date_updated'   => 'Date Updated',
	'image'          => 'Teaser Image',
	'summary'        => 'Summary',
	'tags'           => 'Tags',
	'category'       => 'Category'
);

/*
 * Custom Meta Fields per Node Type
 */
$config['meta_fields']['article'] = array(
	'category'   => 'Category'	
);

// --------------------------------------------------------------

/*
 * Redirects
 * 
 * Use '' (empty string) to redirect to home page
 */
$config['redirects']['some/arbitrary/path'] = '';

// --------------------------------------------------------------

/*
 * Allowed characters in URI (regexp)
 * 
 * Default is: 
 *  
 * Leave blank for none.
 * 
 * @TODO: Implement this!
 */
$config['allowed_uri_chars'] = 'a-z 0-9~%.:_\-\?=';

/* EOF: config.php */