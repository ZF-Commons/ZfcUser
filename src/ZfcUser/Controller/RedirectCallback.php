<?php

namespace ZfcUser\Controller;

use Zend\Mvc\Application;
use Zend\Mvc\Router\RouteInterface;
use Zend\Mvc\Router\Exception;
use Zend\Http\PhpEnvironment\Response;
use ZfcUser\Options\ModuleOptions;

/**
 * Buils a redirect response based on the current routing and parameters
 */
class RedirectCallback
{

    /** @var RouteInterface  */
    private $router;

    /** @var Application */
    private $application;

    /** @var ModuleOptions */
    private $options;

    /**
     * @param Application $application
     * @param RouteInterface $router
     * @param ModuleOptions $options
     */
    public function __construct(Application $application, RouteInterface $router, ModuleOptions $options)
    {
        $this->router = $router;
        $this->application = $application;
        $this->options = $options;
    }

    /**
     * @return Response
     */
    public function __invoke()
    {
        $routeMatch = $this->application->getMvcEvent()->getRouteMatch();
        $redirect = $this->getRedirect($routeMatch->getMatchedRouteName(), $this->getRedirectRouteFromRequest());

        $response = $this->application->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $redirect);
        $response->setStatusCode(302);
        return $response;
    }

    /**
     * Return the redirect from param.
     * First checks GET then POST
     * @return string
     */
    private function getRedirectRouteFromRequest()
    {
        $request  = $this->application->getRequest();
        $redirect = $request->getQuery('redirect');
        if ($redirect && $this->routeExists($redirect)) {
            return $redirect;
        }

        $redirect = $request->getPost('redirect');
        if ($redirect && $this->routeExists($redirect)) {
            return $redirect;
        }

        return false;
    }

    /**
     * @param $route
     * @return bool
     */
     private function routeExists($route)
    {
        // first check if such rout/name exists
        // will throw exception if does not exist
        $namedRouteMatch=0;
        $urlRouteMatch=0;
        try{
             $this->router->assemble(array(), array('name' => $route));
         }catch (Exception\RuntimeException $e) {
            // didnt' match with any route name
            $match=0;
        }
        
        // now try to match url with routes
        try {
            $request=$this->application->getRequest();
            $request->setUri($route);
            $matchedRoute = $this->router->match($request);
            if($matchedRoute){
                $urlRouteMatch=1;
            }
        } catch (Exception\RuntimeException $e) {
            $urlRouteMatch=0;
        }
        // check if anyof them matched
       if($namedRouteMatch || $urlRouteMatch){
        return true;
       }
    }

    /**
     * Returns the url to redirect to based on current route.
     * If $redirect is set and the option to use redirect is set to true, it will return the $redirect url.
     *
     * @param string $currentRoute
     * @param bool $redirect
     * @return mixed
     */
    protected function getRedirect($currentRoute, $redirect = false)
    {
        $useRedirect = $this->options->getUseRedirectParameterIfPresent();
        $routeExists = ($redirect && $this->routeExists($redirect));
        if (!$useRedirect || !$routeExists) {
            $redirect = false;
        }

        switch ($currentRoute) {
            case 'zfcuser/register':
            case 'zfcuser/login':
            case 'zfcuser/authenticate':
                // if redirect is there and matches a route just return it
                if($redirect){
                    return $redirect;
                }
                // since its route name asseble and return string url
                $route =$this->options->getLoginRedirectRoute();
                return $this->router->assemble(array(), array('name' => $route));
                break;
            case 'zfcuser/logout':
                $route = ($redirect) ?: $this->options->getLogoutRedirectRoute();
                return $this->router->assemble(array(), array('name' => $route));
                break;
            default:
                return $this->router->assemble(array(), array('name' => 'zfcuser'));
        }
    }
}
