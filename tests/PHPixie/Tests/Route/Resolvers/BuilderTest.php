<?php

namespace PHPixie\Tests\Resolvers\Locators;

/**
 * @coversDefaultClass \PHPixie\Route\Resolvers\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $resolvers;
    protected $locatorRegistry;
    
    protected $builder;
    
    public function setUp()
    {
        $this->resolvers        = $this->quickMock('\PHPixie\Route\Resolvers');
        $this->resolverRegistry = $this->quickMock('\PHPixie\Route\Resolvers\Registry');
        
        $this->builder = new \PHPixie\Route\Resolvers\Builder(
            $this->resolvers,
            $this->resolverRegistry
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
     * @covers ::buildFromConfig
     * @covers ::<protected>
     */
    public function testBuildFromConfig()
    {
        $configData = $this->abstractMock('\PHPixie\Slice\Data');
        
        $types = array(
            'group'   => array($this->builder, $configData),
            'pattern' => array($configData),
            'prefix'  => array($this->builder, $configData),
            'mount'   => array($this->resolverRegistry, $configData)
        );
        
        foreach($types as $type => $parameters) {
            $this->method($configData, 'get', $type, array('type', 'pattern'), 0);
            
            $resolver = $this->quickMock('\PHPixie\Route\Resolvers\Resolver');
            $this->method($this->resolvers, $type, $resolver, $parameters, 0);
            
            $this->assertSame($resolver, $this->builder->buildFromConfig($configData));
        }
        
        $builder = new \PHPixie\Route\Resolvers\Builder(
            $this->resolvers
        );
        
        $this->method($configData, 'get', 'mount', array('type', 'pattern'), 0);
        
        $this->assertException(function() use($builder, $configData) {
            $builder->buildFromConfig($configData);
        }, '\PHPixie\Route\Exception');
    }
}