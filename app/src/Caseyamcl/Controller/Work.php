<?php

namespace Caseyamcl\Controller;

/**
 * Work Controller
 */
class Work extends PagesAndAssets
{
    protected function loadRoutes()
    {
        $this->addRoute('/work/{work}', 'getContent');
    }

    // --------------------------------------------------------------

    /**
     * Get template name
     *
     * @return string
     */
    protected function getTemplateName()
    {
        return 'pages/work';
    }       
}

/* EOF: Work.php */