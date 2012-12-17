<?php

namespace Caseyamcl\Controller;

/**
 * Content Controller is the default controller for all content
 */
class General extends ControllerAbstract
{
    /**
     * Initialize
     */
    public function init()
    {
        $this->addRoute('/', 'getContent');
        $this->routes->match('{url}', array($this, 'getContent'))->assert('url', '.+');
    }

    // --------------------------------------------------------------

    /**
     * Default route for everything
     */
    public function getContent()
    {
        $pageLoader  = $this->getLibrary('pages');
        $assetLoader = $this->getLibrary('assets');

        //Get the page from the path
        $path = ltrim($this->getPath(), '/');

        //Is content a page?  Great - Load page
        if ($pageLoader->pageExists($path)) {
            return $this->loadPage($path);
        }

        //Is content an asset?  Stream that shtuff
        elseif ($assetLoader->assetExists($path)) {
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
     */
    protected function loadPage($path)
    {
        $pageLoader = $this->getLibrary('pages');

        $meta       = $pageLoader->getMeta($path);
        $rawContent = $pageLoader->getContent($path);

        $meta['content'] = $rawContent;

        //If there is a 'type' variable in the meta, look for that
        //template; else, load default template
        return (isset($meta['type']))
            ? $this->render($meta['type'], $meta)
            : $this->render('default', $meta);
    }

    // --------------------------------------------------------------

    /**
     * Load an asset
     *
     * @param string $path
     */
    protected function loadAsset($path)
    {
        $assetLoader = $this->getLibrary('assets');
        $mime  = $assetLoader->getMime($path);

        $callback = function() use ($assetLoader, $path) {
            $assetLoader->streamAsset($path);
        };

        return $this->stream($callback, $mime);
    }
}

/* EOF: General.php */