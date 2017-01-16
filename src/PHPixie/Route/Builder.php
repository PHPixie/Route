<?php

namespace PHPixie\Route;

class Builder
{
    protected $instances = array();
    
    /**
     * 
     * @param Resolvers\Resolver $resolver
     * @param \PHPixie\Slice\Data $configData
     * @param \PHPixie\HTTP\Context\Container $httpContextContainer
     * @return \PHPixie\Route\Translator
     */
    public function translator($resolver, $configData, $httpContextContainer = null)
    {
        return new Translator(
            $this,
            $resolver,
            $configData,
            $httpContextContainer
        );
    }
    
    /**
     * 
     * @param string $pattern
     * @param string $defaultParameterPattern
     * @param string $parameterPatterns
     * @return \PHPixie\Route\Matcher\Pattern
     */
    public function matcherPattern($pattern, $defaultParameterPattern = '.+?', $parameterPatterns = array())
    {
        return new Matcher\Pattern(
            $pattern,
            $defaultParameterPattern,
            $parameterPatterns
        );
    }
    
    /**
     * 
     * @param array $attributes
     * @param string $path
     * @return \PHPixie\Route\Translator\Match
     */
    public function translatorMatch($attributes = array(), $path = null)
    {
        return new Translator\Match(
            $attributes,
            $path
        );
    }
    
    /**
     * 
     * @param string $path
     * @param string $host
     * @param \PHPixie\HTTP\Messages\Message\Request\ServerRequest $serverRequest
     * @return \PHPixie\Route\Translator\Fragment
     */
    public function translatorFragment($path = null, $host = null, $serverRequest = null)
    {
        return new Translator\Fragment(
            $path,
            $host,
            $serverRequest
        );
    }
    
    /**
     * 
     * @param Translator $translator
     * @param string $resolverPath
     * @return \PHPixie\Route\Translator\Target
     */
    public function translatorTarget($translator, $resolverPath)
    {
        return new Translator\Target(
            $translator,
            $resolverPath
        );
    }
    
    /**
     * 
     * @return Matcher
     */
    public function matcher()
    {
        return $this->instance('matcher');
    }
    
    /**
     * 
     * @return Resolvers
     */
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
