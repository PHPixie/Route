<?php

class Routes implements \IteratorAaggregate
{
    protected $configData;
    protected $names;
    protected $routes;
    
    public function __construct($configData)
    {
        $this->configData = $configData;
    }
    
    public function names()
    {
        if($this->names === null) {
            $this->names = $this->configData->keys();
        }
        
        return $this->names;
    }
    
    public function get($name)
    {
        if(array_key_exists($name, $this->routes)) {
            $configData = $this->configData->slice($name);
            $this->routes[$name] = $this->builder->buildRoute($configData);
        }
        
        return $this->routes[$name];
    }
    
    public function match($segment)
    {
        $attributes = array();
        
        foreach($this->names() as $name) {
            $route = $this->get($name);
            $attributes = $route->match($segment);
            if($attributes !== null) {
                break;
            }
        }
        
        return $attributes;
    }
}