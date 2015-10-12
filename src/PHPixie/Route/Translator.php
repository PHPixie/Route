<?php

namespace PHPixie\Route;

class Translator
{
    protected $builder;
    protected $resolver;
    protected $httpContextContainer;
    
    protected $basePath;
    protected $baseHost;
    
    public function __construct($builder, $resolver, $configData, $httpContextContainer = null)
    {
        $this->builder              = $builder;
        $this->resolver             = $resolver;
        $this->httpContextContainer = $httpContextContainer;
        
        $this->basePath = $configData->get('basePath', '/');
        $this->baseHost = $configData->get('baseHost', '');
    }
    
    public function match($serverRequest = null)
    {
        if($serverRequest === null) {
            $serverRequest = $this->currentServerRequest();
        }
        $uri = $serverRequest->getUri();
        
        $host = $this->stripPrefix($uri->getHost(), $this->baseHost);
        if($host === null) {
            return null;
        }
        
        $path = urldecode($uri->getPath());
        $path = $this->stripPrefix($path, $this->basePath);
        if($path === null) {
            return null;
        }
        
        $fragment = $this->builder->translatorFragment(
            $path,
            $host,
            $serverRequest
        );
        
        return $this->resolver->match($fragment);
    }
    
    public function generatePath($resolverPath = null, $attributes = array())
    {
        $fragment = $this->generateFragment($resolverPath, $attributes);
        return $this->basePath.$fragment->path();
    }
    
    public function generateUri(
        $resolverPath  = null,
        $attributes    = array(),
        $withHost      = false,
        $serverRequest = null
    )
    {
        if($serverRequest === null) {
            $serverRequest = $this->currentServerRequest();
        }
        $uri = $serverRequest->getUri();
        
        $fragment = $this->generateFragment($resolverPath, $attributes, $withHost, $serverRequest);
        
        $uri = $uri->withPath($this->basePath.$fragment->path());
        if($withHost) {
            $uri = $uri->withHost($this->baseHost.$fragment->host());
        }
        
        return $uri;
    }
    
    protected function generateFragment($resolverPath, $attributes, $withHost = false, $serverRequest = null)
    {
        $match = $this->builder->translatorMatch(
            $attributes,
            $resolverPath
        );
        
        return $this->resolver->generate($match, $withHost);
    }
    
    protected function stripPrefix($string, $prefix)
    {
        $length = strlen($prefix);
        if(substr($string, 0, $length) !== $prefix) {
            return null;
        }
        
        return (string) substr($string, $length);
    }
    
    protected function currentServerRequest()
    {
        if($this->httpContextContainer === null)
        {
            throw new \PHPixie\Route\Exception("HTTP context container was not set");
        }
        
        $context = $this->httpContextContainer->httpContext();
        return $context->request()->serverRequest();
    }
}
