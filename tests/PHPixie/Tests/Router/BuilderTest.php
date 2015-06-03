<?php

namespace PHPixie\Tests\Builder;

/**
 * @coversDefaultClass \PHPixie\Router\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $configData;
    protected $httpContextContainer;
    protected $routeRegistry;

    protected $builder;
    
    public function setUp()
    {
        $this->configData           = $this->getSliceData();
        $this->httpContextContainer = $this->quickMock('\PHPixie\HTTP\Context\Container');
        $this->routeRegistry        = $this->quickMock('\PHPixie\Router\Routes\Registry');
        
        $this->builder = new \PHPixie\Router\Builder(
            $this->configData,
            $this->httpContextContainer,
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
     * @covers ::matcherPattern
     * @covers ::<protected>
     */
    public function testMatcherPattern()
    {
        $attributes = array('t' => 1);
        
        $pattern = $this->builder->matcherPattern('pixie', '[a-z]+', $attributes);
        $this->assertInstance($pattern, '\PHPixie\Router\Matcher\Pattern', array(
            'pattern'                 => 'pixie',
            'defaultParameterPattern' => '[a-z]+',
            'parameterPatterns'       => $attributes
        ));
        
        $pattern = $this->builder->matcherPattern('pixie');
        $this->assertInstance($pattern, '\PHPixie\Router\Matcher\Pattern', array(
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
        $this->assertInstance($match, '\PHPixie\Router\Translator\Match', array(
            'routePath'  => 'pixie',
            'attributes' => $attributes
        ));
        
        $match = $this->builder->translatorMatch();
        $this->assertInstance($match, '\PHPixie\Router\Translator\Match', array(
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
        $this->assertInstance($fragment, '\PHPixie\Router\Translator\Fragment', array(
            'path'          => 'pixie',
            'host'          => 'trixie',
            'serverRequest' => $serverRequest
        ));
        
        $fragment = $this->builder->translatorFragment();
        $this->assertInstance($fragment, '\PHPixie\Router\Translator\Fragment', array(
            'path'          => null,
            'host'          => null,
            'serverRequest' => null
        ));
    }
    
    /**
     * @covers ::target
     * @covers ::<protected>
     */
    public function testTarget()
    {
        $this->prepareTranslator();
        
        $target = $this->builder->target('pixie');
        $this->assertInstance($target, '\PHPixie\Router\Target', array(
            'routePath'  => 'pixie',
            'translator' => $this->builder->translator()
        ));
    }
    
    /**
     * @covers ::matcher
     * @covers ::<protected>
     */
    public function testMatcher()
    {
        $matcher = $this->builder->matcher();
        $this->assertInstance($matcher, '\PHPixie\Router\Matcher');
        $this->assertSame($matcher, $this->builder->matcher());
    }
    
    /**
     * @covers ::routes
     * @covers ::<protected>
     */
    public function testRoutes()
    {
        $routes = $this->builder->routes();
        $this->assertInstance($routes, '\PHPixie\Router\Routes', array(
            'builder'       => $this->builder,
            'routeRegistry' => $this->routeRegistry,
        ));
        $this->assertSame($routes, $this->builder->routes());
        
        $this->builder = new \PHPixie\Router\Builder($this->configData);
        $this->assertInstance($this->builder->routes(), '\PHPixie\Router\Routes', array(
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
        $this->prepareTranslator();
        
        $translator = $this->builder->translator();
        $this->assertInstance($translator, '\PHPixie\Router\Translator', array(
            'builder'              => $this->builder,
            'httpContextContainer' => $this->httpContextContainer,
        ));
        $this->assertSame($translator, $this->builder->translator());
        
        $this->builder = new \PHPixie\Router\Builder($this->configData);
        $this->prepareTranslator();
        $this->assertInstance($this->builder->translator(), '\PHPixie\Router\Translator', array(
            'builder'              => $this->builder,
            'httpContextContainer' => null,
        ));
    }
    
    protected function prepareTranslator()
    {
        $routeConfig = $this->getSliceData();
        $this->method($this->configData, 'slice', $routeConfig, array('route'), 2);
        $this->method($routeConfig, 'get', 'group', array('type', 'pattern'), 0);
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