<?php

namespace PHPixie\Tests;

/**
 * @coversDefaultClass \PHPixie\Routing
 */
class RoutingTest extends \PHPixie\Test\Testcase
{
    protected $routing;
    
    protected $builder;
    protected $routes;
    protected $translator;
    
    public function setUp()
    {
        $this->routing = $this->getMockBuilder('\PHPixie\Routing')
            ->setMethods(array('buildBuilder'))
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->builder = $this->quickMock('\PHPixie\Routing\Builder');
        $this->method($this->routing, 'buildBuilder', $this->builder, array(), 0);
        
        $this->routing->__construct();
        
        $this->routes = $this->quickMock('\PHPixie\Routing\Routes');
        $this->method($this->builder, 'routes', $this->routes, array());
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstructor()
    {
        
    }
    
    /**
     * @covers ::builder
     * @covers ::<protected>
     */
    public function testBuilder()
    {
        $this->assertSame($this->builder, $this->routing->builder());
    }
    
    /**
     * @covers ::translator
     * @covers ::<protected>
     */
    public function testTranslator()
    {
        $route                = $this->quickMock('\PHPixie\Routing\Routes\Route');
        $configData           = $this->getSliceData();
        
        foreach(array(false, true) as $withHttpContainer) {
            if($withHttpContainer) {
                $httpContextContainer = $this->quickMock('\PHPixie\HTTP\Context\Container');
            }else{
                $httpContextContainer = null;
            }
            
            $translator = $this->quickMock('\PHPixie\Routing\Translator');
            $this->method(
                $this->builder,
                'translator',
                $translator,
                array($route, $configData, $httpContextContainer),
                0
            );
            
            $params = array($route, $configData);
            if($withHttpContainer) {
                $params[]= $httpContextContainer;
            }
            
            $result = call_user_func_array(array($this->routing, 'translator'), $params);
            $this->assertSame($translator, $result);
        }
    }
    
    
    /**
     * @covers ::buildRoute
     * @covers ::<protected>
     */
    public function testBuildRoute()
    {
        $configData = $this->quickMock('\PHPixie\Slice\Data');
        $builder    = $this->quickMock('\PHPixie\Routing\Routes\Builder');
        $route      = $this->quickMock('\PHPixie\Routing\Routes\Route');
        
        $this->method($builder, 'buildFromConfig', $route, array($configData));
        
        foreach(array(false, true) as $withRouteRegistry) {
            if($withRouteRegistry) {
                $routeRegistry = $this->quickMock('\PHPixie\Filesystem\Locators\Registry');
            }else{
                $routeRegistry = null;
            }
            
            $this->method($this->routes, 'builder', $builder, array($routeRegistry), 0);
            
            $params = array($configData);
            if($withRouteRegistry) {
                $params[]= $routeRegistry;
            }
            
            $result = call_user_func_array(array($this->routing, 'buildRoute'), $params);
            $this->assertSame($route, $result);
        }
    }
    
    /**
     * @covers ::configRouteRegistry
     * @covers ::<protected>
     */
    public function testConfigRouteRegistry()
    {
        $configData     = $this->quickMock('\PHPixie\Slice\Data');
        $builder        = $this->quickMock('\PHPixie\Routing\Routes\Builder');
        $configRegistry = $this->quickMock('\PHPixie\Routing\Routes\Registry\Config');
        
        foreach(array(false, true) as $withRouteRegistry) {
            if($withRouteRegistry) {
                $routeRegistry = $this->quickMock('\PHPixie\Routing\Routes\Registry');
            }else{
                $routeRegistry = null;
            }
            
            $this->method($this->routes, 'builder', $builder, array($routeRegistry), 0);
            $this->method(
                $this->routes,
                'configRegistry',
                $configRegistry,
                array($builder, $configData),
                1
            );
            
            $params = array($configData);
            if($withRouteRegistry) {
                $params[]= $routeRegistry;
            }
            
            $result = call_user_func_array(array($this->routing, 'configRouteRegistry'), $params);
            $this->assertSame($configRegistry, $result);
        }
    }
    
    /**
     * @covers ::buildBuilder
     * @covers ::<protected>
     */
    public function testBuildBuilder()
    {
        $routing = new \PHPixie\Routing();
        $this->assertInstance($routing->builder(), '\PHPixie\Routing\Builder');
    }
    
    protected function getSliceData()
    {
        return $this->abstractMock('\PHPixie\Slice\Data');
    }
}