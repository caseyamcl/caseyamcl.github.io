<?php

namespace Caseyamcl\ContentRetriever;

use Symfony\Component\Yaml\Yaml;

/**
 * Page loader
 */
class Page
{
    /**
     * @var ContentMap $contentMap
     */
    private $contentMap;

    /**
     * @var Symfony\Component\Yaml\Yaml;
     */
    private $yamlParser;

    /**
     * @var string
     */
    private $pageFile = 'content.html.twig';

    /**
     * @var string
     */
    private $metaFile = 'meta.yml';
   
    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param contentMap $contentMap
     */
    public function __construct(ContentMap $contentMap, Yaml $parser)
    {
        $this->contentMap = $contentMap;
        $this->yamlParser = $parser;
    }

    // --------------------------------------------------------------

    /**
     * Get the content
     *
     * @return string|null  Null if no content found
     */
    public function getContent($path)
    {
        return $this->contentMap->getItem($path, $this->pageFile) ?: null;
    }

    // -------------------------------------------------------------- 

    public function pageExists($path)
    {
        return (boolean) $this->contentMap->checkItemExists($path, $this->pageFile);
    }

    // -------------------------------------------------------------- 

    /**
     * Get the meta for this content
     *
     * @param  string      $path
     * @return array|null  Empty array if no meta; null if content doesn't exist
     */
    public function getMeta($path)
    {
        $rawYaml = $this->contentMap->getItem($path, $this->metaFile);

        if ($rawYaml) {
            return $this->yamlParser->parse($rawYaml);
        }
        else {
            return ($this->pageExists($path)) ? array() : null;
        }
    }
}

/* EOF: Page.php */