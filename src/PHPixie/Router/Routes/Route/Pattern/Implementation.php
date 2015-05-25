<?php

namespace PHPixie\Router\Routes\Route\Pattern;

class Implementation extends \PHPixie\Router\Routes\Route\Pattern
{
    public function match($fragment)
    {
        if(!$this->isMethodValid($fragment)) {
            return null;
        }
        
        $hostAttributes = $this->matchPattern(
            $this->hostPattern(),
            $fragment->host()
        );
        
        if($hostAttributes === null) {
            return null;
        }
        
        $pathAttributes = $this->matchPattern(
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
        
        return $this->builder->translatorMatch($attributes);
    }
    
    protected function matchPattern($pattern, $string)
    {
        if($pattern === null) {
            return array();
        }
        return $this->builder->matcher()->match($pattern, $string);
    }
    
    public function generate($match, $withHost = false)
    {
        $attributes = $this->mergeAttributes($match);
        
        $path = $this->generatePatternString($this->pathPattern(), $attributes);
        
        if($withHost) {
            $host = $this->generatePatternString($this->hostPattern(), $attributes);
        }else{
            $host = null;
        }
        
        return $this->builder->translatorFragment($path, $host);
    }
}