<?php

namespace PHPixie\Tests;

/**
 * @coversDefaultClass \PHPixie\Router
 */
class RouterTest extends \PHPixie\Test\Testcase
{
    protected $configData;
    protected $httpContextContainer;
    protected $routeRegistry;
    
    protected $router;
    
    protected $builder;
    protected $routes;
    protected $translator;
    
    public function setUp()
    {
        $this->configData           = $this->getSliceData();
        $this->httpContextContainer = $this->quickMock('\PHPixie\HTTP\Context\Container');
        $this->routeRegistry        = $this->quickMock('\PHPixie\Router\Routes\Registry');
        
        $this->router = $this->getMockBuilder('\PHPixie\Router')
            ->setMethods(array('buildBuilder'))
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->builder = $this->quickMock('\PHPixie\Router\Builder');
        $this->method($this->router, 'buildBuilder', $this->builder, array(
            $this->configData,
            $this->httpContextContainer,
            $this->routeRegistry
        ), 0);
        
        $this->router->__construct(
            $this->configData,
            $this->httpContextContainer,
            $this->routeRegistry
        );
        
        $this->routes = $this->quickMock('\PHPixie\Router\Routes');
        $this->method($this->builder, 'routes', $this->routes, array());
        
        $this->translator = $this->quickMock('\PHPixie\Router\Translator');
        $this->method($this->builder, 'translator', $this->translator, array());
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstructor()
    {
        
    }
    
    /**
     * @covers ::match
     * @covers ::<protected>
     */
    public function testMatch()
    {
        $match = $this->getMatch();
        $serverRequest = $this->getServerRequest();
        $this->method($this->translator, 'match', $match, array($serverRequest), 0);
        $this->assertSame($match, $this->router->match($serverRequest));
        
        $match = $this->getMatch();
        $this->method($this->translator, 'match', $match, array(null), 0);
        $this->assertSame($match, $this->router->match());
    }
    
    /**
     * @covers ::generatePath
     * @covers ::<protected>
     */
    public function testGeneratePath()
    {
        $routePath = 'trixie';
        $attributes = array('t' => 1);
        
        $this->method($this->translator, 'generatePath', '/pixie', array($routePath, $attributes), 0);
        $this->assertSame('/pixie', $this->router->generatePath($routePath, $attributes));
        
        $this->method($this->translator, 'generatePath', '/pixie', array($routePath, array()), 0);
        $this->assertSame('/pixie', $this->router->generatePath($routePath));
    }
    
    /**
     * @covers ::generateUri
     * @covers ::<protected>
     */
    public function testGenerateUri()
    {
        $routePath = 'trixie';
        $attributes = array('t' => 1);
        
        $uri = $this->getUri();
        $this->method($this->translator, 'generateUri', $uri, array($routePath, $attributes, true), 0);
        $this->assertSame($uri, $this->router->generateUri($routePath, $attributes, true));
        
        $uri = $this->getUri();
        $this->method($this->translator, 'generateUri', $uri, array($routePath, array(), false), 0);
        $this->assertSame($uri, $this->router->generateUri($routePath));
    }
    
    
    /**
     * @covers ::target
     * @covers ::<protected>
     */
    public function testTarget()
    {
        $target = $this->quickMock('\PHPixie\Router\Target');
        $this->method($this->builder, 'target', $target, array('pixie'), 0);
        $this->assertSame($target, $this->router->target('pixie'));
    }
    
    /**
     * @covers ::builder
     * @covers ::<protected>
     */
    public function testBuilder()
    {
        $this->assertSame($this->builder, $this->router->builder());
    }
    
    /**
     * @covers ::buildBuilder
     * @covers ::<protected>
     */
    public function testBuildBuilder()
    {
        $router = new \PHPixie\Router(
            $this->configData,
            $this->httpContextContainer,
            $this->routeRegistry
        );
        
        $this->assertInstance($router->builder(), '\PHPixie\Router\Builder', array(
            'configData'           => $this->configData,
            'httpContextContainer' => $this->httpContextContainer,
            'routeRegistry'        => $this->routeRegistry
        ));
        
        $router = new \PHPixie\Router(
            $this->configData
        );
        
        $this->assertInstance($router->builder(), '\PHPixie\Router\Builder', array(
            'configData'           => $this->configData,
            'httpContextContainer' => NULL,
            'routeRegistry'        => null
        ));
    }
    
    /**
     * @covers ::buildRoute
     * @covers ::<protected>
     */
    public function testBuildRoute()
    {
        $configData = $this->quickMock('\PHPixie\Slice\Data');
        $builder    = $this->quickMock('\PHPixie\Router\Routes\Builder');
        $route      = $this->quickMock('\PHPixie\Router\Routes\Route');
        
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
            
            $result = call_user_func_array(array($this->router, 'buildRoute'), $params);
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
        $builder        = $this->quickMock('\PHPixie\Router\Routes\Builder');
        $configRegistry = $this->quickMock('\PHPixie\Router\Routes\Registry\Config');
        
        foreach(array(false, true) as $withRouteRegistry) {
            if($withRouteRegistry) {
                $routeRegistry = $this->quickMock('\PHPixie\Router\Routes\Registry');
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
            
            $result = call_user_func_array(array($this->router, 'configRouteRegistry'), $params);
            $this->assertSame($configRegistry, $result);
        }
    }
    
    protected function getSliceData()
    {
        return $this->abstractMock('\PHPixie\Slice\Data');
    }
    
    protected function getServerRequest()
    {
        return $this->quickMock('\Psr\Http\Message\ServerRequestInterface');
    }
    
    protected function getUri()
    {
        return $this->quickMock('\Psr\Http\Message\UriInterface');
    }
    
    protected function getMatch()
    {
        return $this->quickMock('\PHPixie\Router\Translator\Match');
    }
}