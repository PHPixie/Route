<?php

class Preprocessing extends Route
{
    public function matchSegment($segment)
    {
        $segment = $this->preprocessSegment($segment);
        
        if($segment === null) {
            return null;
        }
        
        $segment = $this->processSegment($segment);
        
        if($segment === null) {
            return null;
        }
    }
    
    abstract protected function processSegment($segment);
}