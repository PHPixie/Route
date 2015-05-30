<?php

namespace PHPixie\Router;

class Routes
{
    protected $builder;
    protected $routeRegistry;
    
    protected $routes = array(
        'group',
        'mount',
        'pattern',
        'prefix'
    );
    
    public function __construct($builder, $routeRegistry = null)
    {
        $this->builder       = $builder;
        $this->routeRegistry = $routeRegistry;
    }
    
    public function group($configData)
    {
        return new Routes\Route\Group(
            $this,
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
    
    public function prefix($configData)
    {
        return new Routes\Route\Pattern\Prefix(
            $this->builder,
            $configData
        );
    }
    
    public function mount($configData)
    {
        if($this->routeRegistry === null) {
            throw new \PHPixie\Router\Exception("Route registry was not specified");
        }
        
        return new Routes\Route\Mount(
            $this->routeRegistry,
            $configData
        );
    }
    
    public function buildFromConfig($configData) {
        $type = $configData->get('type', 'pattern');
        if(!in_array($type, $this->routes, true)) {
            throw new \PHPixie\Router\Exception("Route type '$type' does not exist");
        }
        
        return $this->$type($configData);
    }
}