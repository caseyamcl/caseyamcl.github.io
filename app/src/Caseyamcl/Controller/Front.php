<?php

namespace Caseyamcl\Controller;

/**
 * Calendar Controller
 */
class Front extends ControllerAbstract
{
    // --------------------------------------------------------------

    protected function init()
    {

        $this->addRoute('/',              'index');
        $this->addRoute('/code',          'indexsec');
        $this->addRoute('/work',          'indexsec');
        $this->addRoute('/presentations', 'indexsec');
    }

    // --------------------------------------------------------------

    public function index()
    {
        //View data array
        $data = array();

        //Content Loader and Crawler
        $loader  = $this->getLibrary('content');
        $crawler = $this->getLibrary('crawler');

        //General Items
        $data['items'] = $loader->getYamlItem('front/items.yml');

        //Content Items with Meta
        $data['articles'] = $crawler->getItems('articles', 'date_updated DESC');
        $data['work']     = $crawler->getItems('work',     'circa DESC');

        //Render the view
        return $this->render('pages/front', $data);
    }

    // --------------------------------------------------------------

    /**
     * Index Section: Adds a hash to the first path segment and redirects
     */
    public function indexsec()
    {
        //Get the first path segment
        $segs = array_filter(explode('/', $this->getPath()));

        //Redirect to /#[FPS]
        return (isset($segs[1]))
            ? $this->redirect('/#' . $segs[1])
            : $this->abort(404, "Page Not Found");
    }

}

/* EOF: Front.php */