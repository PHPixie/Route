<?php

class Pattern implements \PHPixie\Router\Routes\Route
{
    protected $hostPattern;
    protected $urlPattern;
    
    protected $configData;
    
    
    
    protected function matchPattern($pattern, $string)
    {
        $regex = $pattern->regex();
        $regex = $this->prepareRegex($regex);
        if(preg_match($regex, $string, $matches) !== 1) {
            return null;
        }
        
        array_shift($matches);
        $this->mapAttributes($attributes);
        
    }
    
    abstract public function match($segment);
    abstract public function generate($match, $withHost = false);
}