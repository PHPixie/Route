<?php

class Group extends Pattern
{
    protected function prepareRegex($regex)
    {
        return "#^$regex$#";
    }
    
    protected function mapAttributes($pattern, $attributes)
    {
        $attributes = $this->pattern->applyAttributes($attributes);
        return $attributes;
    }
    
    public function generatePath($path, $attributes)
    {
        $path = $this->routes()->generatePath($path, $attributes);
        
        if(($pathPattern = $this->pathPattern()) !== null) {
            $path = $this->prefix($pathPattern, $uri->getPath());
            $uri = $uri->withPath($path);
        }
    }
    
    public function generateUri($uri, $path, $attributes)
    {
        $uri = $this->routes()->generateUri($uri, $path, $attributes);
        
        if(($pathPattern = $this->pathPattern()) !== null) {
            $path = $this->prefix($pathPattern, $uri->getPath());
            $uri  = $uri->withPath($path);
        }
        
        if(($hostPattern = $this->hostPattern()) !== null) {
            $host = $this->prefix($hostPattern, $uri->getHost());
            $uri  = $uri->withHost($host);
        }
        
        return $uri;
    }
    
    protected function prefix($pattern, $string)
    {
        $prefix = $pathPattern->generate($attributes);
        return $prefix.$string;
    }
}