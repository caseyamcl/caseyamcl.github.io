<?php

class Pagelister
{
	/**
	 * Pageloader
	 * @var Pageloader
	 */
	private $pageloader;
	
	/**
	 * Basepath
	 * @var string 
	 */
	private $page_basepath;
	
	private $get_types;
	private $get_pages;
	private $except_types;
	private $except_pages;
	private $order_by;
	private $offset;
	private $limit;
	
	/**
	 * State holder for sort paramaters
	 */
	private $curr_sort_field = NULL;
	
	// --------------------------------------------------------------

	/**
	 * Constructor
	 * 
	 * @param string $base_path
	 * @throws RuntimeException 
	 */
	public function __construct(Pageloader $pageloader, $base_path = 'pages')
	{
		//Path
		if ( ! is_readable(realpath($base_path)))
			throw new RuntimeException("Basepath $base_path not readable or does not exist");
		$this->page_basepath = realpath($base_path) . DIRECTORY_SEPARATOR;
		
		//Pageloader
		$this->pageloader = $pageloader;
		
		return $this;
	}
	
	// --------------------------------------------------------------

	/**
	 * Get Types of Content
	 * 
	 * @param type $types 
	 */
	public function get_types($types = 'all')
	{
		if ( ! is_array($types) && $types != 'all')
			$types = array_filter(array_map('trim', explode(',', $types)));
		
		$this->get_types = $types;
		
		return $this;		
	}
	
	// --------------------------------------------------------------
	
	public function get_pages($pages = 'all')
	{
		if ( ! is_array($pages) && $pages != 'all')
			$pages = array_filter(array_map('trim', explode(',', $pages)));
		
		$this->get_pages = $pages;
		
		return $this;		
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Exclude Types
	 * @param type $types 
	 */
	public function except_types($types)
	{
		if ( ! is_array($types))
			$types = array_filter(array_map('trim', explode(',', $types)));
		
		$this->except_types = $types;
		
		return $this;		
	}
	
	// --------------------------------------------------------------
	
	public function except_pages($pages)
	{
		if ( ! is_array($pages))
			$pages = array_filter(array_map('trim', explode(',', $pages)));
		
		$this->except_pages = $pages;		
		
		return $this;		
	}
	
	// --------------------------------------------------------------

	public function order_by($orders)
	{
		if ( ! is_array($orders))
			$orders = array_filter(array_map('trim', explode(",", $orders)));
		
		foreach($orders as &$order)
		{
			if (strpos($order, ' '))
				list($field, $ascdesc) = explode(' ', $order, 2);
			else
				list($field, $ascdesc) = array($order, 'asc');
			
			$this->order_by[$field] = strtolower($ascdesc);			
		}
		
		return $this;		
	}
	
	// --------------------------------------------------------------

	public function offset($offset)
	{
		$this->offset = (int) $offset;
		
		return $this;		
	}
	
	// --------------------------------------------------------------

	public function limit($limit)
	{
		$this->limit = (int) $limit;
		
		return $this;		
	}
	
	// --------------------------------------------------------------
	
	public function count()
	{
		//Return a count of the pages returned
		return $this->go(TRUE);
	}
	
	// --------------------------------------------------------------

	public function go($count_only = FALSE)
	{		
		//Get a full list of pages
		$pagelist = $this->scan_page_directory();

		//Filter by type, if applicable
		if ($this->get_types)
		{
			$pagelist = $this->filter($pagelist, 'page_type', $this->get_types);
		}
	
		//Filter by title, if applicable
		if ($this->get_pages)
			$pagelist = $this->filter($pagelist, 'title', $this->get_pages);
		
		//If none in the list after filtering, return here
		if (count($pagelist) == 0)
			return $pagelist;
		
		//Re-order, if applicable
		if ( ! $count_only && $this->order_by)
			$pagelist = $this->sort($pagelist, $this->order_by);
		
		//Return array slice if offset and/or limit exist
		if ($this->offset)
		{
			if (count($pagelist) >= $this->offset)
				$pagelist = array_slice($pagelist, $this->offset);
			else
				$pagelist = array();
		}
		
		if ($this->limit)
		{
			if (count($pagelist) >= $this->limit)
				$pagelist = array_slice($pagelist, 0, $this->limit);
		}
		
		return $pagelist;
	}
	
	// --------------------------------------------------------------

	private function sort($pagelist, $order_by_list)
	{
		foreach($order_by_list as $field => $ascdesc)
		{
			$this->curr_sort_field = $field;
			uasort($pagelist, array($this, 'sort_callback'));	
			
			if ($ascdesc == 'desc')
				$pagelist = array_reverse($pagelist);
			
			$this->curr_sort_field = NULL;
		}
		
		return $pagelist;
	}
	
	// --------------------------------------------------------------
	
	private function sort_callback($a, $b)
	{
		if ( ! $this->curr_sort_field)
			throw new RuntimeException("Using sort_callback out of context of sort method!");
		
		$field = $this->curr_sort_field;
		
		//Determine what fields to compare
		if (isset($a->$field) && isset($b->$field))
			list($a, $b) = array($a->$field, $b->$field);
		elseif (isset($a->$field) && ! isset($b->$field))
			list($a, $b) = array($a->$field, NULL);
		elseif ( ! isset($a->$field) && isset($b->$field))
			list($a, $b) = array(NULL, $b->$field);
		elseif (isset($a->page_meta->$field) && isset($b->page_meta->$field))
			list($a, $b) = array($a->page_meta->$field, $b->page_meta->$field);
		elseif (isset($a->page_meta->$field) && ! isset($b->page_meta->$field))
			list($a, $b) = array($a->page_meta->$field, NULL);
		elseif ( ! isset($a->page_meta->$field) && isset($b->page_meta->$field))
			list($a, $b) = array(NULL, $b->page_meta->$field);
		else //Neither exist
			return 0;

		return strcmp($a, $b);
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Filter the pagelist on certain criteria
	 * 
	 * @param array $pagelist
	 * @param string $field
	 * @param array $acceptable_values 
	 * @return array
	 */
	private function filter($pagelist, $field, $acceptable_values)
	{				
		foreach($pagelist as $k => &$pg)
		{			
			//Must have field and must be in acceptable values
			if (isset($pg->$field) && ! in_array($pg->$field, $acceptable_values))
				$pg = NULL;
			elseif (isset($pg->page_meta->$field) && ! in_array($pg->page_meta->$field, $acceptable_values))
				$pg = NULL;
			elseif ( ! isset($pg->$field) && ! isset($page->page_meta->$field))
				$pg = NULL;
		}
	
		return array_filter($pagelist);
	}
	
	// --------------------------------------------------------------

	/**
	 * Recursive directory to scan pages and return objects 
	 * 
	 * @param string $path
	 * URI path to start at
	 */
	private function scan_page_directory($path = NULL)
	{
		$pagelist = array();
		
		foreach(scandir($this->page_basepath . DIRECTORY_SEPARATOR . $path) as $file)
		{
			$filepath = $this->page_basepath . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $file;
			$uripath = $path . '/' . $file;
			
			//We're only interested in directories
			if ( ! is_dir($filepath))
				continue;
			
			//Ignore special pages except front, and ignore hidden files
			if ($file{0} == '.' OR ($file{0} == '_' && $file != '_front'))
				continue;
			
			//See if it's a page
			try {
				$page = $this->pageloader->load_page($uripath);
			} catch (Exception $e) {
				$page = FALSE;
			}
			
			//If so, add it, and see what's underneath
			if ($page)
			{			
				$pagelist[$page->page_path] = $page;
				$pagelist = array_merge($pagelist, $this->scan_page_directory($uripath));
			}
		}
		
		return $pagelist;
	}
	
}

/* EOF: pagelister.php */