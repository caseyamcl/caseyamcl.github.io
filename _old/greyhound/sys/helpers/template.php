<?php

/**
 * 
 */
function get_page_lister()
{	
	global $c;
	return $c['pagelister'];
}

// --------------------------------------------------------------

/**
 * Image Helper Function
 * 
 * Render an image with auto pan-n-scan sizing
 * 
 * @param string $filename
 * @param string $desired_size
 * @param string $alt
 * @param string $page_path
 * @param string $attrs 
 * @return string
 */
function img($filename, $desired_size = '275x275', $alt = 'Image', $page_path = NULL, $attrs = NULL)
{
	//Assemble image path.  If not $page_path supplied, just use filename and assume
	//the path is in there
	
	//Resolve image path and make sure it exists
	
	//Check to see if the desired image already exists in the image library
	
	//If not, generate it
	
	//Return img html
}

// --------------------------------------------------------------



/* EOF: template.php */