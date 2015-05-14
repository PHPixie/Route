<?php

class Pattern
{
    protected $hostPattern;
    protected $urlPattern;
    
    public function __construct($builder, $name, $configData, $parent = null)
    {
        $this->builder = $builder;
        parent::__construct($name, $parent);
    }
    
    public function match($segment)
    {
        if(!$this->matchMethod($segment)) {
            return null;
        }
        
        $attributes = array();
        
        $host = $segment->host();
        $path = $segment->path();
        
        if($this->hostPattern() !== null) {
            list($attributes, $host) = $this->matchPattern(
                $this->hostPattern,
                $host
            );
        }
        
        if($this->hostPattern() !== null) {
            list($attributes, $host) = $this->matchPattern(
                $this->hostPattern,
                $host
            );
            
            if($attributes === null) {
                return null;
            }
        }
        
        if($this->urlPattern() !== null) {
            list($urlAttributes, $host) = $this->matchPattern(
                $this->urlPattern,
                $segment->url()
            );
            
            if($urlAttributes === null) {
                return null;
            }

            foreach($urlAttributes as $key => $value) {
                $attributes[$key] = $value;
            }
        }
        
        return $this->segment->
    }
    
    protected function matchMethod($segment)
    {
        if($this->methods === null) {
            return true;
        }
        
        $method = $segment->serverRequest()->method();
        return in_array($method, $this->methods, true);
    }
    
    protected function matchPattern($pattern, $string)
    {
        $regexp = $pattern->regexp();
        $regexp = "#^$regexp(.*)$#";
        if(preg_match($regexp, $string, $matches) !== 1) {
            return array(null, null);
        }
        
        array_shift($matches);
        $tail = array_pop($matches);
        
        $attributes = $this->pattern->applyAttributes($matches);
        return array($attributes, $tail);
    }
}