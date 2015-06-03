<?php

namespace PHPixie\Tests\Router\Routes\Route;

/**
 * @coversDefaultClass \PHPixie\Router\Routes\Route\Group
 */
class GroupTest extends \PHPixie\Test\Testcase
{
    protected $routeBuilder;
    protected $configData;
    
    protected $group;
    
    protected $routeNames = array('pixie', 'trixie', 'stella');
    
    public function setUp()
    {
        $this->routeBuilder = $this->quickMock('\PHPixie\Router\Routes\Builder');
        $this->configData   = $this->getSliceData();
        
        $this->group = new \PHPixie\Router\Routes\Route\Group(
            $this->routeBuilder,
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
     * @covers ::names
     * @covers ::<protected>
     */
    public function testNames()
    {
        $this->prepareRouteNames();
        for($i=0; $i<2; $i++) {
            $this->assertSame($this->routeNames, $this->group->names());
        }
    }
    
    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $this->prepareRouteNames();
        foreach($this->routeNames as $key => $name) {
            $configAt = $key === 0 ? 1 : 0;
            $route = $this->prepareRoute($name, $configAt);
            for($i=0; $i<2; $i++) {
                $this->assertSame($route, $this->group->get($name));
            }
        }
    }
    
    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGetException()
    {
        $this->prepareRouteNames();
        $this->setExpectedException('\PHPixie\Router\Exception\Route');
        $this->group->get('fairy');
    }
    
    /**
     * @covers ::match
     * @covers ::<protected>
     */
    public function testMatch()
    {
        $configAt       = 0;
        $routesAt = 0;
        $fragment       = $this->getFragment();
        $match          = $this->getMatch();
        
        $this->prepareRouteNames($configAt);
        for($i=0; $i<2; $i++) {
            $route = $this->prepareRoute($this->routeNames[$i], $configAt, $routesAt);
            $return = $i == 1 ? $match : null;
            $this->method($route, 'match', $return, array($fragment), 0);
        }
        
        $this->method($match, 'prependRoutePath', null, array($this->routeNames[1]), 0);
        
        $this->assertSame($match, $this->group->match($fragment));
    }
    
    /**
     * @covers ::match
     * @covers ::<protected>
     */
    public function testNotMatched()
    {
        $configAt = 0;
        $routesAt = 0;
        $fragment       = $this->getFragment();
        
        $this->prepareRouteNames($configAt);
        foreach($this->routeNames as $key => $name) {
            $route = $this->prepareRoute($name, $configAt, $routesAt);
            $this->method($route, 'match', null, array($fragment), 0);
        }
        
        $this->assertSame(null, $this->group->match($fragment));
    }
    
    /**
     * @covers ::generate
     * @covers ::<protected>
     */
    public function testGenerate()
    {
        $routeName = $this->routeNames[1];
        $this->prepareRouteNames();
        $configAt = 1;
        $route = $this->prepareRoute($routeName, $configAt);
        
        $this->generateTest($routeName, $route);
        $this->generateTest($routeName, $route, true);
    }
    
    protected function generateTest($routeName, $route, $withHost = false)
    {
        $match    = $this->getMatch();
        $fragment = $this->getFragment();
        
        $this->method($match, 'popRoutePath', $routeName, array(), 0);
        
        $params = array($match);
        if($withHost) {
            $params[]= true;
        }
        
        $this->method($route, 'generate', $fragment, $params, 0);
        $this->assertSame($fragment, call_user_func_array(array($this->group, 'generate'), $params));
    }
    
    protected function prepareRouteNames(&$configAt = 0)
    {
        $this->method($this->configData, 'keys', $this->routeNames, array(), $configAt++);
    }
    
    protected function prepareRoute($name, &$configAt = 0, &$routesAt = 0)
    {
        $slice = $this->getSliceData();
        $this->method($this->configData, 'slice', $slice, array($name), $configAt++);
        
        $route = $this->quickMock('\PHPixie\Router\Routes\Route');
        $this->method($this->routeBuilder, 'buildFromConfig', $route, array($slice), $routesAt++);
        
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