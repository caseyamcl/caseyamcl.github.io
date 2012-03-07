<?php

/**
 * Imgsize Class
 */
class Imgsizer
{
	/**
	 * A string representing where to save the image when resized
	 * @var string 
	 */
	private $save_file_path;
	
	/**
	 * Resize class from vendor directory
	 * @var Resize $resize
	 */
	private $resize;
	
	// --------------------------------------------------------------		
		
	public function __construct(Resize $resize, $save_file_path)
	{
		$this->resize = $resize;
		
		//@TODO: Ensure that the save_file_path is writable!
		$this->save_file_path = $save_file_path;
	}

	// --------------------------------------------------------------		

	public function get_sized_image($img_path, $width, $height)
	{
		//Check for existing resized image and return path for that
		//-or- generate one, and return path for that
	}
	
	// --------------------------------------------------------------		

	/**
	 * Resize it, save it, and return the filepath
	 * 
	 * @param string $img_path 
	 * @param int $width
	 * @param int $height
	 * @return string
	 */
	private function resize($img_path, $width, $height)
	{
		//Resize image using the resize class
		
		//Return image path
	}
}

/* EOF: imgsizer.php */