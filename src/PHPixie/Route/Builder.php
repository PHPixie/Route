<?php

namespace PHPixie\Route;

class Builder
{
    protected $instances = array();
    
    public function translator($resolver, $configData, $httpContextContainer = null)
    {
        return new Translator(
            $this,
            $resolver,
            $configData,
            $httpContextContainer
        );
    }
    
    public function matcherPattern($pattern, $defaultParameterPattern = '.+?', $parameterPatterns = array())
    {
        return new Matcher\Pattern(
            $pattern,
            $defaultParameterPattern,
            $parameterPatterns
        );
    }
    
    public function translatorMatch($attributes = array(), $path = null)
    {
        return new Translator\Match(
            $attributes,
            $path
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
    
    public function translatorTarget($translator, $resolverPath)
    {
        return new Translator\Target(
            $translator,
            $resolverPath
        );
    }
    
    public function matcher()
    {
        return $this->instance('matcher');
    }
    
    public function resolvers()
    {
        return $this->instance('resolvers');
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
    
    protected function buildResolvers()
    {
        return new Resolvers(
            $this
        );
    }
}