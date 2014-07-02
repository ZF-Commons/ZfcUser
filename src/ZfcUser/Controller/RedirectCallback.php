<?php

namespace ZfcUser\Controller;

use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\Mvc\Router\RouteInterface;
use Zend\Mvc\Router\Exception;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use ZfcUser\Options\ModuleOptions;

class RedirectCallback
{
    /** @var RouteInterface */
    protected $router;

    /** @var Response */
    protected $response;

    /** @var Request */
    protected $request;

    /** @var ModuleOptions */
    protected $options;

    /**
     * @param RouteInterface $router
     * @param Response $response
     * @param Request $request
     * @param ModuleOptions $options
     */
    public function __construct(RouteInterface $router, Response $response, Request $request, ModuleOptions $options)
    {
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
        $response = $this->response;
        $response->getHeaders()->addHeaderLine('Location', $this->getRedirect($routeMatch->getMatchedRouteName(), $routeMatch->getParam('redirect', false)));
        $response->setStatusCode(302);
        return $response;
    }

    /**
     * @param $route
     * @return bool
     */
    protected function routeExists($route)
    {
        try{
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
        if (!$this->options->getUseRedirectParameterIfPresent() || ($redirect && !$this->routeExists($redirect))) {
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
