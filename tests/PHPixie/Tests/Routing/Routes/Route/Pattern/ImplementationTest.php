<?php

namespace PHPixie\Tests\Routing\Routes\Route;

/**
 * @coversDefaultClass \PHPixie\Routing\Routes\Route\Pattern\Implementation
 */
class ImplementationTest extends \PHPixie\Tests\Routing\Routes\Route\PatternTest
{
    /**
     * @covers ::match
     * @covers ::<protected>
     */
    public function testMatch()
    {
        $this->matchTest(false);
        $this->matchTest(true, false);
        $this->matchTest(true, true, false);
        $this->matchTest(true, true, true);
        $this->matchTest(null, null, null);
    }
    
    /**
     * @covers ::generate
     * @covers ::<protected>
     */
    public function testGenerate()
    {
        $this->generateTest();
        $this->generateTest(true);
        $this->generateTest(true, true);
        $this->generateTest(true, true, true);
    }
    
    protected function matchTest($methodValid = null, $hostValid = null, $pathValid = null)
    {
        $this->route = $this->route();
        $fragment = $this->getFragment();
        $match = $this->prepareMatchTest($fragment, $methodValid, $hostValid, $pathValid);
        $this->assertSame($match, $this->route->match($fragment));
    }
    
    protected function prepareMatchTest($fragment, $methodValid, $hostValid, $pathValid)
    {
        $builderAt  = 0;
        $configAt   = 0;
        $fragmentAt = 0;
        $matcherAt  = 0;
        
        $this->prepareIsMethodValid($fragment, $methodValid, $configAt, $fragmentAt);
        if($methodValid === false) {
            return null;
        }
        
        $this->method($fragment, 'host', 'pixie', array(), $fragmentAt++);
        $hostAttributes = $this->prepareMatchPattern(
            'host',
            $hostValid,
            'pixie',
            true,
            $configAt,
            $builderAt,
            $matcherAt
        );
        
        if($hostAttributes === null) {
            return null;
        }
        
        $this->method($fragment, 'path', 'pixie', array(), $fragmentAt++);
        $pathAttributes = $this->prepareMatchPattern(
            'path',
            $pathValid,
            'pixie',
            $hostValid === null,
            $configAt,
            $builderAt,
            $matcherAt
        );
        
        if($pathAttributes === null) {
            return null;
        }
        
        $defaults = array('default' => 1, 'override' => 'defaults');
        $this->prepareConfigGet('defaults', $defaults, array(), $configAt);
        
        $attributes = array_merge($defaults, $hostAttributes, $pathAttributes);
        
        $match = $this->getMatch();
        $this->method($this->builder, 'translatorMatch', $match, array($attributes), $builderAt++);
        return $match;
    }
    
    protected function prepareMatchPattern(
        $name,
        $isValid,
        $string,
        $prepareAttributePatterns,
        &$configAt,
        &$builderAt,
        &$matcherAt
    )
    {
        $pattern = $this->preparePattern(
            $name,
            $isValid !== null,
            $prepareAttributePatterns,
            $configAt,
            $builderAt
        );
        
        if($pattern === null) {
            return array();
        }
        
        $attributes = $isValid ? array($name => 1, 'override' => $name) : null;
        $this->method($this->builder, 'matcher', $this->matcher, array(), $builderAt++);
        $this->method($this->matcher, 'match', $attributes, array($pattern, $string), $matcherAt++);
        return $attributes;
    }
    
    protected function generateTest($withHost = false, $pathExists = false, $hostExists = false)
    {
        $this->route = $this->route();
        $match = $this->getMatch();
        
        $builderAt = 0;
        $configAt  = 0;
        
        $attributes = array('a' => 2);
        $mergedAttributes = $this->prepareMergeAttributes($match, $attributes, $configAt);
        
        $path = $this->prepareGeneratePatternString(
            'path',
            $pathExists,
            $mergedAttributes,
            true,
            $configAt,
            $builderAt
        );
        
        if($withHost) {
            $host = $this->prepareGeneratePatternString(
                'host',
                $hostExists,
                $mergedAttributes,
                !$pathExists,
                $configAt,
                $builderAt
            );
        }else{
            $host = null;
        }
        
        $fragment = $this->getFragment();
        $this->method($this->builder, 'translatorFragment', $fragment, array($path, $host), $builderAt++);
        
        if($withHost) {
            $result = $this->route->generate($match, true);
            
        }else{
            $result = $this->route->generate($match);
        }
        
        $this->assertSame($fragment, $result);
    }
    
    protected function route()
    {
        return new \PHPixie\Routing\Routes\Route\Pattern\Implementation(
            $this->builder,
            $this->configData
        );
    }
}