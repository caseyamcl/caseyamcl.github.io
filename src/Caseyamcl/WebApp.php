<?php

namespace Caseyamcl;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GoldenRetriever\ContentTypeNontAvailableException;
use GoldenRetriever\ContentNotFoundException;
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

            //Load the GoldenRetriever
            $this['goldenRetriever'] = $this->loadContentMapper();

            //Get the content object
            $contentObject = $this['goldenRetriever']->retrieveContent(
                $path, $this['acceptableTypes']
            );

            //Send it
            $this->send($contentObject, 200);
        }
        catch (ContentNotFoundException $e) {
            $this->error(404, 'Content Not Found');
        }
        catch (ContentTypeNontAvailableException $e) {
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
        //Use HTTP Foundation to deliver response
        $this['response']->setStatusCode((int) $httpCode);
        $this['response']->headers->set('Content-Type', $contentObject->getContent());
        $this['response']->setContent($contentObject->getContent());
        $this['response']->prepare($this['request']);
        $this['response']->send();
    }

    // --------------------------------------------------------------

    /**
     * Load Content Mapper
     *
     * @return \GoldenRetriever\Mapper
     */
    protected function loadContentMapper()
    {
        //Load the contentTypes
        $contentTypes = array();
        $contentTypes[] = new \GoldenRetriever\ContentType\Html();

        //Load the desired driver
        $contentPath = $this->basepath . 'content/';
        $parser = new \Symfony\Component\Yaml\Parser();
        $driver = new \GoldenRetriever\Driver\FlatFile($contentTypes, $parser, $contentPath);

        //Load the negotiator
        $negotiator = new \GoldenRetriever\Negotiator();

        //Load the mapper
        return new \GoldenRetriever\Mapper($driver, $negotiator);
    }
}

/* EOF: WebApp.php */