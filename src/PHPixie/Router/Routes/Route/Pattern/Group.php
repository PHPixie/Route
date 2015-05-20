<?php

class Group extends Pattern
{
    public function match()
    {
        if(!$this->isMethodValid($fragment)) {
            return null;
        }
        
        list($attributes, $host) = $this->matchPattern($this->hostPattern(), $fragment->host());
        if($attributes === null) {
            return null;
        }
        
        list($pathAttributes, $path) = $this->matchPattern($this->pathPattern(), $fragment->path());
        if($pathAttributes === null) {
            return null;
        }
        
        $attributes = array_merge($attributes, $pathAttributes);
        
        $fragment = $fragment->copy($host, $path);
        
        if(($match = $this->routes()->match($fragment)) !== null) {
            $match->prepend(null, $attributes);
        }
        
        return $match;
    }
    
    protected function matchPattern($pattern, $string)
    {
        if($pattern === null) {
            return array(array(), $string);
        }
        
        if(($matches = $this->matchPatternRegex($pattern, $path)) === null) {
            return array(null, null);
        }
            
        $tail = array_pop($matches);
        $attributes = $pattern->mapMatches($matches);
        return array($attributes, $tail);
    }
    
    protected function prepareRegex($regex)
    {
        return "#^$regex$#";
    }
    
    public function generate($match, $withHost = false)
    {
        $fragment = $this->routes()->generate($match, $withHost);
        
        if(($pathPattern = $this->pathPattern()) !== null) {
            $path = $this->prefix($pathPattern, $fragment->path());
            $fragment->setPath($path);
        }
        
        if($withHost && ($hostPattern = $this->hostPattern()) !== null) {
            $host = $this->prefix($hostPattern, $fragment->host());
            $fragment->setHost($host);
        }
        
        return $fragment;
    }
        
    protected function prefix($pattern, $string)
    {
        $prefix = $pathPattern->generate($attributes);
        return $prefix.$string;
    }
}