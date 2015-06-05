<?php

namespace PHPixie\Route\Resolvers;

class Builder
{
    protected $resolvers;
    protected $resolverRegistry;
    
    public function __construct($resolvers, $resolverRegistry = null)
    {
        $this->resolvers        = $resolvers;
        $this->resolverRegistry = $resolverRegistry;        
    }
    
    protected function group($configData)
    {
        return $this->resolvers->group($this, $configData);
    }
    
    protected function pattern($configData)
    {
        return $this->resolvers->pattern($configData);
    }
    
    protected function prefix($configData)
    {
        return $this->resolvers->prefix($this, $configData);
    }
    
    protected function mount($configData)
    {
        if($this->resolverRegistry === null) {
            throw new \PHPixie\Route\Exception("Route registry was not specified");
        }
        
        return $this->resolvers->mount($this->resolverRegistry, $configData);
    }
    
    public function buildFromConfig($configData) {
        $type = $configData->get('type', 'pattern');
        return $this->$type($configData);
    }
}