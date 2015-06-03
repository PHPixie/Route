<?php

namespace PHPixie\Router\Routes\Registry;

class Config implements \PHPixie\Router\Routes\Registry
{
    protected $routeBuilder;
    protected $configData;
    
    protected $locators = array();
    
    public function __construct($routeBuilder, $configData)
    {
        $this->routeBuilder = $routeBuilder;
        $this->configData   = $configData;
    }
    
    public function get($name)
    {
        if(!array_key_exists($name, $this->locators)) {
            $locatorConfig = $this->configData->slice($name);
            $this->locators[$name] = $this->routeBuilder->buildFromConfig($locatorConfig);
        }
        
        return $this->locators[$name];
    }
}