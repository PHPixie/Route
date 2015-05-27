<?php

namespace PHPixie\Router\Routes\Route;

class Mount
{
    protected $routeRegistry;
    protected $name;
    protected $route;
    
    public function route()
    {
        if($this->route === null) {
            $name = $this->configData->getRequired('name');
            $this->route = $this->routeRegistry->get($name);
        }
        
        return $this->route;
    }
    
    public function match($segment)
    {
        return $this->route()->match($segment);
    }
    
    public function generate($match, $withHost = false)
    {
        return $this->route()->generate($match, $withHost = false);
    }
}