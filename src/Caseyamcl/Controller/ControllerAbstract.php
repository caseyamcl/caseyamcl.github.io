<?php

namespace Caseyamcl\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Abstract Controller for simplifying code in other controllers
 */
abstract class ControllerAbstract implements ControllerProviderInterface
{
    const ALL = 1;

    /**
     * @var Silex\App
     */
    private $app;

    /**
     * @var Silex\ControllerCollection
     */
    protected $routes;

    // --------------------------------------------------------------

    /**
     * Connect method
     *
     * @param Silex\Application $app
     */
    public function connect(Application $app)
    {
        //Setup the app as a controller variable
        $this->app = $app;
        $this->routes = $app['controllers_factory'];

        //Run the child method
        $this->init();

        return $this->routes;
    }

    // --------------------------------------------------------------

    /**
     * Initialize method
     *
     * Should load $this->addRoute() for whatever
     * routes to register with the controller and do any other runtime setup
     */
    abstract protected function init();

    // --------------------------------------------------------------

    /**
     * Add a route for this controller
     *
     * @param string $path
     * @param string $classMethod
     * @param int|string|array $methods
     */
    protected function addRoute($path, $classMethod, $methods = self::ALL)
    {
        if ( ! method_exists($this, $classMethod)) {
            throw new \InvalidArgumentException(sprintf(
                "Cannot register route '%s' class method '%s' is not callable",
                $path,
                $classMethod
            ));
        }

        if ($methods == self::ALL) {
            $this->routes->match($path, array($this, $classMethod));            
        }
        else {

            if (is_array($methods)) {
                $methods = implode("|", $methods);
            }

            $this->routes->match($path, array($this, $classMethod))->method($methods);
        }
    }

    // --------------------------------------------------------------

    /**
     * Render JSON
     *
     * @param array|object $data
     * @param boolean $wrapper
     */
    protected function json($data, $wrapper = true)
    {
        //Wrap it up?
        if ($wrapper) {
            $data = array(
                'api_version' => 1,
                'data'        => $data
            );
        }

        return $this->app->json($data);
    }

    // --------------------------------------------------------------

    /**
     * Render
     *
     * Renders a template
     *
     * @param string $view              View filename (in Views directory)
     * @param string|boolean $template  Template filename (in Views directory) or false
     * @param return string             The rendered output
     */
    protected function render($view, $data = array())
    {
        //File Extension
        $view .= '.html.twig';
        return $this->app['twig']->render($view, $data);      
    }

    // --------------------------------------------------------------

    /**
     * Abort
     *
     * @int $code
     * @string $message
     */
    protected function abort($code, $message = null)
    {
        return $this->app->abort($code, $message);
    }

    // --------------------------------------------------------------

    /**
     * Get query string parameters from input
     *
     * @param string|null $which
     * @param boolean     $strict  If $which and $strict, will fail on missing parameter
     * @return array|mixed|null
     */
    protected function getQueryParams($which = null, $strict = false)
    {
        $params = $this->app['request']->query->all();

        if ($which && ! isset($params[$which]) && $strict) {
            throw new HttpException(400, "Expected paramter '$which' missing from query");
        }
        elseif ($which) {
            return (isset($params[$which])) ? $params[$which] : null;
        }
        else {
            return $params;
        }
    }

    // --------------------------------------------------------------

    /**
     * Get post parameters from input
     *
     * @param string|null $which     
     * @param boolean     $strict  If $which and $strict, will fail on missing parameter
     * @return array|mixed|null
     */
    protected function getPostParams($which = null, $strict = false)
    {
        $params = $this->app['request']->request->all();

        if ($which && ! isset($params[$which]) && $strict) {
            throw new HttpException(400, "Expected paramter '$which' missing from request");
        }
        elseif ($which) {
            return (isset($params[$which])) ? $params[$which] : null;
        }
        else {
            return $params;
        }
   }

    // --------------------------------------------------------------

    /**
     * Get a library from the DiC
     *
     * @param string $name
     * @return object
     */
    public function getLibrary($name)
    {
        return $this->app[$name];
    }

    // --------------------------------------------------------------

    /**
     * Get the path requested
     *
     * @return string
     */
    protected function getPath()
    {
        return $this->app['request']->getPathInfo();
    }

    // --------------------------------------------------------------

    /**
     * Redirect to another path in the app
     *
     * @param   string $path
     * @param   boolean $external
     * @return  Redirection (halts app and redirects)
     */
    protected function redirect($path, $external = false)
    {
        //Ensure left slash, but no right slash
        $url = ($external)
            ? $this->app['url.app'] . '/' . trim($path, '/')
            : $path;

        //Do it
        return $this->app->redirect($url . $path);
    } 

    // --------------------------------------------------------------

    /**
     * Check if the client expects a certain content-type
     *
     * e.g.  if ($this->clientExpects('application/json'))
     *       ...
     *
     * Can use shorthand: 'html', 'json', 'xml'
     * 
     * @param string|array $mimeType
     * @param boolean      $strict  If false, then *\/* will always return true
     * @return boolean
     */
    protected function clientExpects($mimeType, $strict = true)
    {
        //Shorthand mappings
        $mappings = array(
            'json'  => array('application/json'),
            'html'  => array('text/html', 'application/xhtml+xml'),
            'xml'   => array('application/xml')
        );

        //Using shorthand?
        $expected = (isset($mappings[$mimeType])) 
            ? $mappings[$mimeType] : (array) $mimeType;

        //Client Accept Headers
        $accepted = $this->app['request']->getAcceptableContentTypes();
        
        //Return results
        if ($strict) {
            return count(array_intersect($expected, $accepted)) > 0;
        }
        else {
            return count(array_intersect($expected, $accepted)) > 0 OR in_array('*/*', $accepted);
        }
    }

    // --------------------------------------------------------------    

    /**
     * Streaming Download
     *
     * @param string $data      Streamable data
     * @param string $mime      Mime-type
     */
    protected function stream($callback, $mime = 'application/octet-stream')
    {
        //Download headers
        $headers = array();
        $headers['Content-type'] = $mime;
        
        return $this->app->stream($callback, 200, $headers); 
    }        
}

/* EOF: ControllerAbstract.php */