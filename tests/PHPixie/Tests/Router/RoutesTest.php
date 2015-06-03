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
        $configData   = $this->getSliceData();
        $routeBuilder = $this->getRouteBuilder();
        
        $group = $this->routes->group($routeBuilder, $configData);
        $this->assertInstance($group, $this->classMap['group'], array(
            'routeBuilder' => $routeBuilder,
            'configData'   => $configData
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
        $configData   = $this->getSliceData();
        $routeBuilder = $this->getRouteBuilder();
        
        $pattern = $this->routes->prefix($routeBuilder, $configData);
        $this->assertInstance($pattern, $this->classMap['prefix'], array(
            'routeBuilder' => $routeBuilder,
            'configData'   => $configData
        ));
    }
    
    /**
     * @covers ::mount
     * @covers ::<protected>
     */
    public function testMount()
    {
        $configData    = $this->getSliceData();
        $routeRegistry = $this->getRouteRegistry();
        
        $pattern = $this->routes->mount($routeRegistry, $configData);
        $this->assertInstance($pattern, $this->classMap['mount'], array(
            'routeRegistry' => $routeRegistry,
            'configData'    => $configData
        ));
    }
    
    /**
     * @covers ::builder
     * @covers ::<protected>
     */
    public function testBuilder()
    {
        $routeRegistry = $this->getRouteRegistry();

        $builder = $this->routes->builder($routeRegistry);
        $this->assertInstance($builder, '\PHPixie\Router\Routes\Builder', array(
            'routeRegistry' => $routeRegistry
        ));
        
        $builder = $this->routes->builder();
        $this->assertInstance($builder, '\PHPixie\Router\Routes\Builder', array(
            'routeRegistry' => null
        ));
    }
    
    /**
     * @covers ::configRegistry
     * @covers ::<protected>
     */
    public function testConfigRegistry()
    {
        $routeBuilder = $this->getRouteBuilder();
        $configData   = $this->getSliceData();
        
        $routeRegistry = $this->routes->configRegistry($routeBuilder, $configData);
        $this->assertInstance($routeRegistry, '\PHPixie\Router\Routes\Registry\Config', array(
            'routeBuilder' => $routeBuilder,
            'configData'   => $configData
        ));
    }
    
    protected function getSliceData()
    {
        return $this->quickMock('\PHPixie\Slice\Data');
    }
    
    protected function getRouteBuilder()
    {
        return $this->quickMock('\PHPixie\Router\Routes\Builder');
    }
    
    protected function getRouteRegistry()
    {
        return $this->quickMock('\PHPixie\Router\Routes\Registry');
    }
}