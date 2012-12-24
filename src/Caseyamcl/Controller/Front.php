<?php

namespace Caseyamcl\Controller;

/**
 * Calendar Controller
 */
class Front extends ControllerAbstract
{
    private $indexContent;

    // --------------------------------------------------------------

    protected function init()
    {
        $contentLoader      = $this->getLibrary('content');
        $this->indexContent = $contentLoader->getYamlItem('front/items.yml');

        $this->addRoute('/',              'index');
        $this->addRoute('/articles',      'indexsec');
        $this->addRoute('/code',          'indexsec');
        $this->addRoute('/work',          'indexsec');
        $this->addRoute('/presentations', 'indexsec');
    }

    // --------------------------------------------------------------

    public function index()
    {
        $data = array('items' => $this->indexContent);
        return $this->render('pages/front', $data);
    }

    // --------------------------------------------------------------

    /**
     * @TODO: Debug this
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