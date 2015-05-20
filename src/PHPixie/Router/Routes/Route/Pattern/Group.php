<?php

class Group extends Pattern
{
    public function match()
    {
        if(!$this->isMethodValid($fragment)) {
            return null;
        }
        
        list($hostAttributes, $host) = $this->matchPattern(
            $this->hostPattern(),
            $fragment->host()
        );
        
        if($hostAttributes === null) {
            return null;
        }
        
        list($pathAttributes, $path) = $this->matchPattern(
            $this->pathPattern(),
            $fragment->path()
        );
        
        if($pathAttributes === null) {
            return null;
        }
        
        $attributes = array_merge(
            $this->defaults(),
            $hostAttributes,
            $pathAttributes
        );
        
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
        
        return $this->matcher->matchPrefix($pattern, $string);
    }

    public function generate($match, $withHost = false)
    {
        $fragment = $this->routes()->generate($match, $withHost);
        $attributes = $match->attributes();
        
        if(($pathPattern = $this->pathPattern()) !== null) {
            $path = $this->prefix($pathPattern, $attributes, $fragment->path());
            $fragment->setPath($path);
        }
        
        if($withHost && ($hostPattern = $this->hostPattern()) !== null) {
            $host = $this->prefix($hostPattern, $attributes, $fragment->host());
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