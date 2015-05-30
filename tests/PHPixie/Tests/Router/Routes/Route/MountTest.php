<?php

namespace PHPixie\Tests\Router\Routes\Route;

/**
 * @coversDefaultClass \PHPixie\Router\Routes\Route\Mount
 */
class MountTest extends \PHPixie\Test\Testcase
{
    protected $routeRegistry;
    protected $configData;
    
    protected $route;
    
    public function setUp()
    {
        $this->routeRegistry = $this->quickMock('\PHPixie\Router\Routes\Registry');
        $this->configData    = $this->getSliceData();
        
        $this->route = new \PHPixie\Router\Routes\Route\Mount(
            $this->routeRegistry,
            $this->configData
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
     * @covers ::route
     * @covers ::<protected>
     */
    public function testRoute()
    {
        $route = $this->prepareRoute();
        
        for($i = 0; $i < 2; $i++) {
            $this->assertSame($route, $this->route->route());
        }
    }
    
    /**
     * @covers ::match
     * @covers ::<protected>
     */
    public function testMatch()
    {
        $route = $this->prepareRoute();
        
        for($i = 0; $i < 2; $i++) {
            $fragment = $this->getFragment();
            $match    = $this->getMatch();
            
            $this->method($route, 'match', $match, array($fragment), 0);
            $this->assertSame($match, $this->route->match($fragment));
        }
    }
    
    /**
     * @covers ::generate
     * @covers ::<protected>
     */
    public function testGenerate()
    {
        $route = $this->prepareRoute();
        
        foreach(array(true, false) as $withHost) {
            $match    = $this->getMatch();
            $fragment = $this->getFragment();
            
            $params = array($match, $withHost);
            $this->method($route, 'generate', $fragment, $params, 0);
            
            $this->assertSame($fragment, call_user_func_array(array($this->route, 'generate'), $params));
        }
    }
    
    protected function prepareRoute()
    {
        $this->method($this->configData, 'getRequired', 'pixie', array('name'), 0);
        
        $route = $this->quickMock('\PHPixie\Router\Routes\Route');
        $this->method($this->routeRegistry, 'get', $route, array('pixie'), 0);
        
        return $route;
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
}