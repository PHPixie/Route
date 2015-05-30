<?php

namespace PHPixie\Router\Translator;

class Match
{
    protected $routePath;
    protected $attributes;
    
    public function __construct($routePath = null, $attributes = array())
    {
        $this->routePath  = $routePath;
        $this->attributes = $attributes;
    }
    
    public function routePath()
    {
        return $this->routePath;
    }
    
    public function attributes()
    {
        return $this->attributes;
    }
    
    public function popRoutePath()
    {
        if($this->routePath === null) {
            throw new \PHPixie\Router\Exception("Route path is empty");
        }
        
        $parts = explode('.', $this->routePath, 2);
        if(count($parts) === 1) {
            $this->routePath = null;
            return $parts[0];
        }
        
        $this->routePath = $parts[1];
        return $parts[0];
    }
    
    public function prependRoutePath($routePath)
    {
        $this->routePath = $routePath.'.'.$this->routePath;
    }
    
    public function prependAttributes($attributes)
    {
        $this->attributes = array_merge(
            $attributes,
            $this->attributes
        );
    }
}