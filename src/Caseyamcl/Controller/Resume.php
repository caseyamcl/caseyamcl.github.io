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
        return $this->render('pages/resume', array());
    }
}

/* EOF: Resume.php */