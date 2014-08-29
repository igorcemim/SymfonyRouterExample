<?php

namespace Application\Core;

use Symfony\Component\Routing\Generator\UrlGenerator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Application\Exception\NotFoundException;

class FrontController
{

    /**
     * 
     * @param array $parameters
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Symfony\Component\Routing\Generator\UrlGenerator $generator
     */
    public function dispatch(
        array $parameters,
        Request $request = NULL,
        Response $response = NULL,
        UrlGenerator $generator = NULL
    )
    {
        try {
            $controller = new $parameters['controller'];
        } catch (\Exception $e) {
            throw new NotFoundException();
        }
        $controller->setUrlGenerator($generator);
        $controller->setParameters($parameters);
        $controller->setRequest($request);
        $controller->setResponse($response);

        $content = $controller->$parameters['action']();
        $response->setContent($content);
        $response->send();
    }

}
