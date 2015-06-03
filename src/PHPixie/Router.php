<?php

namespace PHPixie;

class Router
{
    protected $builder;
    
    public function __construct($configData)
    {
        $this->builder = $this->buildBuilder(
            $configData,
            $httpContextContainer,
            $routeRegistry
        );
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
    
    public function translator($route)
    
    public function buildRoute($configData, $routeRegistry = null)
    {
        $routes  = $this->builder->routes();
        $builder = $routes->builder($routeRegistry);
        return $builder->buildFromConfig($configData);
    }
    
    public function configRouteRegistry($configData, $routeRegistry = null)
    {
        $routes  = $this->builder->routes();
        $builder = $routes->builder($routeRegistry);
        return $routes->configRegistry($builder, $configData);
    }
    
    public function builder()
    {
        return $this->builder;
    }
    
    protected function buildBuilder($configData, $httpContextContainer, $routeRegistry)
    {
        return new Router\Builder($configData, $httpContextContainer, $routeRegistry);
    }
}