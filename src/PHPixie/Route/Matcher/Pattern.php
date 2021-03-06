<?php

namespace PHPixie\Route\Matcher;

class Pattern
{
    protected $pattern;
    protected $defaultParameterPattern;
    protected $parameterPatterns;
    
    protected $regex;
    protected $parameterNames = array();
    
    /**
     * 
     * @param string $pattern
     * @param string $defaultParameterPattern
     * @param string $parameterPatterns
     */
    public function __construct($pattern, $defaultParameterPattern = '.+?', $parameterPatterns = array())
    {
        $this->pattern                 = $pattern;
        $this->defaultParameterPattern = $defaultParameterPattern;
        $this->parameterPatterns       = $parameterPatterns;
    }
    
    protected function prepareRegex()
    {
        $pattern = str_replace(
            array('(', ')'),
            array('(?:', ')?'),
            $this->pattern
        );
        
        $parameterNames    = array();
        $parameterPatterns = $this->parameterPatterns;
        
        $this->regex = preg_replace_callback(
            '#<(.*?)>#',
            function($matches) use($parameterPatterns, &$parameterNames) {
                $parameterNames[]= $matches[1];
                if(array_key_exists($matches[1], $parameterPatterns)) {
                    $regexp = $parameterPatterns[$matches[1]];

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
    
    /**
     * 
     * @return string
     */
    public function pattern()
    {
        return $this->pattern;
    }
    
    /**
     * 
     * @return string
     */
    public function regex()
    {
        $this->requireRegex();
        return $this->regex;
    }
    
    /**
     * 
     * @return array
     */
    public function parameterNames()
    {
        $this->requireRegex();
        return $this->parameterNames;
    }
    
    /**
     * 
     * @param array $parameters
     * @return string
     */
    public function generate($parameters)
    {
        $search  = array();
        $replace = array();
        foreach($parameters as $key => $value) {
            $search []= '<'.$key.'>';
            $replace[]= $value;
        }
        $remove = array('(', ')');
        foreach($remove as $string) {
            $search[]= $string;
        }
        
        $string = str_replace($search, $replace, $this->pattern);
        $string = preg_replace('/\/+/', '/', $string);   
        return   $string;
    }
}