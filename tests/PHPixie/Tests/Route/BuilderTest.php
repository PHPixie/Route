<?php

namespace PHPixie\Tests\Route;

/**
 * @coversDefaultClass \PHPixie\Route\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $builder;
    
    public function setUp()
    {
        $this->builder = new \PHPixie\Route\Builder();
    }
    
    /**
     * @covers ::matcherPattern
     * @covers ::<protected>
     */
    public function testMatcherPattern()
    {
        $attributes = array('t' => 1);
        
        $pattern = $this->builder->matcherPattern('pixie', '[a-z]+', $attributes);
        $this->assertInstance($pattern, '\PHPixie\Route\Matcher\Pattern', array(
            'pattern'                 => 'pixie',
            'defaultParameterPattern' => '[a-z]+',
            'parameterPatterns'       => $attributes
        ));
        
        $pattern = $this->builder->matcherPattern('pixie');
        $this->assertInstance($pattern, '\PHPixie\Route\Matcher\Pattern', array(
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
        
        $match = $this->builder->translatorMatch($attributes, 'pixie');
        $this->assertInstance($match, '\PHPixie\Route\Translator\Match', array(
            'attributes' => $attributes,
            'resolverPath'  => 'pixie'
        ));
        
        $match = $this->builder->translatorMatch();
        $this->assertInstance($match, '\PHPixie\Route\Translator\Match', array(
            'attributes'   => array(),
            'resolverPath' => null
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
        $this->assertInstance($fragment, '\PHPixie\Route\Translator\Fragment', array(
            'path'          => 'pixie',
            'host'          => 'trixie',
            'serverRequest' => $serverRequest
        ));
        
        $fragment = $this->builder->translatorFragment();
        $this->assertInstance($fragment, '\PHPixie\Route\Translator\Fragment', array(
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
        $translator = $this->quickMock('\PHPixie\Route\Translator');
        
        $target = $this->builder->translatorTarget($translator, 'pixie');
        $this->assertInstance($target, '\PHPixie\Route\Translator\Target', array(
            'resolverPath'  => 'pixie',
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
        $this->assertInstance($matcher, '\PHPixie\Route\Matcher');
        $this->assertSame($matcher, $this->builder->matcher());
    }
    
    /**
     * @covers ::resolvers
     * @covers ::<protected>
     */
    public function testResolvers()
    {
        $resolvers = $this->builder->resolvers();
        $this->assertInstance($resolvers, '\PHPixie\Route\Resolvers', array(
            'builder'       => $this->builder
        ));
        $this->assertSame($resolvers, $this->builder->resolvers());
        
        $this->builder = new \PHPixie\Route\Builder($this->configData);
        $this->assertInstance($this->builder->resolvers(), '\PHPixie\Route\Resolvers', array(
            'builder'       => $this->builder,
            'resolverRegistry' => null,
        ));
    }
    
    /**
     * @covers ::translator
     * @covers ::<protected>
     */
    public function testTranslator()
    {
        $configData           = $this->getSliceData();
        $resolver                = $this->quickMock('\PHPixie\Route\Resolvers\Resolver');
        $httpContextContainer = $this->quickMock('\PHPixie\HTTP\Context\Container');
        
        $this->method($configData, 'get', 'pixie', array('basePath', '/'), 0);
        $this->method($configData, 'get', 'trixie', array('baseHost', ''), 1);
        
        $translator = $this->builder->translator($resolver, $configData, $httpContextContainer);
        $this->assertInstance($translator, '\PHPixie\Route\Translator', array(
            'builder'              => $this->builder,
            'resolver'                => $resolver,
            'httpContextContainer' => $httpContextContainer,
            'basePath'             => 'pixie',
            'baseHost'             => 'trixie',
        ));
        
        $translator = $this->builder->translator($resolver, $configData);
        $this->assertInstance($translator, '\PHPixie\Route\Translator', array(
            'builder'              => $this->builder,
            'resolver'                => $resolver,
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
