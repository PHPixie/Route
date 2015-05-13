<?php

namespace PHPixie\Router;

class Route
{
    protected $configData;
    
    protected $pathPattern;
    protected $hostPattern;
    protected $methods;
    
    protected $hostPatternProcessed = false;
    
    public function __construct($configData)
    {
        $this->configData = $configData;
    }
    
    public function methods()
    {
        if($this->methods === null) {
            $this->methods = $this->configData->get('methods', array());
        }
        
        return $this->methods;
    }
    
    public function pathPattern()
    {
        if($this->pathPattern === null) {
            $pattern = $this->configData->getRequired('path');
            $this->pathPattern = $this->builder->pattern($pattern);
        }
        
        return $this->pathPattern;
    }
    
    public function hostPattern()
    {
        if(!$this->hostPatternProcessed) {
            $pattern = $this->configData->get('host');
            if($pattern !== null) {
                $this->hostPattern = $this->builder->pattern($pattern);
            }
            $this->hostPatternProcessed = true;
        }
        
        return $this->hostPattern;
    }
}