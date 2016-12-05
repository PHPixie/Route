<?php

namespace PHPixie\Route\Resolvers\Resolver;

class Mount implements \PHPixie\Route\Resolvers\Resolver
{
    protected $resolverRegistry;
    protected $configData;
    
    protected $resolver;
    
    public function __construct($resolverRegistry, $configData)
    {
        $this->resolverRegistry = $resolverRegistry;
        $this->configData    = $configData;
    }
    
    public function resolver()
    {
        if($this->resolver === null) {
            $name = $this->configData->getRequired('name');
            $this->resolver = $this->resolverRegistry->get($name);
        }
        
        return $this->resolver;
    }
    
    public function match($segment)
    {
        return $this->resolver()->match($segment);
    }
    
    public function generate($match, $withHost = false)
    {
        return $this->resolver()->generate($match, $withHost);
    }
}