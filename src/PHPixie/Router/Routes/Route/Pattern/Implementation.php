<?php

class Leaf extends Pattern
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
        return $this->generate($this->pathPattern(), $attributes);
    }
    
    public function generateUri($uri, $path, $attributes)
    {
        $path = $this->generate($this->pathPattern(), $attributes);
        $host = $this->generate($this->hostPattern(), $attributes);
        
        return $uri->withPath($path)->withHost($host);
    }
    
    protected function generate($pattern, $attributes)
    {
        if($pattern === null) {
            return '';
        }
        
        return $pattern->generate($attributes);
    }
}