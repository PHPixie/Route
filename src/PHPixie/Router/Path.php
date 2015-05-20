<?php

namespace PHPixie\Router;

class Path
{
    protected $generator;
    protected $name;
    
    public function __construct($generator, $name)
    {
        $this->generator = $generator;
        $this->name      = $name;
    }
    
    public function name($name)
    {
        return $this->name;
    }
    
    public function path($attributes)
    {
        return $this->generator->path($this->name, $attributes);
    }
    
    public function uri($attributes)
    {
        return $this->generator->uri($this->name, $attributes);
    }
}