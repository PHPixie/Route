<?php

namespace PHPixie\Route\Translator;

class Match
{
    protected $resolverPath;
    protected $attributes;
    
    public function __construct($resolverPath = null, $attributes = array())
    {
        $this->resolverPath  = $resolverPath;
        $this->attributes = $attributes;
    }
    
    public function resolverPath()
    {
        return $this->resolverPath;
    }
    
    public function attributes()
    {
        return $this->attributes;
    }
    
    public function popResolverPath()
    {
        if($this->resolverPath === null) {
            throw new \PHPixie\Route\Exception("Route path is empty");
        }
        
        $parts = explode('.', $this->resolverPath, 2);
        if(count($parts) === 1) {
            $this->resolverPath = null;
            return $parts[0];
        }
        
        $this->resolverPath = $parts[1];
        return $parts[0];
    }
    
    public function prependResolverPath($resolverPath)
    {
        $this->resolverPath = $resolverPath.'.'.$this->resolverPath;
    }
    
    public function prependAttributes($attributes)
    {
        $this->attributes = array_merge(
            $attributes,
            $this->attributes
        );
    }
}