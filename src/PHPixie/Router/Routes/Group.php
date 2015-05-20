<?php

namespace PHPixie\Router\Routes;

class Group
{
    protected $routeBuilder;
    protected $configData;
    
    protected $names;
    protected $routes;
    
    public function __construct($routeBuilder, $configData)
    {
        $this->routeBuilder = $routeBuilder;
        $this->configData   = $configData;
    }
    
    public function names()
    {
        $this->requireRouteNames();
        return $this->names;
    }
    
    public function get($name)
    {
        $this->requireRouteNames();
        
        if(!array_key_exists($name, $this->routes)) {
            throw new \PHPixie\Router\Exception\Route();
        }
        
        if($this->routes[$name] === null) {
            $configData = $this->configData->slice($name);
            $this->routes[$name] = $this->routeBuilder->buildRoute($configData);
        }
        
        return $this->routes[$name];
    }
    
    public function match($segment)
    {
        $match = null;
        
        foreach($this->names() as $name) {
            $route = $this->get($name);
            $match = $route->match($segment);
            if($match !== null) {
                $match->prepend($name);
                break;
            }
        }
        
        return $match;
    }
    
    public function generate($match, $withHost = false)
    {
        $name  = $match->popPath();
        $route = $this->get($name);
        return $route->generate($match, $withHost);
    }
    
    protected function requireRouteNames()
    {
        if($this->names === null) {
            $this->names  = $this->configData->keys();
            $this->routes = array_fill_keys($this->names, null);
        }
    }
}