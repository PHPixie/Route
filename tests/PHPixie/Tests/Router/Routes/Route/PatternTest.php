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
        $this->configData = $this->quickMock('\PHPixie\Slice\Data');
        
        $this->route = $this->route();
        
        $this->matcher = $this->quickMock('\PHPixie\Router\Routes\Matcher');
        $this->method($this->builder, 'matcher', $this->matcher, array());
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
            $pattern = $this->preparePattern($name, $attributePatterns);
            
            if($key === 0) {
                $this->prepareConfigGet('attributePatterns', $attributePatterns, array(), 1);
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
    
    protected function prepareConfigGet($key, $value, $default = null, $configAt = 0)
    {
        $params = array($key);
        if($default !== null) {
            $params[]= $default;
        }
        
        $this->method($this->configData, 'get', $value, $params, $configAt);
    }
    
    protected function preparePattern($name, $attributePatterns, &$configAt = 0, &$builderAt = 0)
    {
        $this->prepareConfigGet($name, 'pixie', null);
        
        $pattern = $this->getPattern();
        
        $this->method($this->builder, 'matcherPattern', $pattern, array(
            'pixie',
            $this->defaultAttributePatterns[$name],
            $attributePatterns
        ), $builderAt++);
        
        return $pattern;
    }
    
    protected function getPattern()
    {
        return $this->quickMock('\PHPixie\Router\Matcher\Pattern');
    }
    
    abstract protected function route();
}