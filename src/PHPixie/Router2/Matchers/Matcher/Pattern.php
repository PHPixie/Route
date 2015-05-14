<?php

class Pattern
{
    public function match($segment)
    {
        $segment = $this->matchSegment($segment);
        return $segment->attributes();
    }
    
    public function preprocess($segment)
    {
        
    }
}