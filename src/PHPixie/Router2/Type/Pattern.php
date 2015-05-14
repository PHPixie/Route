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
        
        if($this->hostPattern() !== null) {
            $attributes = $this->matchPattern(
                $this->hostPattern,
                $segment->host()
            );
        }
        
        if($this->urlPattern() !== null) {
            $urlAttributes = $this->matchPattern(
                $this->urlPattern,
                $segment->url()
            );
            
            foreach($urlAttributes as $key => $value) {
                $attributes[$key] = $value;
            }
        }
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
        $regexp = "#^$regexp$#";
        if(preg_match($regexp, $segment->host, $matches) !== 1) {
            return null;
        }
        
        array_shift($matches);
        $tail = array_pop($matches);
        $attributes = $this->pattern->applyAttributes($matches);
        return array($attributes, $tail);
    }
}