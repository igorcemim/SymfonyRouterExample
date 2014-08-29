<?php

namespace Config;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class Routes
{

    /**
     * 
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public static function getConfig()
    {
        $routes = new RouteCollection();
        $routes->add('foobar_home', 
            new Route('/{id}', array(
                    'controller' => 'Application\Controller\FoobarHome',
                    'action' => 'Index',
                ),
                array('id' => '([0-9]+)?')
            )
        );
        $routes->add('foobar_admin', 
            new Route('/foobar/admin', array(
                    'controller' => 'Application\Controller\FoobarAdmin',
                    'action' => 'Index',
                )
            )
        );
        
        return $routes;
    }

}
