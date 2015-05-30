<?php

namespace PHPixie\Router;

class Target
{
    protected $translator;
    protected $routePath;
    
    public function __construct($translator, $routePath)
    {
        $this->translator = $translator;
        $this->routePath  = $routePath;
    }
    
    public function routePath()
    {
        return $this->routePath;
    }
    
    public function path($attributes = array())
    {
        return $this->translator->generatePath($this->routePath, $attributes);
    }
    
    public function uri($attributes = array(), $withHost = false, $serverRequest = null)
    {
        return $this->translator->generateUri($this->routePath, $attributes, $withHost, $serverRequest);
    }
}