<?php

namespace PHPixie\Router\Translator;

class Match
{
    protected $path;
    protected $attributes;
    
    public function __construct($path = null, $attributes = array())
    {
        $this->path       = $path;
        $this->attributes = $attributes;
    }
    
    public function path()
    {
        return $this->path;
    }
    
    public function attributes()
    {
        return $this->attributes;
    }
    
    public function popPath()
    {
        if($this->path === null) {
            throw new \PHPixie\Router\Exception("Path is empty");
        }
        
        $parts = explode('.', $this->path, 2);
        if(count($parts) === 1) {
            $this->path = null;
            return $parts[0];
        }
        
        $this->path = $parts[1];
        return $parts[0];
    }
    
    public function prependPath($path)
    {
        $this->path = $path.'.'.$this->path;
    }
    
    public function prependAttributes($attributes)
    {
        $this->attributes = array_merge(
            $attributes,
            $this->attributes
        );
    }
}