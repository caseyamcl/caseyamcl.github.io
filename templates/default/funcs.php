<?php

/**
 * Draw a Menu
 * 
 * @param string $site_url
 * @param string $current_url
 * @param array $menu_items 
 */
function draw_menu($site_url, $current_url, $menu_items)
{
	$items = array();
	
	foreach($menu_items as $path => $display)
	{
		$item_url = $site_url . $path;
		
		if (strlen($path) == '' && $current_url == $site_url)
			$items[] = "<li class='current'><a href='$item_url' title='$display'>$display</a></li>";		
		elseif (strlen($path) > 0 && (strlen($current_url) >= strlen($item_url) && substr($current_url, 0, strlen($item_url)) == $item_url))
			$items[] = "<li class='current'><a href='$item_url' title='$display'>$display</a></li>";
		else
			$items[] = "<li><a href='$item_url' title='$display'>$display</a></li>";		
	}
	
	$html = implode("\n", $items);	
	return $html;
}

/* EOF: funcs.php */