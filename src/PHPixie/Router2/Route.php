<?php

class Route
{
    protected $preprocessor;
    
    public function match($segment)
    {
        $segment = $this->matchSegment($segment);
        return $segment->attributes();
    }
    
    public function matchSegment()
    {
        $segment = 
    }
    
    protected function processSegment($segment)
    {
        if($this->preprocessor !== null) {
            $segment = $this->preprocessor->matchSegment($segment);
            if($segment === null) {
                return null;
            }
        }
        
        return $segment;
    }
}