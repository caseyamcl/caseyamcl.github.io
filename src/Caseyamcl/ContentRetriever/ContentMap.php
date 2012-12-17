<?php

namespace Caseyamcl\ContentRetriever;

use RuntimeException;

/**
 * Content Map Class
 */
class ContentMap
{
    /**
     * @var string
     */
    private $basepath;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param string $basepath
     */
    public function __construct($basepath)
    {
        if ( ! is_readable($basepath)) {
            throw new RuntimeException('Could not read basepath');
        }

        $this->basepath = realpath($basepath) . DIRECTORY_SEPARATOR;
    }

    // -------------------------------------------------------------- 

    /**
     * Check if item exists
     *
     * @param string $path
     * @param string $item
     * @return boolean
     */
    public function checkItemExists($path, $item)
    {
        return (boolean) $this->resolvePath($path, $item);
    }

    // -------------------------------------------------------------- 

    /**
     * Get an item
     *
     * @param string $path
     * @param string $item
     * @return string|boolean
     */
    public function getItem($path, $item)
    {
        $fullpath = $this->resolvePath($path, $item);
        return ($fullpath) ? file_get_contents($fullpath) : false;
    }

    // --------------------------------------------------------------

    /**
     * Stream an item.  To be used as a callback most of the time
     *
     * @param string $path
     * @param string $item
     * @return void  Will echo contents of item
     */
    public function streamItem($path, $item)
    {
        $fullpath = $this->resolvePath($path, $item);

        if ($fullpath) {
            readfile($fullpath);
        }
        else {
            echo '';
        }
    }

    // --------------------------------------------------------------

    /**
     * Resolve the full path of an item
     *
     * @param  string $path  Path to content
     * @param  string $file  Filename
     * @return string|boolean
     */
    private function resolvePath($path, $file)
    {
        $path = ($path == '/')
            ? ''
            : trim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            
        $rpath = $this->basepath . $path . $file;
        return (is_readable($rpath)) ? $rpath : false;
    }
}

/* EOF: Page.php */