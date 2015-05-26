<?php

namespace PHPixie\Tests\Router\Routes\Route;

/**
 * @coversDefaultClass \PHPixie\Router\Routes\Route\Pattern
 */
abstract class PatternTest extends \PHPixie\Test\Testcase
{
    protected $builder;
    protected $configData;
    
    protected $route;
    
    protected $matcher;
    
    protected $defaultAttributePatterns = array(
        'host' => '.+?',
        'path' => '[^/]+?',
    );
    
    public function setUp()
    {
        $this->builder    = $this->quickMock('\PHPixie\Router\Builder');
        $this->configData = $this->getSliceData();
        
        $this->route = $this->route();
        
        $this->matcher = $this->quickMock('\PHPixie\Router\Matcher');
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::attributePatterns
     * @covers ::defaults
     * @covers ::methods
     * @covers ::<protected>
     */
    public function testConfig()
    {
        $sets = array(
            array('attributePatterns', array(), array('t' => '.+')),
            array('defaults', array(), array('t' => '1')),
            array('methods', array(), array('GET')),
        );
        
        foreach($sets as $set) {
            $this->prepareConfigGet($set[0], $set[2], $set[1]);
            for($i=0; $i<2; $i++) {
                $method = $set[0];
                $this->assertSame($set[2], $this->route->$method());
            }
        }
    }
    
    /**
     * @covers ::hostPattern
     * @covers ::pathPattern
     * @covers ::<protected>
     */
    public function testPattern()
    {
        $attributePatterns = array('t' => '.+');
        
        foreach(array('host', 'path') as $key => $name) {
            $pattern = $this->prepareBuildPattern($name, $attributePatterns);
            
            if($key === 0) {
                $at = 1;
                $this->prepareConfigGet('attributePatterns', $attributePatterns, array(), $at);
            }
            
            for($i=0; $i<2; $i++) {
                $method = $name.'Pattern';
                $this->assertSame($pattern, $this->route->$method());
            }
        }
    }
    
    /**
     * @covers ::pathPattern
     * @covers ::<protected>
     */
    public function testMissingPattern()
    {
        foreach(array('host', 'path') as $name) {
            $this->prepareConfigGet($name, null);
            $method = $name.'Pattern';
            for($i=0; $i<2; $i++) {
                $this->assertSame(null, $this->route->$method());
            }
        }
    }
    
    protected function prepareIsMethodValid($fragment, $methodValid, &$configAt, &$fragmentAt)
    {
        if($methodValid === null) {
            $this->prepareConfigGet('methods', null, null, $configAt);
            
        }else{
            $this->prepareConfigGet('methods', array('GET', 'POST'), null, $configAt);
            $serverRequest = $this->getServerRequest();
            
            $this->method($fragment, 'serverRequest', $serverRequest, array(), $fragmentAt++);
            $method = $methodValid ? 'GET' : 'PUT';
            $this->method($serverRequest, 'getMethod', $method, array(), 0);
        }
    }
    
    protected function prepareConfigGet($key, $value, $default = null, &$configAt = 0)
    {
        $params = array($key);
        if($default !== null) {
            $params[]= $default;
        }
        
        $this->method($this->configData, 'get', $value, $params, $configAt++);
    }
    
    protected function preparePattern($name, $exists, $prepareAttributePatterns, &$configAt, &$builderAt)
    {
        if(!$exists) {
            $this->prepareConfigGet($name, null, null, $configAt);
            return null;
        }
        
        $attributePatterns = array('t' => 1);
        if($prepareAttributePatterns) {
            $attributeAt = $configAt+1;
            $this->prepareConfigGet('attributePatterns', $attributePatterns, array(), $attributeAt);
        }
        
        $pattern = $this->prepareBuildPattern($name, $attributePatterns, $configAt, $builderAt);
        
        if($prepareAttributePatterns) {
            $configAt++;
        }
        
        return $pattern;
    }
    
    protected function prepareGeneratePatternString(
        $name,
        $exists,
        $attributes,
        $prepareAttributePatterns,
        &$configAt,
        &$builderAt
    )
    {
        $pattern = $this->preparePattern(
            $name,
            $exists,
            $prepareAttributePatterns,
            $configAt,
            $builderAt
        );
        
        if($pattern === null) {
            return '';
        }
        
        $string = 'gen-'.$name;
        $this->method($pattern, 'generate', $string, array($attributes), 0);
        return $string;
    }
    
    protected function prepareBuildPattern($name, $attributePatterns, &$configAt = 0, &$builderAt = 0)
    {
        $this->prepareConfigGet($name, 'pixie', null, $configAt);
        
        $pattern = $this->getPattern();
        
        $this->method($this->builder, 'matcherPattern', $pattern, array(
            'pixie',
            $this->defaultAttributePatterns[$name],
            $attributePatterns
        ), $builderAt++);
        
        return $pattern;
    }
    
    protected function prepareMergeAttributes($match, $attributes, &$configAt = 0, &$matchAt = 0)
    {
        $defaults = array('default' => 1, 'a' => 1);
        $this->prepareConfigGet('defaults', $defaults, array(), $configAt);
        
        $this->method($match, 'attributes', $attributes, array(), $matchAt);
        return array_merge($defaults, $attributes);
    }
    
    protected function getSliceData()
    {
        return $this->quickMock('\PHPixie\Slice\Data');
    }
    
    protected function getFragment()
    {
        return $this->quickMock('\PHPixie\Router\Translator\Fragment');
    }
    
    protected function getMatch()
    {
        return $this->quickMock('\PHPixie\Router\Translator\Match');
    }
    
    protected function getServerRequest()
    {
        return $this->quickMock('\Psr\Http\Message\ServerRequestInterface');
    }
    
    protected function getPattern()
    {
        return $this->quickMock('\PHPixie\Router\Matcher\Pattern');
    }
    
    abstract protected function route();
}