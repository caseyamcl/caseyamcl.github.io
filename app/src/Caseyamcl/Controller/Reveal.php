<?php

namespace Caseyamcl\Controller;

/**
 * Controller for Reveal Presentations, which are embedded
 * with presentation content material in the content/presentations
 * directory
 */
class Reveal extends ControllerAbstract
{
    private $indexContent;

    // --------------------------------------------------------------

    protected function init()
    {
        $this->addRoute('/reveal/{presentation}', 'index');
    }

    // --------------------------------------------------------------

    public function index($presentation)
    {
        //Get the content mapper
        $content = $this->getLibrary('content');

        //Location within the content folder
        $prezLocation = 'presentations/' . $presentation . '/presentation.html';
        $metaLocation = 'presentations/' . $presentation . '/meta.yml';

        //See if it exists
        if ($content->checkItemExists($prezLocation)) {

            $data = array(
                'content'      => $content->getItem($prezLocation),
                'presentation' => $content->getYamlItem($metaLocation) ?: array()
            );

            return $this->render('reveal', $data);



        }
        else {
            return $this->abort(404, "Presentation not found");
        }

    }
}

/* EOF: Reveal.php */