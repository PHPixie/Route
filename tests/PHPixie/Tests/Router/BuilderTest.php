<?php

namespace PHPixie\Tests\Builder;

/**
 * @coversDefaultClass \PHPixie\Router\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $configData;
    protected $builder;
    
    public function setUp()
    {
        $this->configData = $this->getSliceData();
        $this->builder = new \PHPixie\Router\Builder($this->configData);
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
     * @covers ::translator
     * @covers ::<protected>
     */
    public function testTranslator()
    {
        $routeConfig = $this->getSliceData();
        $this->method($this->configData, 'slice', $routeConfig, array('route'));
        $this->method($routeConfig, 'get', 'group', array('type', 'pattern'), 0);
        
        $translator = $this->builder->translator();
        $this->assertInstance($translator, '\PHPixie\Router\Translator', array(
            'builder'    => $this->builder
        ));
        $this->assertSame($translator, $this->builder->translator());
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