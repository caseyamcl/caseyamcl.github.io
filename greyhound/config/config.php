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
$config['cache']['method'] = 'file';

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
 * Page Types
 * (basic_page is built-in)
 */
$config['page_types'] = array(
	'post'       => 'Post',
	'code'       => 'Code'
);

/*
 * Custom Node Page Fields
 * (title is built-in)
 */
$config['meta_fields'] = array(
	'summary'        => 'Summary'
);

/*
 * Custom Meta Fields per Page Type
 */
$config['meta_fields']['post'] = array(
	'date_published' => 'Date Published',
	'date_updated'   => 'Date Updated',
	'image'          => 'Teaser Image',
	'summary'        => 'Summary',
	'tags'           => 'Tags',
	'category'       => 'Category'
);

$config['meta_fields']['code'] = array(
	'date_published' => 'Date Published',
	'date_updated'   => 'Date Updated',
	'image'          => 'Teaser Image',
	'summary'        => 'Summary',
	'tags'           => 'Tags',
	'category'       => 'Category'
);

// --------------------------------------------------------------

/*
 * Redirects
 * 
 * Use '' (empty string) to redirect to home page
 */
$config['redirects']['some/arbitrary/path'] = 'content';

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