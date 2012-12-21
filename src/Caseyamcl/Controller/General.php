<?php

namespace Caseyamcl\Controller;

/**
 * Content Controller is the default controller for all content
 */
class General extends ControllerAbstract
{
    /**
     * @var Caseyamcl\ContentRetriever\Page
     */
    private $pageLoader;

    /**
     * @var Caseyamcl\ContentRetriever\Page
     */
    private $assetLoader;

    // --------------------------------------------------------------
 
    /**
     * Initialize
     */
    public function init()
    {
        //Load routes
        $this->loadRoutes();

        //Load resources
        $this->pageLoader  = $this->getLibrary('pages');
        $this->assetLoader = $this->getLibrary('assets');        
    }


    // --------------------------------------------------------------

    /**
     * Load routes 
     *
     * Match all routes that aren't the front page or haven't already been defined by
     * other controllers.
     */
    protected function loadRoutes()
    {
        $this->routes->match('{url}', array($this, 'getContent'))->assert('url', '.+');
    }

    // --------------------------------------------------------------

    /**
     * Default route for everything
     */
    public function getContent()
    {
        //Get the page from the path
        $path = ltrim($this->getPath(), '/');

        //Is content a page?  Great - Load page
        if ($this->pageLoader->pageExists($path)) {
            return $this->loadPage($path);
        }

        //Is content an asset?  Stream that shtuff
        elseif ($this->assetLoader->assetExists($path)) {
            return $this->loadAsset($path);
        }

        //Else, 404
        else {
            return $this->abort(404, 'Content not found');
        }
    }

    // --------------------------------------------------------------

    /**
     * Load a page
     *
     * @param string $path
     * @param string $data
     * @param string $template
     */
    protected function loadPage($path, array $data = array(), $template = 'default')
    {
        $data = array();
        $data['content'] = $this->pageLoader->getContent($path);

        $data = array_merge($data, $this->getData($path));

        //Load it
        return $this->render($template, $data);
    }

    // --------------------------------------------------------------

    /**
     * Get data for the page
     *
     * Meant to be overridden depending on the page type
     *
     * @param  string 
     * @return array
     */
    protected function getData($path)
    {
        return $this->pageLoader->getMeta($path);
    }

    // --------------------------------------------------------------

    /**
     * Load an asset
     *
     * @param string $path
     */
    protected function loadAsset($path)
    {
        $mime  = $this->assetLoader->getMime($path);

        $callback = function() use ($assetLoader, $path) {
            $this->assetLoader->streamAsset($path);
        };

        return $this->stream($callback, $mime);
    }
}

/* EOF: General.php */