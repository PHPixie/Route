<?php

namespace PHPixie\Router;

class Builder
{
    protected $configData;
    protected $httpContextContainer;
    protected $routeRegistry;
    
    protected $instances = array();
    
    public function __construct($configData, $httpContextContainer = null, $routeRegistry = null)
    {
        $this->configData           = $configData;
        $this->httpContextContainer = $httpContextContainer;
        $this->routeRegistry        = $routeRegistry;
    }
    
    public function matcherPattern($pattern, $defaultParameterPattern = '.+?', $parameterPatterns = array())
    {
        return new Matcher\Pattern(
            $pattern,
            $defaultParameterPattern,
            $parameterPatterns
        );
    }
    
    public function translatorMatch($path = null, $attributes = array())
    {
        return new Translator\Match(
            $path,
            $attributes
        );
    }
    
    public function translatorFragment($path = null, $host = null, $serverRequest = null)
    {
        return new Translator\Fragment(
            $path,
            $host,
            $serverRequest
        );
    }
    
    public function target($routePath)
    {
        return new Target(
            $this->translator(),
            $routePath
        );
    }
    
    public function matcher()
    {
        return $this->instance('matcher');
    }
    
    public function routes()
    {
        return $this->instance('routes');
    }
    
    public function translator()
    {
        return $this->instance('translator');
    }
    
    protected function instance($name)
    {
        if(!array_key_exists($name, $this->instances)) {
            $method = 'build'.ucfirst($name);
            $this->instances[$name] = $this->$method();
        }
        
        return $this->instances[$name];
    }
    
    protected function buildMatcher()
    {
        return new Matcher();
    }
    
    protected function buildRoutes()
    {
        return new Routes(
            $this,
            $this->routeRegistry
        );
    }
    
    protected function buildTranslator()
    {
        return new Translator(
            $this,
            $this->configData,
            $this->httpContextContainer
        );
    }
}