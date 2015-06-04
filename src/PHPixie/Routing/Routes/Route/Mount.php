<?php

namespace PHPixie\Routing\Routes\Route;

class Mount implements \PHPixie\Routing\Routes\Route
{
    protected $routeRegistry;
    protected $configData;
    
    protected $route;
    
    public function __construct($routeRegistry, $configData)
    {
        $this->routeRegistry = $routeRegistry;
        $this->configData    = $configData;
    }
    
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
        return $this->route()->generate($match, $withHost);
    }
}