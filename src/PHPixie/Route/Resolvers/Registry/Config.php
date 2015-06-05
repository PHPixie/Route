<?php

namespace PHPixie\Route\Resolvers\Registry;

class Config implements \PHPixie\Route\Resolvers\Registry
{
    protected $resolverBuilder;
    protected $configData;
    
    protected $locators = array();
    
    public function __construct($resolverBuilder, $configData)
    {
        $this->resolverBuilder = $resolverBuilder;
        $this->configData   = $configData;
    }
    
    public function get($name)
    {
        if(!array_key_exists($name, $this->locators)) {
            $locatorConfig = $this->configData->slice($name);
            $this->locators[$name] = $this->resolverBuilder->buildFromConfig($locatorConfig);
        }
        
        return $this->locators[$name];
    }
}