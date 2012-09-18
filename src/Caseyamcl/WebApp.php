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

    public static function main($mode = self::PRODUCTION)
    {
        $that = new WebApp($mode);
        $that->run();
    }

    // --------------------------------------------------------------

    public function __construct($mode = self::PRODUCTION)
    {
        assert($mode == self::PRODUCTION OR $mode == self::DEVELOPMENT);
        $this->basepath = realpath(__DIR__ . '/../../') . '/';
        $this->mode = $mode;
    }

    // --------------------------------------------------------------

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

            //Send info to GoldenRetriever
            $this['contentMapper'] = $this->loadContentMapper();
            $this['contentObject'] = $this['contentMapper']->retrieveContent(
                $path, $this['acceptableTypes']
            );

            //Use HTTP Foundation to deliver response
            $this['response']->setStatusCode(200);
            $this['response']->headers->set('Content-Type', $this['contentObject']->getContent());
            $this['response']->setContent($this['contentObject']->getContent());
            $this['response']->prepare($this['request']);
            $this['response']->send();
        }
        catch (ContentNotFoundException $e) {
            $this->abort(404, 'Content Not Found');
        }
        catch (ContentTypeNontAvailableException $e) {
            $this->abort(415, 'Could not Negotiate Content Type');
        }
        catch (Exception $e) {

            if ($this->mode == self::DEVELOPMENT) {
                throw $e;
            }
            else {
                $this->abort(500, 'Internal Server Error Occured');
            }
        }
    }

    // --------------------------------------------------------------

    protected function abort($code, $msg = null)
    {
        //Start with null errorOutput
        $errorOutput = null;

        //Try to get error code to match the content type
        if (isset($this['contentMapper']) && $this['acceptableTypes']) {
            $errorOutput = $this['contentMapper']->retrieveError(
                $code, $this['acceptableTypes'], $msg
            );
        }

        //Fall back on simple text error
        if ( ! $errorOutput) {
            $errorOutput = sprintf("Error (%d): %s", $code, $msg);
        }

        if ($this['response']) {
            $this['response']->setStatusCode($code);
            $this['response']->setContent($errorOutput);
            //@TODO: $response->headers->set('Content-Type', 'text/plain');

            $this['response']->prepare($this['request']);
            $this['response']->send();
        }
        else {
            header("Content-type: text/plain");
            echo $errorOutput;
        }

        die();
    }

    // --------------------------------------------------------------

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

/* EOF: App.php */