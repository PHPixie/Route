<?php

namespace PHPixie\Router;

class Matcher
{
    public function match($pattern, $string)
    {
        $regex = '#^'.$pattern->regex().'$#i';
        if(($matches = $this->matchRegex($regex, $string)) === null) {
            return null;
        }
        
        return $this->mapParameters($pattern, $matches);
    }
    
    public function matchPrefix($pattern, $string)
    {
        $regex = '#^'.$pattern->regex().'(.*)$#i';
        if(($matches = $this->matchRegex($regex, $string)) === null) {
            return array(null, $string);
        }
        
        $tail       = array_pop($matches);
        $parameters = $this->mapParameters($pattern, $matches);
        return array($parameters, $tail);
    }
    
    protected function matchRegex($regex, $string)
    {
        if(preg_match($regex, $string, $matches) !== 1) {
            return null;
        }
        
        array_shift($matches);
        return $matches;
    }
    
    protected function mapParameters($pattern, $matches)
    {
        return array_combine($pattern->parameterNames(), $matches);
    }
}