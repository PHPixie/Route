<?php

namespace PHPixie\Route\Translator;

class Target
{
    /**
     *
     * @var \PHPixie\Route\Translator 
     */
    protected $translator;
    protected $resolverPath;
    
    /**
     * 
     * @param \PHPixie\Route\Translator $translator
     * @param string $resolverPath
     */
    public function __construct($translator, $resolverPath)
    {
        $this->translator = $translator;
        $this->resolverPath  = $resolverPath;
    }
    
    public function resolverPath()
    {
        return $this->resolverPath;
    }
    
    /**
     * 
     * @param array $attributes
     * @return string
     */
    public function path($attributes = array())
    {
        return $this->translator->generatePath($this->resolverPath, $attributes);
    }
    
    /**
     * 
     * @param array $attributes
     * @param boolean $withHost
     * @param \PHPixie\HTTP\Messages\Message\Request\ServerRequest $serverRequest
     * @return string
     */
    public function uri($attributes = array(), $withHost = false, $serverRequest = null)
    {
        return $this->translator->generateUri($this->resolverPath, $attributes, $withHost, $serverRequest);
    }
}