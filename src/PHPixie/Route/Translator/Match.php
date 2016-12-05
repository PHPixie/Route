<?php

namespace PHPixie\Route\Translator;

class Match
{
    protected $resolverPath;
    protected $attributes;
    
    public function __construct($attributes = array(), $resolverPath = null)
    {
        $this->attributes = $attributes;
        $this->resolverPath  = $resolverPath;
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
        if($this->resolverPath !== null) {
            $this->resolverPath = $resolverPath.'.'.$this->resolverPath;
        }else{
            $this->resolverPath = $resolverPath;
        }
    }
    
    public function prependAttributes($attributes)
    {
        $this->attributes = array_merge(
            $attributes,
            $this->attributes
        );
    }
}