<?php

namespace PHPixie\Router\Routes\Route\Pattern;

abstract class Prefix extends \PHPixie\Router\Routes\Route\Pattern
{
    public function match($fragment)
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
        
        $fragment = $fragment->copy($path, $host);
        
        $match = $this->group()->match($fragment);
        if($match !== null) {
            $match->prependAttributes($attributes);
        }
        
        return $match;
    }
    
    protected function matchPattern($pattern, $string)
    {
        if($pattern === null) {
            return array(array(), $string);
        }
        
        return $this->builder->matcher()->matchPrefix($pattern, $string);
    }

    public function generate($match, $withHost = false)
    {
        $fragment   = $this->group()->generate($match, $withHost);
        $attributes = $this->mergeAttributes($match);
        
        $path = $this->generatePatternString($this->pathPattern(), $attributes);
        $path.= $fragment->path();
        $fragment->setPath($path);
        
        if($withHost) {
            $host = $this->generatePatternString($this->hostPattern(), $attributes);
            $host.= $fragment->host();
            $fragment->setHost($host);
        }
        
        return $fragment;
    }
        
    abstract public function route();
}