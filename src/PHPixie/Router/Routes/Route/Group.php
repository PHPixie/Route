<?php

namespace PHPixie\Router\Route;

class Group implements \PHPixie\Router\Route
{
    protected $pathPrefixPattern;
    protected $hostPrefixPattern;
    
    protected $methods = array();
    
    public function __construct($configData)
    {
        
    }
    
    public function match($serverRequest)
    {
        if(!empty($this->methods)) {
            $method = $serverRequest->getMethod();
            if(!in_array($method, $this->methods))
                return null;
        }
        
        if($this->hostPrafixPattern !== null) {
            list($args, $tail) = $
        }
    }
        
}