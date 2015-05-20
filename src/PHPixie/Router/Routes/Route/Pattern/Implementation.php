<?php

class Leaf extends Pattern
{
    public function match()
    {
        if(!$this->isMethodValid($fragment)) {
            return null;
        }
        
        $attributes = $this->matchPattern($this->hostPattern(), $fragment->getHost());
        if($attributes === null) {
            return null;
        }
            
        $pathAttributes = $this->matchPattern($this->pathPattern(), $fragment->getPath());
        if($pathAttributes === null) {
            return null;
        }
        
        $attributes = array_merge($attributes, $pathAttributes);
        
        return $this->builder->translatorMatch($attributes);
    }
    
    protected function matchPattern($pattern, $string)
    {
        if($pattern === null) {
            return array();
        }
        
        if(($matches = $this->matchPatternRegex($pattern, $string)) === null) {
            return null;
        }
        
        return $pattern->mapMatches($matches);        
    }
    
    protected function prepareRegex($regex)
    {
        return "#^$regex$#";
    }
    
    public function generate($match, $withHost = false)
    {
        $path = $this->generate($this->pathPattern(), $attributes);
        
        if($withHost) {
            $host = $this->generate($this->hostPattern(), $attributes);
        }else{
            $host = null;
        }
        
        return $this->builder->translatorFragment($path, $host);
    }
}