<?php

namespace PHPixie\Router;

class URLGenerator
{
    protected $originalUri;
    
    public function path($route, $parameters)
    {
        return $route->pathPattern()->generate($parameters);
    }
    
    public function host($route, $parameters)
    {
        return $route->hostPattern()->generate($parameters);
    }
    
    public function uri($route, $parameters)
    {
        $path = $this->path($route, $parameters);
        $uri = $this->contextUri()->withPath($path);
        
        if(($hostPattern = $route->hostPattern()) !== null) {
            $host = $route->hostPattern()->generate($parameters);
            $uri  = $uri->withHost($host);
        }
        
        return $uri;
    }
    
    protected function contextUri()
    {
        $context = $this->httpContextContainer->httpContext();
        return $context->serverRequest()->getUri();
    }
}