<?php

namespace ZfcUser\Controller;

use Zend\Mvc\Router\RouteInterface;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Exception;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use ZfcUser\Options\ModuleOptions;

class RedirectCallback
{
    /** @var RouteMatch */
    protected $routeMatch;

    /** @var RouteInterface  */
    protected $router;

    /** @var Response */
    protected $response;

    /** @var Request */
    protected $request;

    /** @var ModuleOptions */
    protected $options;

    /**
     * @param RouteMatch $router
     * @param Response $response
     * @param Request $request
     * @param ModuleOptions $options
     */
    public function __construct(RouteMatch $routeMatch, RouteInterface $router, Response $response, Request $request, ModuleOptions $options)
    {
        $this->routeMatch = $routeMatch;
        $this->router = $router;
        $this->request = $request;
        $this->response = $response;
        $this->options = $options;
    }

    /**
     * @return Response
     */
    public function __invoke()
    {
        $routeMatch = $this->router->match($this->request);

        $redirect = $this->getRedirect($routeMatch->getMatchedRouteName(), $this->getRedirectRouteFromRequest());

        $response = $this->response;
        $response->getHeaders()->addHeaderLine('Location', $redirect);
        $response->setStatusCode(302);
        return $response;
    }

    /**
     * Return the redirect from param.
     * First checks GET then POST
     * @return string
     */
    protected function getRedirectRouteFromRequest()
    {
        $request  = $this->request;
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
    protected function routeExists($route)
    {
        try {
            $this->router->assemble($route);
        } catch (Exception\RuntimeException $e) {
            return false;
        }
        return true;
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
            case 'zfcuser/login':
                $route = ($redirect) ?: $this->options->getLoginRedirectRoute();
                return $this->router->assemble([], ['name' => $route]);
                break;
            case 'zfcuser/logout':
                $route = ($redirect) ?: $this->options->getLogoutRedirectRoute();
                return $this->router->assemble([], ['name' => $route]);
                break;
            default:
                return $this->router->assemble([], ['name' => 'zfcuser']);
        }
    }
}
