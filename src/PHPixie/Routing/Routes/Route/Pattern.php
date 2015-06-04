<?php

namespace PHPixie\Routing\Routes\Route;

abstract class Pattern implements \PHPixie\Routing\Routes\Route
{
    protected $builder;
    protected $configData;
    
    protected $processed = array();
    
    protected $hostPattern;
    protected $pathPattern;
    protected $methods;
    
    public function __construct($builder, $configData)
    {
        $this->builder    = $builder;
        $this->configData = $configData;
    }
    
    public function hostPattern()
    {
        return $this->pattern('host','.+?');
    }
    
    public function pathPattern()
    {
        return $this->pattern('path','[^/]+?');
    }
    
    public function defaults()
    {
        return $this->config('defaults', array());
    }
    
    public function attributePatterns()
    {
        return $this->config('attributePatterns', array());
    }
    
    public function methods()
    {
        return $this->config('methods', array());
    }
    
    protected function pattern($name, $defaultAttributePattern)
    {
        $key = $name.'Pattern';
        if(!array_key_exists($key, $this->processed)) {
            $pattern = $this->configData->get($name, null);
            if($pattern !== null) {
                $pattern = $this->builder->matcherPattern(
                    $pattern,
                    $defaultAttributePattern,
                    $this->attributePatterns()
                );
            }
            $this->processed[$key] = $pattern;
        }
        return $this->processed[$key];
    }
    
    protected function config($key, $default)
    {
        if(!array_key_exists($key, $this->processed)) {
            $this->processed[$key] = $this->configData->get($key, $default);
        }
        
        return $this->processed[$key];
    }
    
    protected function isMethodValid($fragment)
    {
        $methods = $this->methods();
        if(empty($methods)) {
           return true; 
        }
        
        $method = $fragment->serverRequest()->getMethod();
        return in_array($method, $methods, true);
    }
    
    protected function generatePatternString($pattern, $attributes)
    {
        if($pattern === null) {
            return '';
        }
        
        return $pattern->generate($attributes);
    }
    
    protected function mergeAttributes($match)
    {
        return array_merge(
            $this->defaults(),
            $match->attributes()
        );
    }
    
    abstract public function generate($match, $withHost = false);
}