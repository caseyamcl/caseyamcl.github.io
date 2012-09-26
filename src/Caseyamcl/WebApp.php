<?php

namespace Caseyamcl;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GoldenRetriever\ContentTypeNotAvailableException;
use GoldenRetriever\ContentNotFoundException;
use GoldenRetriever\TemplateEngine\TwigEngine;
use Pimple;
use Exception;

class WebApp extends Pimple
{
    const PRODUCTION = 1;
    const DEVELOPMENT = 2;

    // --------------------------------------------------------------

    /**
     * @var string
     */
    private $basepath;

    /**
     * @var int
     */
    private $mode;

    // --------------------------------------------------------------

    /**
     * Static Initiator
     *
     * @param int $mode
     */
    public static function main($mode = self::PRODUCTION)
    {
        $that = new WebApp($mode);
        $that->run();
    }

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param int $mode
     */
    public function __construct($mode = self::PRODUCTION)
    {
        assert($mode == self::PRODUCTION OR $mode == self::DEVELOPMENT);
        $this->basepath = realpath(__DIR__ . '/../../') . '/';
        $this->mode = $mode;
    }

    // --------------------------------------------------------------

    /**
     * Run
     */
    public function run()
    {
        try {

            //Load request
            $this['request'] = $this->share(function() {
                return Request::createFromGlobals();
            });

            //Load response
            $this['response'] = $this->share(function() {
                return new Response();
            });

            //Use HTTP Foundation to get the path
            $path    = $this['request']->getPathInfo();
            $aTypes  = $this['request']->getAcceptableContentTypes();

            //Turn the acceptableTypes into the form needed by GoldenRetriever
            $aTypes = array_reverse(array_flip(array_reverse($aTypes)));
            $this['acceptableTypes'] = array_map(function($v) {
                return $v / 10;
            }, $aTypes);

            //Setup templateEngine
            $this['templateEngine'] = new TwigEngine();

            //Load GoldenRetriever
            $this['goldenRetriever'] = $this->loadGoldenRetriever();

            //Build some context data to send to the content
            $contentData = array();
            $contentData['base_url'] = $this['request']->getSchemeAndHttpHost() . 
                $this['request']->getBaseUrl();
            $contentData['page_url'] = $contentData['base_url'] . 
                $this['request']->getPathInfo();
            $contentData['asset_url'] = dirname($contentData['base_url']);

            //Get the content object
            $contentObject = $this['goldenRetriever']->retrieveContent(
                $path, $this['acceptableTypes'], $contentData
            );

            //Send it
            $this->send($contentObject, 200);
        }
        catch (ContentNotFoundException $e) {
            $this->error(404, 'Content Not Found');
        }
        catch (ContentTypeNotAvailableException $e) {
            $this->error(415, 'Could not Negotiate Content Type');
        }
        catch (Exception $e) {

            if ($this->mode == self::DEVELOPMENT) {
                throw $e;
            }
            else {
                $this->error(500, 'Internal Server Error Occured');
            }
        }
    }

    // --------------------------------------------------------------

    /**
     * Handle errors
     *
     * @param int $code
     * @param string $msg
     */
    protected function error($code, $msg = null)
    {
        //Try to get error code to match the content type
        if (isset($this['contentMapper']) && $this['acceptableTypes']) {

            $errorContentObject = $this['contentMapper']->retrieveError(
                $code, $this['acceptableTypes'], $msg
            );
        }

        if (isset($errorContentObject) && $this['response']) {
            $this->send($errorContentObject, $code);
        }
        else { //Fall back on simple text error
            $errorOutput = sprintf("Error (%d): %s", $code, $msg);
            header("Content-type: text/plain");
            die($errorOutput);
        }
    }

    // --------------------------------------------------------------

    /**
     * Send content
     *
     * @param ContentObject $contentObject
     * @param int $httpCode
     */
    protected function send($contentObject, $httpCode = 200)
    {
        //Get the content body
        $contentBody = $contentObject->getContent();
        $mimeType    = $contentObject->getMimeType();

        //Perform some last minute template goodness for specific types
        switch ($mimeType) {
            case 'text/html':
                $contentBody = $this->applyTemplate('html.twig', $contentBody, $contentObject->getMeta());
            break;
            case 'application/pdf':
                $contentBody = $this->applyTemplate('pdf.twig', $contentBody, $contentObject->getMeta());
            break;
        }

        //Use HTTP Foundation to deliver response
        $this['response']->setStatusCode((int) $httpCode);
        $this['response']->headers->set('Content-Type', $mimeType);
        $this['response']->setContent($contentBody);
        $this['response']->prepare($this['request']);
        $this['response']->send();
    }

    // --------------------------------------------------------------

    /** 
     * Apply a template to ouptput content
     *
     * @param string $templateFile
     * @param string $content
     * @param array  $meta
     *
     * @return string
     */
    protected function applyTemplate($templateFile, $content, $meta)
    {
        $templateFile = $this->basepath . 'templates/' . $templateFile;

        if (is_readable($templateFile)) {

            //Template Contents
            $templateContent = file_get_contents($templateFile);

            //Add content to meta
            $meta['_content'] = $content;
            return $this['templateEngine']->render($templateContent, $meta);
        }
        else {
            $this->error('500', "Could not load expected template: " . $templateFile);
        }
    }

    // --------------------------------------------------------------

    /**
     * Load Golden Retriever Content Mapper
     *
     * @return \GoldenRetriever\Retriever
     */
    protected function loadGoldenRetriever()
    {
        //Load the contentTypes into an array
        $contentTypes = array();
        $contentTypes[] = new \GoldenRetriever\ContentType\Html();            

        //Load the desired driver
        $contentPath = $this->basepath . 'content/';
        $parser = new \Symfony\Component\Yaml\Parser();
        $driver = new \GoldenRetriever\Driver\FlatFile($contentTypes, $parser, $contentPath);

        //Set the template engine
        $driver->setTemplateEngine($this['templateEngine']);

        //Load the negotiator
        $negotiator = new \GoldenRetriever\Negotiator();

        //Load the mapper
        return new \GoldenRetriever\Retriever($driver, $negotiator);
    }
}

/* EOF: WebApp.php */