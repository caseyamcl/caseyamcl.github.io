<?php

namespace Caseyamcl\Controller;

/**
 * Calendar Controller
 */
class Front extends General
{
    // --------------------------------------------------------------

    protected function loadRoutes()
    {
        $this->addRoute('/', 'index');
    }

    // --------------------------------------------------------------

    public function index()
    {
        return $this->loadPage('/', array(), 'front');
    }
}

/* EOF: Front.php */