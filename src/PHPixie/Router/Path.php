<?php

namespace PHPixie\Router;

class Path
{
    protected $translator;
    protected $name;
    
    public function __construct($generator, $name)
    {
        $this->translator = $translator;
        $this->name       = $name;
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