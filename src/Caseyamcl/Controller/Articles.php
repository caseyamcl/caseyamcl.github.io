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

        //Do the action
        return ($rss)
            ? $this->feed()
            : $this->redirect('/#articles');
    }

    // --------------------------------------------------------------

    protected function feed($limit = 10)
    {
        $crawler = $this->getLibrary('crawler');

        //Scan the articles directory
        $articles = $crawler->getItems('articles', 'date_published DESC');

        //Limit
        if (count($articles) > $limit) {
            $articles = array_slice($articles, 0, $limit);
        }

        //Convert format
        $items = array();
        foreach($articles as $path => $meta) {

            $items[] = array(
                'title'   => $meta['title'],
                'summary' => $meta['summary'],
                'url'     => $this->getUrl($path),
                'pubDate' => $meta['date_published']
            );

        }

        //Render RSS template
        return $this->rss($items, 'Articles', 'Recent articles on CaseyMcLaughlin.com');
    }

    // --------------------------------------------------------------

    protected function getTemplateName()
    {
        return 'pages/article';
    }
}

/* EOF: Articles.php */