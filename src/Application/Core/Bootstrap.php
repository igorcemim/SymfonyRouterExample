<?php

namespace Application\Core;

use Config\Routes as RoutesConfig;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Bootstrap
{

    /**
     *
     * @var \Symfony\Component\Routing\Generator\UrlGenerator
     */
    protected $generator;
    /**
     *
     * @var \Application\FrontController
     */
    protected $frontController;
    /**
     *
     * @var \Symfony\Component\Routing\Matcher\UrlMatcher
     */
    protected $matcher;
    /**
     *
     * @var \Symfony\Component\Routing\RequestContext
     */
    protected $context;
    /**
     *
     * @var \Config\Routes
     */
    protected $routes;
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\Request 
     */
    protected $request;
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        if ($this->response === NULL) {
            $this->response = new Response();
        }

        return $this->response;
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return \Application\Bootstrap
     */
    public function setResponse(\Symfony\Component\HttpFoundation\Response $response)
    {
        $this->response = $response;
        return $this;
    }
    
    /**
     * 
     * @return \Application\FrontController
     */
    public function getFrontController()
    {
        return $this->frontController;
    }

    /**
     * 
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        if ($this->request === NULL) {
            $this->request = Request::createFromGlobals();
        }
        
        return $this->request;
    }

    /**
     * 
     * @param \Application\FrontController $frontController
     * @return \Application\Bootstrap
     */
    public function setFrontController(\Application\FrontController $frontController)
    {
        $this->frontController = $frontController;
        return $this;
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Application\Bootstrap
     */
    public function setRequest(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->request = $request;
        return $this;
    }
    
    /**
     * 
     * @return \Symfony\Component\Routing\Generator\UrlGenerator
     */
    public function getGenerator()
    {
        if ($this->generator === NULL) {
            $this->generator = new UrlGenerator($this->routes, $this->getContext());
        }

        return $this->generator;
    }

    /**
     * 
     * @return \Application\FrontController
     */
    public function getDispatcher()
    {
        return $this->frontController;
    }

    /**
     * @return \Symfony\Component\Routing\Matcher\UrlMatcher
     */
    public function getMatcher()
    {
        if ($this->matcher === NULL) {
            $this->matcher = new UrlMatcher($this->routes, $this->getContext());
        }

        return $this->matcher;
    }

    /**
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * 
     * @param \Application\Symfony\Component\Routing\Generator\UrlGenerator $generator
     * @return \Application\Bootstrap
     */
    public function setGenerator(Symfony\Component\Routing\Generator\UrlGenerator $generator)
    {
        $this->generator = $generator;
        return $this;
    }

    /**
     * 
     * @param \Application\FrontController $dispatcher
     * @return \Application\Bootstrap
     */
    public function setDispatcher(FrontController $dispatcher)
    {
        $this->frontController = $dispatcher;
        return $this;
    }

    /**
     * 
     * @param \Application\Symfony\Component\Routing\Matcher\UrlMatcher $matcher
     * @return \Application\Bootstrap
     */
    public function setMatcher(Symfony\Component\Routing\Matcher\UrlMatcher $matcher)
    {
        $this->matcher = $matcher;
        return $this;
    }

    /**
     * 
     * @param array $routes
     * @return \Application\Bootstrap
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
        return $this;
    }
    
    public function __construct()
    {
        $this->routes = RoutesConfig::getConfig();
        $this->request = $this->getRequest();
        $this->context = $this->getContext();
        $this->matcher = $this->getMatcher();
        $this->generator = $this->getGenerator();
        $this->frontController = new FrontController();
    }

    /**
     * 
     * @return \Symfony\Component\Routing\RequestContext
     */
    private function getContext()
    {
        if ($this->context === NULL) {
            $this->context = new RequestContext();
            $this->context->fromRequest($this->request);
        }
        
        return $this->context;
    }

    /**
     * 
     * @param \Symfony\Component\Routing\RequestContext $context
     * @return string Caminho da requisição atual sem baseUrl e query string.
     */
    private function getRelativeUrl(RequestContext $context)
    {
        $baseurlRegex = '/^' . preg_quote($context->getBaseUrl(), '/') . '/';
        $queryRegex = '/\?' . preg_quote($context->getQueryString(), '/') . '$/';
        $path = preg_replace(
                                array(
                                    $baseurlRegex,
                                    $queryRegex,
                                ),
                                NULL,
                                $_SERVER['REQUEST_URI']
                            );
        
        return $path;
    }

    /**
     * Roda a aplicação.
     */
    public function run()
    {
        $errorController = array(
            'controller' => 'Application\Controller\NotFound',
            'action' => 'Index',
        );
        // obtém a URL atual relativa
        $currentUrl = $this->getRelativeUrl($this->context);

        // verifica se a URL atual ($path) combina com alguma das rotas
        try {
            $parameters = $this->matcher->match($currentUrl);

            // cria e executa o controller
            try {
                $this->frontController->dispatch(
                    $parameters,
                    $this->getRequest(),
                    $this->getResponse(),
                    $this->generator
                );
            } catch (NotFoundException $e) {
                $this->frontController->dispatch(
                    $errorController,
                    $this->getRequest(),
                    $this->getResponse(),
                    $this->generator
                );
            }

        } catch (ResourceNotFoundException $e) {
            $this->frontController->dispatch(
                $errorController,
                $this->getRequest(),
                $this->getResponse(),
                $this->generator
            );
        }
        
    }
    
    public function dispatcher()
    {
        
    }

}
