<?php

namespace PHPixie\Router;

class Translator
{
    protected $builder;
    
    protected $basePath;
    protected $baseHost;
    protected $route;
    
    public function __construct($builder, $configData)
    {
        $this->builder  = $builder;
        
        $this->basePath = $configData->get('basePath', '/');
        $this->baseHost = $configData->get('baseHost', '');
        
        $routeConfig = $configData->slice('route');
        $this->route = $this->builder->routes()->buildFromConfig($routeConfig);
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
    
    public function generatePath($routePath, $attributes)
    {
        $fragment = $this->generateFragment($routePath, $attributes);
        return $this->basePath.$fragment->path();
    }
    
    public function generateUri($routePath, $attributes, $withHost = false)
    {
        $fragment = $this->generateFragment($routePath, $attributes, $withHost);
        $uri = $this->currentServerRequest()->getUri();
        
        $uri = $uri->withPath($this->basePath.$fragment->path());
        if($withHost) {
            $uri = $uri->withHost($this->baseHost.$fragment->host());
        }
        
        return $uri;
    }
    
    protected function generateFragment($routePath, $attributes, $withHost = false)
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
        return $this->builder->getHttpContext()->serverRequest();
    }
}