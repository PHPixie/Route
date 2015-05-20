<?php

class Processor implements \PHPixie\Processors\Processor
{
    public function process($serverRequest)
    {
        $attributes = $this->matcher->match($serverRequest);
        
        if($attributes === null) {
            throw new \PHPixie\Router\Exception\NotMatched();
        }
        
        foreach($attributes as $key => $value) {
            $serverRequest = $serverRequest->withAttribute($key, $value);
        }
        
        return $serverRequest;
    }
}