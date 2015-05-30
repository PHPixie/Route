<?php

namespace PHPixie\Tests\Router;

/**
 * @coversDefaultClass \PHPixie\Router\Routes
 */
class RoutesTest extends \PHPixie\Test\Testcase
{
    protected $builder;
    protected $routeRegistry;
    
    protected $routes;
    
    protected $classMap = array(
        'group'   => '\PHPixie\Router\Routes\Route\Group',
        'mount'  => '\PHPixie\Router\Routes\Route\Mount',
        'pattern' => '\PHPixie\Router\Routes\Route\Pattern\Implementation',
        'prefix'  => '\PHPixie\Router\Routes\Route\Pattern\Prefix'
    );
    
    public function setUp()
    {
        $this->builder       = $this->quickMock('\PHPixie\Router\Builder');
        $this->routeRegistry = $this->quickMock('\PHPixie\Router\Routes\Registry');
        
        $this->routes = new \PHPixie\Router\Routes(
            $this->builder,
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
     * @covers ::group
     * @covers ::<protected>
     */
    public function testGroup()
    {
        $configData = $this->getSliceData();
        
        $group = $this->routes->group($configData);
        $this->assertInstance($group, $this->classMap['group'], array(
            'routes'     => $this->routes,
            'configData' => $configData
        ));
    }
    
    /**
     * @covers ::pattern
     * @covers ::<protected>
     */
    public function testPattern()
    {
        $configData = $this->getSliceData();
        
        $pattern = $this->routes->pattern($configData);
        $this->assertInstance($pattern, $this->classMap['pattern'], array(
            'builder'     => $this->builder,
            'configData'  => $configData
        ));
    }
    
    /**
     * @covers ::prefix
     * @covers ::<protected>
     */
    public function testPrefix()
    {
        $configData = $this->getSliceData();
        
        $pattern = $this->routes->prefix($configData);
        $this->assertInstance($pattern, $this->classMap['prefix'], array(
            'builder'     => $this->builder,
            'configData'  => $configData
        ));
    }
    
    /**
     * @covers ::mount
     * @covers ::<protected>
     */
    public function testMount()
    {
        $configData = $this->getSliceData();
        
        $pattern = $this->routes->mount($configData);
        $this->assertInstance($pattern, $this->classMap['mount'], array(
            'routeRegistry' => $this->routeRegistry,
            'configData'    => $configData
        ));
        
        $routes = new \PHPixie\Router\Routes($this->builder);
        $this->assertException(function() use($routes, $configData){
            $routes->mount($configData);
        }, '\PHPixie\Router\Exception');
    }
    
    /**
     * @covers ::buildFromConfig
     * @covers ::<protected>
     */
    public function testBuildFromConfig()
    {
        foreach($this->classMap as $type => $class) {
            $configData = $this->getSliceData();
            $this->method($configData, 'get', $type, array('type'), 0);
            $route = $this->routes->buildFromConfig($configData);
            $this->assertInstance($route, $class);
        }
        
        $routes = $this->routes;
        $configData = $this->getSliceData();
        $this->method($configData, 'get', 'pixie', array('type'), 0);
        
        $this->assertException(function() use($routes, $configData){
            $routes->buildFromConfig($configData);
        }, '\PHPixie\Router\Exception');
    }
    
    protected function getSliceData()
    {
        return $this->abstractMock('\PHPixie\Slice\Data');
    }
}