<?php

namespace PHPixie;

class Routing
{
    protected $builder;
    
    public function __construct()
    {
        $this->builder = $this->buildBuilder();
    }
    
    public function translator($configData, $route, $httpContextContainer = null)
    {
        return $this->builder->translator($configData, $route, $httpContextContainer);
    }
    
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
    
    protected function buildBuilder()
    {
        return new Routing\Builder();
    }
}