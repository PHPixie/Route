<?php

namespace PHPixie;

class Router
{
    protected $builder;
    
    public function __construct($configData)
    {
        $this->builder = $this->buildBuilder($configData);
    }
    
    public function match($serverRequest = null)
    {
        return $this->builder->translator()->match($serverRequest);
    }
    
    public function generatePath($routePath, $attributes = array())
    {
        return $this->builder->translator()->generatePath($routePath, $attributes);
    }
    
    public function generateUri($routePath, $attributes = array(), $withHost = false)
    {
        return $this->builder->translator()->generateUri($routePath, $attributes, $withHost);
    }
    
    public function target($routePath)
    {
        return $this->builder->target($routePath);
    }
    
    public function buildRouteFromConfig($configData)
    {
        return $this->builder->routes()->buildFromConfig($configData);
    }
    
    public function builder()
    {
        return $this->builder;
    }
    
    protected function buildBuilder($configData)
    {
        return new Router\Builder($configData);
    }
}