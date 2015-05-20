<?php

namespace PHPixie\HTTPProcessors\Processor\AttributeParser;

class Pattern
{
    protected $pattern;
    protected $attributePatterns;
    protected $defaults;
    
    protected $regex;
    protected $parameterNames = array();
    
    public function __construct($pattern, $attributePatterns)
    {
        $this->pattern           = $pattern;
        $this->attributePatterns = $attributePatterns;
    }
    
    protected function prepareRegex()
    {
        $pattern = str_replace(
            array('(', ')'),
            array('(?:', ')?'),
            $this->pattern
        );
        
        $parameterNames = array();
        $this->regex = preg_replace_callback(
            '#<(.*?)>#',
            function($matches) use($attributePatterns, &$parameterNames) {
                $parameterNames[]= $matches[1];
                if(array_key_exists($matches[1], $attributePatterns)) {
                    $regexp = $attributePatterns[$matches[1]];

                }else{
                    $regexp = '[^/]+';
                }
                return '('.$regexp.')';
            },
            $pattern
        );
        
        $this->parameterNames = $parameterNames;
    }
    
    protected function requireRegex()
    {
        if($this->regex === null) {
            $this->prepareRegex();
        }
    }
    
    public function pattern()
    {
        return $this->pattern;
    }
    
    public function regex()
    {
        $this->requireRegex();
        return $this->regex;
    }
    
    public function parameterNames()
    {
        $this->requireRegex();
        return $this->parameterNames;
    }
    
    public function generate($parameters)
    {
        $parameters = array_merge($this->defaults, $parameters);
        
        $search  = array();
        $replace = array();
        foreach($parameters as $key => $value) {
            $search []= '<'.$key.'>';
            $replace[]= $value;
        }
        
        $remove = array('(', ')', '//');
        foreach($remove as $string) {
            $search[]= $remove;
        }        
        
        $string = str_replace($search, $replace, $this->pattern);
        
        return $string;
    }
}