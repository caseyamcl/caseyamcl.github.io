<?php

namespace Caseyamcl\Controller;

/**
 * Content Controller is the default controller for all content
 */
class Articles extends General
{
    protected function loadRoutes()
    {
        $this->addRoute('articles/{article_slug}', 'getContent');
    }

    protected function getTemplateName()
    {
        return 'pages/article';
    }

}

/* EOF: Articles.php */