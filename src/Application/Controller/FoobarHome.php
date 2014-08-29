<?php

namespace Application\Controller;

class FoobarHome extends ApplicationController
{

    public function Index()
    {
        return "Hello from FoobarHome Controller!<br><br> ID: " . $this->getParameter('id');
    }

}
