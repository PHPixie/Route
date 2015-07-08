<?php

namespace PHPixie;

class Route
{
    protected $builder;
    
    public function __construct()
    {
        $this->builder = $this->buildBuilder();
    }
    
    public function translator($resolver, $configData, $httpContextContainer = null)
    {
        return $this->builder->translator($resolver, $configData, $httpContextContainer);
    }
    
    public function buildResolver($configData, $resolverRegistry = null)
    {
        $resolvers  = $this->builder->resolvers();
        $builder = $resolvers->builder($resolverRegistry);
        return $builder->buildFromConfig($configData);
    }
    
    public function builder()
    {
        return $this->builder;
    }
    
    protected function buildBuilder()
    {
        return new Route\Builder();
    }
}