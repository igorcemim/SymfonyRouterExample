<?php

namespace Application\Controller;

use Symfony\Component\Routing\Generator\UrlGenerator;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class ApplicationController
{

    /**
     *
     * @var UrlGenerator
     */
    protected $urlGenerator;

    /**
     *
     * @var array
     */
    protected $parameters;
    
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
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * 
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * 
     * @param string $name
     * @return string
     */
    public function getParameter($name)
    {
        return $this->parameters[$name];
    }

    /**
     * 
     * @param string $name
     * @param string $value
     */
    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * 
     * @param \Symfony\Component\Routing\Generator\UrlGenerator $urlGenerator
     */
    public function setUrlGenerator(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * 
     * @param string $name Nome da rota.
     * @return string Url gerada.
     */
    public function getRoute($name)
    {
        return $this->urlGenerator->generate($name);
    }
    
    /**
     * Redireciona o browser para uma URL.
     * 
     * @param string $url
     */
    public function redirect($url)
    {
        $this->getResponse()->setStatusCode(301);
        $this->getResponse()->headers->set('Location', $url);
    }

}
