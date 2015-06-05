<?php

namespace PHPixie\Tests\Route;

/**
 * @coversDefaultClass \PHPixie\Route\Resolvers
 */
class ResolversTest extends \PHPixie\Test\Testcase
{
    protected $builder;
    protected $resolverRegistry;
    
    protected $resolvers;
    
    protected $classMap = array(
        'group'   => '\PHPixie\Route\Resolvers\Resolver\Group',
        'mount'  => '\PHPixie\Route\Resolvers\Resolver\Mount',
        'pattern' => '\PHPixie\Route\Resolvers\Resolver\Pattern\Implementation',
        'prefix'  => '\PHPixie\Route\Resolvers\Resolver\Pattern\Prefix'
    );
    
    public function setUp()
    {
        $this->builder       = $this->quickMock('\PHPixie\Route\Builder');
        $this->resolverRegistry = $this->quickMock('\PHPixie\Route\Resolvers\Registry');
        
        $this->resolvers = new \PHPixie\Route\Resolvers(
            $this->builder,
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
     * @covers ::group
     * @covers ::<protected>
     */
    public function testGroup()
    {
        $configData   = $this->getSliceData();
        $resolverBuilder = $this->getRouteBuilder();
        
        $group = $this->resolvers->group($resolverBuilder, $configData);
        $this->assertInstance($group, $this->classMap['group'], array(
            'resolverBuilder' => $resolverBuilder,
            'configData'   => $configData
        ));
    }
    
    /**
     * @covers ::pattern
     * @covers ::<protected>
     */
    public function testPattern()
    {
        $configData = $this->getSliceData();
        
        $pattern = $this->resolvers->pattern($configData);
        $this->assertInstance($pattern, $this->classMap['pattern'], array(
            'builder'     => $this->builder,
            'configData'  => $configData
        ));
    }
    
    /**
     * @covers ::prefix
     * @covers ::<protected>
     */
    public function testPrefix()
    {
        $configData   = $this->getSliceData();
        $resolverBuilder = $this->getRouteBuilder();
        
        $pattern = $this->resolvers->prefix($resolverBuilder, $configData);
        $this->assertInstance($pattern, $this->classMap['prefix'], array(
            'resolverBuilder' => $resolverBuilder,
            'configData'   => $configData
        ));
    }
    
    /**
     * @covers ::mount
     * @covers ::<protected>
     */
    public function testMount()
    {
        $configData    = $this->getSliceData();
        $resolverRegistry = $this->getResolverRegistry();
        
        $pattern = $this->resolvers->mount($resolverRegistry, $configData);
        $this->assertInstance($pattern, $this->classMap['mount'], array(
            'resolverRegistry' => $resolverRegistry,
            'configData'    => $configData
        ));
    }
    
    /**
     * @covers ::builder
     * @covers ::<protected>
     */
    public function testBuilder()
    {
        $resolverRegistry = $this->getResolverRegistry();

        $builder = $this->resolvers->builder($resolverRegistry);
        $this->assertInstance($builder, '\PHPixie\Route\Resolvers\Builder', array(
            'resolverRegistry' => $resolverRegistry
        ));
        
        $builder = $this->resolvers->builder();
        $this->assertInstance($builder, '\PHPixie\Route\Resolvers\Builder', array(
            'resolverRegistry' => null
        ));
    }
    
    /**
     * @covers ::configRegistry
     * @covers ::<protected>
     */
    public function testConfigRegistry()
    {
        $resolverBuilder = $this->getRouteBuilder();
        $configData   = $this->getSliceData();
        
        $resolverRegistry = $this->resolvers->configRegistry($resolverBuilder, $configData);
        $this->assertInstance($resolverRegistry, '\PHPixie\Route\Resolvers\Registry\Config', array(
            'resolverBuilder' => $resolverBuilder,
            'configData'   => $configData
        ));
    }
    
    protected function getSliceData()
    {
        return $this->quickMock('\PHPixie\Slice\Data');
    }
    
    protected function getRouteBuilder()
    {
        return $this->quickMock('\PHPixie\Route\Resolvers\Builder');
    }
    
    protected function getResolverRegistry()
    {
        return $this->quickMock('\PHPixie\Route\Resolvers\Registry');
    }
}