<?php

class Pattern
{
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
    
    abstract protected function prepareRegex($regex);
    abstract protected function mapAttributes();
}