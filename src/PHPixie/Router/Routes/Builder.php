<?php

namespace PHPixie\Router\Routes;

class Builder
{
    protected $routes;
    protected $routeRegistry;
    
    public function __construct($routes, $routeRegistry = null)
    {
        $this->routes        = $routes;
        $this->routeRegistry = $routeRegistry;        
    }
    
    protected function group($configData)
    {
        return $this->routes->group($this, $configData);
    }
    
    protected function pattern($configData)
    {
        return $this->routes->pattern($configData);
    }
    
    protected function prefix($configData)
    {
        return $this->routes->prefix($this, $configData);
    }
    
    protected function mount($configData)
    {
        if($this->routeRegistry === null) {
            throw new \PHPixie\Router\Exception("Route registry was not specified");
        }
        
        return $this->routes->mount($this->routeRegistry, $configData);
    }
    
    public function buildFromConfig($configData) {
        $type = $configData->get('type', 'pattern');
        return $this->$type($configData);
    }
}