<?php

namespace PHPixie;

class Route
{
    /**
     *
     * @var Route\Builder 
     */
    protected $builder;
    
    public function __construct()
    {
        $this->builder = $this->buildBuilder();
    }
    
    /**
     * 
     * @param Route\Resolvers\Resolver $resolver
     * @param Slice\Data $configData
     * @param HTTP\Context\Container $httpContextContainer
     * @return Route\Translator
     */
    public function translator($resolver, $configData, $httpContextContainer = null)
    {
        return $this->builder->translator($resolver, $configData, $httpContextContainer);
    }
    
    /**
     * 
     * @param \PHPixie\Slice\Data $configData
     * @param \PHPixie\Route\Resolvers\Registry $resolverRegistry
     * @return \PHPixie\Route\Resolvers\Resolver
     */
    public function buildResolver($configData, $resolverRegistry = null)
    {
        $resolvers  = $this->builder->resolvers();
        $builder = $resolvers->builder($resolverRegistry);
        return $builder->buildFromConfig($configData);
    }
    
    /**
     * 
     * @return Route\Builder 
     */
    public function builder()
    {
        return $this->builder;
    }
    
    protected function buildBuilder()
    {
        return new Route\Builder();
    }
}
