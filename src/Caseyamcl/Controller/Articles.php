<?php

namespace Caseyamcl\Controller;

/**
 * Content Controller is the default controller for all content
 */
class Articles extends PagesAndAssets
{
    protected function loadRoutes()
    {
        $this->addRoute('/articles', 'articleList');
        $this->addRoute('/articles/{article_slug}', 'getContent');
    }

    // --------------------------------------------------------------
    
    public function articleList()
    {
        //See if RSS is expected
        $rss = ($this->clientExpects('application/rss+xml') 
            OR $this->getQueryParams('feed') == 'rss'
        );

        if ($rss) {
            return $this->feed();
        }
        else {
            return $this->redirect('/#articles');
        }
    }

    // --------------------------------------------------------------

    protected function feed($limit = 10)
    {
        //Scan the articles directory

        //Sort by date

        //Render RSS template
    }

    // --------------------------------------------------------------

    protected function getTemplateName()
    {
        return 'pages/article';
    }
}

/* EOF: Articles.php */