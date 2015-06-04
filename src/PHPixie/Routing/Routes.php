<?php

namespace PHPixie\Routing;

class Routes
{
    protected $builder;
    protected $routeRegistry;
    
    public function __construct($builder, $routeRegistry = null)
    {
        $this->builder       = $builder;
        $this->routeRegistry = $routeRegistry;
    }
    
    public function group($routeBuilder, $configData)
    {
        return new Routes\Route\Group(
            $routeBuilder,
            $configData
        );
    }
    
    public function pattern($configData)
    {
        return new Routes\Route\Pattern\Implementation(
            $this->builder,
            $configData
        );
    }
    
    public function prefix($routeBuilder, $configData)
    {
        return new Routes\Route\Pattern\Prefix(
            $this->builder,
            $routeBuilder,
            $configData
        );
    }
    
    public function mount($routeRegistry, $configData)
    {
        return new Routes\Route\Mount(
            $routeRegistry,
            $configData
        );
    }
    
    public function builder($routeRegistry = null)
    {
        return new Routes\Builder(
            $this,
            $routeRegistry
        );
    }
    
    public function configRegistry($routeBuilder, $configData)
    {
        return new Routes\Registry\Config(
            $routeBuilder,
            $configData
        );
    }
}