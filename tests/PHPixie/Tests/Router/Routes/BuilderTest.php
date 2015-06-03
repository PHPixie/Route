<?php

namespace PHPixie\Tests\Routes\Locators;

/**
 * @coversDefaultClass \PHPixie\Router\Routes\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $routes;
    protected $locatorRegistry;
    
    protected $builder;
    
    public function setUp()
    {
        $this->routes        = $this->quickMock('\PHPixie\Router\Routes');
        $this->routeRegistry = $this->quickMock('\PHPixie\Router\Routes\Registry');
        
        $this->builder = new \PHPixie\Router\Routes\Builder(
            $this->routes,
            $this->routeRegistry
        );
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::buildFromConfig
     * @covers ::<protected>
     */
    public function testBuildFromConfig()
    {
        $configData = $this->abstractMock('\PHPixie\Slice\Data');
        
        $types = array(
            'group'   => array($this->builder, $configData),
            'pattern' => array($configData),
            'prefix'  => array($this->builder, $configData),
            'mount'   => array($this->routeRegistry, $configData)
        );
        
        foreach($types as $type => $parameters) {
            $this->method($configData, 'get', $type, array('type', 'pattern'), 0);
            
            $route = $this->quickMock('\PHPixie\Router\Routes\Route');
            $this->method($this->routes, $type, $route, $parameters, 0);
            
            $this->assertSame($route, $this->builder->buildFromConfig($configData));
        }
        
        $builder = new \PHPixie\Router\Routes\Builder(
            $this->routes
        );
        
        $this->method($configData, 'get', 'mount', array('type', 'pattern'), 0);
        
        $this->assertException(function() use($builder, $configData) {
            $builder->buildFromConfig($configData);
        }, '\PHPixie\Router\Exception');
    }
}