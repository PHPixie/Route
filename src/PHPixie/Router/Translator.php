<?php

namespace PHPixie\Router;

class Translator
{
    protected $builder;
    protected $route;
    protected $httpContextContainer;
    
    protected $basePath;
    protected $baseHost;
    
    public function __construct($builder, $route, $configData, $httpContextContainer = null)
    {
        $this->builder              = $builder;
        $this->route                = $route;
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
        
        $path = $this->stripPrefix($uri->getPath(), $this->basePath);
        if($path === null) {
            return null;
        }
        
        $fragment = $this->builder->translatorFragment(
            $path,
            $host,
            $serverRequest
        );
        
        return $this->route->match($fragment);
    }
    
    public function generatePath($routePath, $attributes = array())
    {
        $fragment = $this->generateFragment($routePath, $attributes);
        return $this->basePath.$fragment->path();
    }
    
    public function generateUri(
        $routePath,
        $attributes    = array(),
        $withHost      = false,
        $serverRequest = null
    )
    {
        if($serverRequest === null) {
            $serverRequest = $this->currentServerRequest();
        }
        $uri = $serverRequest->getUri();
        
        $fragment = $this->generateFragment($routePath, $attributes, $withHost, $serverRequest);
        
        $uri = $uri->withPath($this->basePath.$fragment->path());
        if($withHost) {
            $uri = $uri->withHost($this->baseHost.$fragment->host());
        }
        
        return $uri;
    }
    
    protected function generateFragment($routePath, $attributes, $withHost = false, $serverRequest = null)
    {
        $match = $this->builder->translatorMatch(
            $routePath,
            $attributes
        );
        
        return $this->route->generate($match, $withHost);
    }
    
    protected function stripPrefix($string, $prefix)
    {
        $length = strlen($prefix);
        if(substr($string, 0, $length) !== $prefix) {
            return null;
        }
        
        return substr($string, $length);
    }
    
    protected function currentServerRequest()
    {
        if($this->httpContextContainer === null)
        {
            throw new \PHPixie\Router\Exception("HTTP context container was not set");
        }
        
        $context = $this->httpContextContainer->httpContext();
        return $context->serverRequest();
    }
}