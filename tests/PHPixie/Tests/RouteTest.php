<?php

namespace PHPixie\Tests;

/**
 * @coversDefaultClass \PHPixie\Route
 */
class RouteTest extends \PHPixie\Test\Testcase
{
    protected $resolver;
    
    protected $builder;
    protected $resolvers;
    protected $translator;
    
    public function setUp()
    {
        $this->resolver = $this->getMockBuilder('\PHPixie\Route')
            ->setMethods(array('buildBuilder'))
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->builder = $this->quickMock('\PHPixie\Route\Builder');
        $this->method($this->resolver, 'buildBuilder', $this->builder, array(), 0);
        
        $this->resolver->__construct();
        
        $this->resolvers = $this->quickMock('\PHPixie\Route\Resolvers');
        $this->method($this->builder, 'resolvers', $this->resolvers, array());
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
        $this->assertSame($this->builder, $this->resolver->builder());
    }
    
    /**
     * @covers ::translator
     * @covers ::<protected>
     */
    public function testTranslator()
    {
        $resolver   = $this->quickMock('\PHPixie\Route\Resolvers\Resolver');
        $configData = $this->getSliceData();
        
        foreach(array(false, true) as $withHttpContainer) {
            if($withHttpContainer) {
                $httpContextContainer = $this->quickMock('\PHPixie\HTTP\Context\Container');
            }else{
                $httpContextContainer = null;
            }
            
            $translator = $this->quickMock('\PHPixie\Route\Translator');
            $this->method(
                $this->builder,
                'translator',
                $translator,
                array($resolver, $configData, $httpContextContainer),
                0
            );
            
            $params = array($resolver, $configData);
            if($withHttpContainer) {
                $params[]= $httpContextContainer;
            }
            
            $result = call_user_func_array(array($this->resolver, 'translator'), $params);
            $this->assertSame($translator, $result);
        }
    }
    
    
    /**
     * @covers ::buildResolver
     * @covers ::<protected>
     */
    public function testBuildRoute()
    {
        $configData = $this->quickMock('\PHPixie\Slice\Data');
        $builder    = $this->quickMock('\PHPixie\Route\Resolvers\Builder');
        $resolver   = $this->quickMock('\PHPixie\Route\Resolvers\Resolver');
        
        $this->method($builder, 'buildFromConfig', $resolver, array($configData));
        
        foreach(array(false, true) as $withResolverRegistry) {
            if($withResolverRegistry) {
                $resolverRegistry = $this->quickMock('\PHPixie\Filesystem\Locators\Registry');
            }else{
                $resolverRegistry = null;
            }
            
            $this->method($this->resolvers, 'builder', $builder, array($resolverRegistry), 0);
            
            $params = array($configData);
            if($withResolverRegistry) {
                $params[]= $resolverRegistry;
            }
            
            $result = call_user_func_array(array($this->resolver, 'buildResolver'), $params);
            $this->assertSame($resolver, $result);
        }
    }
    
    /**
     * @covers ::buildBuilder
     * @covers ::<protected>
     */
    public function testBuildBuilder()
    {
        $resolver = new \PHPixie\Route();
        $this->assertInstance($resolver->builder(), '\PHPixie\Route\Builder');
    }
    
    protected function getSliceData()
    {
        return $this->abstractMock('\PHPixie\Slice\Data');
    }
}