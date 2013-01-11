<?php

namespace Caseyamcl\Controller;

/**
 * Resume Controller
 */
class Resume extends PagesAndAssets
{
    // --------------------------------------------------------------

    protected function loadRoutes()
    {
        $this->addRoute('/resume', 'index');
        $this->addRoute('/cv',     'index');
    }

    // --------------------------------------------------------------

    public function index()
    {
        $loader = $this->getLibrary('content');
        $data   = array('resume' => $loader->getYamlItem('resume/resume.yml'));
        return $this->render('pages/resume', $data);
    }
}

/* EOF: Resume.php */