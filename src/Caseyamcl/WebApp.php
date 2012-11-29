<?php

namespace Caseyamcl;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Eloquent\Asplode\Asplode;
use Pimple;
use Exception;

use GoldenRetriever\ContentTypeNotAvailable as ContentTypeNotAvailableException,
    GoldenRetriever\ContentNotFound as ContentNotFoundException,
    GoldenRetriever\Parser\TwigEngine,
    GoldenRetriever\ContentObject;

/**
 * Main Web App File
 */
class WebApp extends Pimple
{
    const PRODUCTION  = 1;
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
        $this->basepath = realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR;
        $this->mode = $mode;
    }

    // --------------------------------------------------------------

    /**
     * Run
     */
    public function run()
    {
        //Register error handler
        Asplode::instance()->install();

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

            //Get the base and current URL
            $appUrl     = $this['request']->getSchemeAndHttpHost() . $this['request']->getBaseUrl();
            $currentUrl = $appUrl . $this['request']->getPathInfo();
            $baseUrl    = $this['request']->getSchemeAndHttpHost() . $this['request']->getBasePath();

            //Setup templateEngine
            $this['templateEngine'] = new TwigEngine();
            $this['templateEngine']->addGlobal('app_url', $appUrl);
            $this['templateEngine']->addGlobal('base_url', $baseUrl);
            $this['templateEngine']->addGlobal('current_url', $currentUrl);

            //Load GoldenRetriever
            $this['goldenRetriever'] = $this->loadGoldenRetriever();

            //Get the content object
            $contentObject = $this['goldenRetriever']->retrieveContent($path, $this['acceptableTypes']);

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
    protected function send(ContentObject $contentObject, $httpCode = 200)
    {
        //Get the content body
        $contentBody = $contentObject->getContent();
        $mimeType    = $contentObject->getRepresentation()->getKey();

        //Wrap the HTML and PDF versions of the content with a template
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
        //Driver
        $yaml = new \Symfony\Component\Yaml\Parser();
        $path = $this->basepath . 'content' . DIRECTORY_SEPARATOR;
        $driver = new \GoldenRetriever\Driver\FlatFile($path, $yaml);

        //Content Representations
        $driver->registerRepresentation(new \GoldenRetriever\Representation\Html());

        //Parsers
        $driver->registerParser('twig', $this['templateEngine']);
        $driver->registerParser('php', new \GoldenRetriever\Parser\PhpEngine());

        //Load the negotiator
        $negotiator = new \GoldenRetriever\Negotiator();

        //Load the mapper
        return new \GoldenRetriever\Retriever($driver, $negotiator);
    }
   
}

/* EOF: WebApp.php */