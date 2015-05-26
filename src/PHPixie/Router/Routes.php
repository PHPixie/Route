<?php

namespace PHPixie\Router;

class Routes
{
    protected $builder;
    
    protected $routes = array(
        'group',
        'pattern',
        'prefix'
    );
    
    public function __construct($builder)
    {
        $this->builder = $builder;
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
    
    public function buildFromConfig($configData) {
        $type = $configData->get('type', 'pattern');
        if(!in_array($type, $this->routes, true)) {
            throw new \PHPixie\Router\Exception("Route type '$type' does not exist");
        }
        
        return $this->$type($configData);
    }
}