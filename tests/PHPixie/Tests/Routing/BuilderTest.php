<?php

namespace PHPixie\Tests\Builder;

/**
 * @coversDefaultClass \PHPixie\Routing\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $builder;
    
    public function setUp()
    {
        $this->builder = new \PHPixie\Routing\Builder();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::matcherPattern
     * @covers ::<protected>
     */
    public function testMatcherPattern()
    {
        $attributes = array('t' => 1);
        
        $pattern = $this->builder->matcherPattern('pixie', '[a-z]+', $attributes);
        $this->assertInstance($pattern, '\PHPixie\Routing\Matcher\Pattern', array(
            'pattern'                 => 'pixie',
            'defaultParameterPattern' => '[a-z]+',
            'parameterPatterns'       => $attributes
        ));
        
        $pattern = $this->builder->matcherPattern('pixie');
        $this->assertInstance($pattern, '\PHPixie\Routing\Matcher\Pattern', array(
            'pattern'                 => 'pixie',
            'defaultParameterPattern' => '.+?',
            'parameterPatterns'       => array()
        ));
    }
    
    /**
     * @covers ::translatorMatch
     * @covers ::<protected>
     */
    public function testTranslatorMatch()
    {
        $attributes = array('t' => 1);
        
        $match = $this->builder->translatorMatch('pixie', $attributes);
        $this->assertInstance($match, '\PHPixie\Routing\Translator\Match', array(
            'routePath'  => 'pixie',
            'attributes' => $attributes
        ));
        
        $match = $this->builder->translatorMatch();
        $this->assertInstance($match, '\PHPixie\Routing\Translator\Match', array(
            'routePath'  => null,
            'attributes' => array()
        ));
    }
    
    /**
     * @covers ::translatorFragment
     * @covers ::<protected>
     */
    public function testTranslatorFragment()
    {
        $serverRequest = $this->getServerRequest();
        
        $fragment = $this->builder->translatorFragment('pixie', 'trixie', $serverRequest);
        $this->assertInstance($fragment, '\PHPixie\Routing\Translator\Fragment', array(
            'path'          => 'pixie',
            'host'          => 'trixie',
            'serverRequest' => $serverRequest
        ));
        
        $fragment = $this->builder->translatorFragment();
        $this->assertInstance($fragment, '\PHPixie\Routing\Translator\Fragment', array(
            'path'          => null,
            'host'          => null,
            'serverRequest' => null
        ));
    }
    
    /**
     * @covers ::translatorTarget
     * @covers ::<protected>
     */
    public function testTranslatorTarget()
    {
        $translator = $this->quickMock('\PHPixie\Routing\Translator');
        
        $target = $this->builder->translatorTarget($translator, 'pixie');
        $this->assertInstance($target, '\PHPixie\Routing\Translator\Target', array(
            'routePath'  => 'pixie',
            'translator' => $translator
        ));
    }
    
    /**
     * @covers ::matcher
     * @covers ::<protected>
     */
    public function testMatcher()
    {
        $matcher = $this->builder->matcher();
        $this->assertInstance($matcher, '\PHPixie\Routing\Matcher');
        $this->assertSame($matcher, $this->builder->matcher());
    }
    
    /**
     * @covers ::routes
     * @covers ::<protected>
     */
    public function testRoutes()
    {
        $routes = $this->builder->routes();
        $this->assertInstance($routes, '\PHPixie\Routing\Routes', array(
            'builder'       => $this->builder
        ));
        $this->assertSame($routes, $this->builder->routes());
        
        $this->builder = new \PHPixie\Routing\Builder($this->configData);
        $this->assertInstance($this->builder->routes(), '\PHPixie\Routing\Routes', array(
            'builder'       => $this->builder,
            'routeRegistry' => null,
        ));
    }
    
    /**
     * @covers ::translator
     * @covers ::<protected>
     */
    public function testTranslator()
    {
        $configData           = $this->getSliceData();
        $route                = $this->quickMock('\PHPixie\Routing\Routes\Route');
        $httpContextContainer = $this->quickMock('\PHPixie\HTTP\Context\Container');
        
        $this->method($configData, 'get', 'pixie', array('basePath', '/'), 0);
        $this->method($configData, 'get', 'trixie', array('baseHost', ''), 1);
        
        $translator = $this->builder->translator($route, $configData, $httpContextContainer);
        $this->assertInstance($translator, '\PHPixie\Routing\Translator', array(
            'builder'              => $this->builder,
            'route'                => $route,
            'httpContextContainer' => $httpContextContainer,
            'basePath'             => 'pixie',
            'baseHost'             => 'trixie',
        ));
        
        $translator = $this->builder->translator($route, $configData);
        $this->assertInstance($translator, '\PHPixie\Routing\Translator', array(
            'builder'              => $this->builder,
            'route'                => $route,
            'httpContextContainer' => null,
        ));
    }
    
    protected function getSliceData()
    {
        return $this->quickMock('\PHPixie\Slice\Data');
    }
    
    protected function getServerRequest()
    {
        return $this->quickMock('\Psr\Http\Message\ServerRequestInterface');
    }
}
