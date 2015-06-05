<?php

namespace PHPixie\Route;

class Resolvers
{
    protected $builder;
    protected $resolverRegistry;
    
    public function __construct($builder, $resolverRegistry = null)
    {
        $this->builder       = $builder;
        $this->resolverRegistry = $resolverRegistry;
    }
    
    public function group($resolverBuilder, $configData)
    {
        return new Resolvers\Resolver\Group\Implementation(
            $resolverBuilder,
            $configData
        );
    }
    
    public function pattern($configData)
    {
        return new Resolvers\Resolver\Pattern\Implementation(
            $this->builder,
            $configData
        );
    }
    
    public function prefix($resolverBuilder, $configData)
    {
        return new Resolvers\Resolver\Pattern\Prefix(
            $this->builder,
            $resolverBuilder,
            $configData
        );
    }
    
    public function mount($resolverRegistry, $configData)
    {
        return new Resolvers\Resolver\Mount(
            $resolverRegistry,
            $configData
        );
    }
    
    public function builder($resolverRegistry = null)
    {
        return new Resolvers\Builder(
            $this,
            $resolverRegistry
        );
    }
}