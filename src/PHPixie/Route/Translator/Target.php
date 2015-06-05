<?php

namespace PHPixie\Route\Translator;

class Target
{
    protected $translator;
    protected $resolverPath;
    
    public function __construct($translator, $resolverPath)
    {
        $this->translator = $translator;
        $this->resolverPath  = $resolverPath;
    }
    
    public function resolverPath()
    {
        return $this->resolverPath;
    }
    
    public function path($attributes = array())
    {
        return $this->translator->generatePath($this->resolverPath, $attributes);
    }
    
    public function uri($attributes = array(), $withHost = false, $serverRequest = null)
    {
        return $this->translator->generateUri($this->resolverPath, $attributes, $withHost, $serverRequest);
    }
}