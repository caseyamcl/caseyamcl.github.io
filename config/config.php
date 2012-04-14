<?php

/**
 * Config.php - Copy to config.local.php for local settings 
 */

/**
 * Environment - Set to 'production' or 'development'
 * 
 * Affects the way errors are printed to the screen, and other things 
 */
$config['enivornment'] = 'production';

/**
 * Cache Method
 * 
 * Built-in methods are:
 *   FALSE     - Turn cache off
 *   'file'    - Cache to the filesystem
 *   others .. - Check the libs/cachey/Drivers folder
 */
$config['cache_method'] = FALSE;

/**
 * File Cache Options
 */
$config['cache_options']['file'] = array(
  'filepath'           => 'cache',
  'default_expiration' => 86400
);

/* EOF: config.php */