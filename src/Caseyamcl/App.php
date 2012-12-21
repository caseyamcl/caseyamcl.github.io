<?php

namespace Caseyamcl;

use Silex\Application as SilexApplication;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TwigServiceProvider;
use RuntimeException, Exception;

/**
 * Main DL2SL Application Library
 */
class App extends SilexApplication
{
    const DEVELOPMENT = 1;
    const PRODUCTION  = 2;
    const MAINTENANCE = 3;
     
    // --------------------------------------------------------------

    /**
     * Static entry point for application
     *
     * Will run CLI or web dependening on if running CLI mode or not
     *
     * @param int $mode
     */
    public static function main($mode = self::PRODUCTION)
    {
        $className = get_called_class();
        $that = new $className($mode);
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
        //Construct the parent
        parent::__construct();

        //Set Basepath and appath
        $this['basepath'] = realpath(__DIR__ . '/../../');
        $this['srcpath']  = realpath(__DIR__);

        //Mode
        $this['site_mode'] = $mode;

        //App mode
        if ($mode == self::DEVELOPMENT) {
            $this['debug'] = true;
        }

        //Load common libraries
        $this->loadCommonLibraries();
    }

    // --------------------------------------------------------------

    public function run()
    {
        //Before Hook
        $this->before(array($this, 'loadWebLibraries'));

        //Error Hook
        $this->error(array($this, 'doError'));

        //Maintenance mode?
        if ($this['site_mode'] == self::MAINTENANCE) {
            $this->doMaintenance();
        }
        else {            

            //Load controllers
            $this['controller_general']  = new Controller\General();
            $this['controller_front']    = new Controller\Front();
            $this['controller_calendar'] = new Controller\Calendar($this['calendar']);

            //Mount controllers
            $this->mount('', $this['controller_calendar']);
            $this->mount('', $this['controller_front']);

            //Mount general controller LAST!
            $this->mount('', $this['controller_general']);
        }

        //Go
        parent::run();        
    }

    // --------------------------------------------------------------

    /**
     * Load common libraries
     * 
     * Needed for both CLI and web application
     */
    protected function loadCommonLibraries()
    {
        //Pointer for anonymous functions
        $app =& $this;

        //Guzzle Library (for external content)
        $this['guzzle'] = $this->share(function() {
            return new \Guzzle\Http\Client();
        });

        $this['content'] = $this->share(function() use ($app) {
            return new ContentRetriever\ContentMap($app['basepath'] . '/content');
        });

        $this['pages'] = $this->share(function() use ($app) {
            $yaml = new \Symfony\Component\Yaml\Yaml();
            return new ContentRetriever\Page($app['content'], $yaml);
        });

        $this['assets'] = $this->share(function() use ($app) {
            return new ContentRetriever\Asset($app['content']);
        });

        $this['calendar'] = $this->share(function() use ($app) {
            return new GoogleCalendar\Scraper($app['guzzle']);
        });
    }

    // --------------------------------------------------------------

    /**
     * Hook after request is generated but before controller is loaded
     */
    public function loadWebLibraries()
    {
        //Some additional info about the path at runtime
        $this['url.base']     = $this['request']->getSchemeAndHttpHost() . $this['request']->getBasePath();
        $this['url.app']      = $this['request']->getSchemeAndHttpHost() . $this['request']->getBaseUrl();
        $this['url.current']  = $this['url.app'] . $this['request']->getPathInfo();

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        //Pointer for lexical functions
        $app =& $this;

        //$this['url_generator']
        $this->register(
            new UrlGeneratorServiceProvider()
        );

        //$this['twig']
        $this->register(new TwigServiceProvider(), array(
            'twig.path'           => $this['basepath']  . '/templates',
            'twig.form.templates' => array('form_div_layout.html.twig', 'forms.html.twig')
        ));

        //Add additional information to Twig
        $this['twig'] = $this->share($this->extend('twig', function($twig, $app) {

            //URL globals
            $twig->addGlobal('base_url',    $app['url.base']);
            $twig->addGlobal('site_url',    $app['url.app']);
            $twig->addGlobal('current_url', $app['url.current']);

            //debug method
            if ($app['debug'] == true) {
                $twig->addExtension(new \Twig_Extension_Debug());
            }

            return $twig;
        }));      
    }

    // --------------------------------------------------------------

    /**
     * Handles errors when in production mode
     *
     * @param \Exception $exception
     * @param int $code
     */
    public function doError(Exception $exception, $code)
    {
        //Load web libraries up
        if ( ! isset($this['twig'])) {
            @$this->loadWebLibraries();
        }

        //Do the error
        switch ($code) {

            case 404:
                return $this['twig']->render('404.html.twig');
            break;
            default:

                //If Debug, do default (just return to cause this behavior)
                if ($this['debug']) {
                    return;
                }
                elseif (isset($this['twig'])) { //If we've loaded twig...
                    return new Response($this['twig']->render('error.html.twig'));
                }
                else {
                    return new Response(
                        "<html><head><title>DL2SL</title></head><body>Something went wrong.</title></head></html>"
                    );
                }
            break;
        }
    }

    // --------------------------------------------------------------

    /**
     * Set all routes to point ot the maintenance view
     */
    public function doMaintenance()
    {
        //Pointer
        $app =& $this;

        //Anonymous function
        $maint = function() use ($app) {
            $data = $app['twig']->render('maintenance.html.twig');
            return new Response($data, 503);
        };

        //All routes point to maintenance
        $this->match('/',     $maint);
        $this->match('{url}', $maint)->assert('url', '.+');        
    }
}

/* EOF: App.php */