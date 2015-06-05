<?php

namespace PHPixie\Tests\Route\Resolvers\Resolver;

/**
 * @coversDefaultClass \PHPixie\Route\Resolvers\Resolver\Group
 */
class GroupTest extends \PHPixie\Test\Testcase
{
    protected $resolverBuilder;
    protected $configData;
    
    protected $group;
    
    protected $resolverNames = array('pixie', 'trixie', 'stella');
    
    public function setUp()
    {
        $this->resolverBuilder = $this->quickMock('\PHPixie\Route\Resolvers\Builder');
        $this->configData   = $this->getSliceData();
        
        $this->group = new \PHPixie\Route\Resolvers\Resolver\Group(
            $this->resolverBuilder,
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
        $this->prepareResolverNames();
        for($i=0; $i<2; $i++) {
            $this->assertSame($this->resolverNames, $this->group->names());
        }
    }
    
    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $this->prepareResolverNames();
        foreach($this->resolverNames as $key => $name) {
            $configAt = $key === 0 ? 1 : 0;
            $resolver = $this->prepareResolver($name, $configAt);
            for($i=0; $i<2; $i++) {
                $this->assertSame($resolver, $this->group->get($name));
            }
        }
    }
    
    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGetException()
    {
        $this->prepareResolverNames();
        $this->setExpectedException('\PHPixie\Route\Exception\Route');
        $this->group->get('fairy');
    }
    
    /**
     * @covers ::match
     * @covers ::<protected>
     */
    public function testMatch()
    {
        $configAt       = 0;
        $resolversAt = 0;
        $fragment       = $this->getFragment();
        $match          = $this->getMatch();
        
        $this->prepareResolverNames($configAt);
        for($i=0; $i<2; $i++) {
            $resolver = $this->prepareResolver($this->resolverNames[$i], $configAt, $resolversAt);
            $return = $i == 1 ? $match : null;
            $this->method($resolver, 'match', $return, array($fragment), 0);
        }
        
        $this->method($match, 'prependResolverPath', null, array($this->resolverNames[1]), 0);
        
        $this->assertSame($match, $this->group->match($fragment));
    }
    
    /**
     * @covers ::match
     * @covers ::<protected>
     */
    public function testNotMatched()
    {
        $configAt = 0;
        $resolversAt = 0;
        $fragment       = $this->getFragment();
        
        $this->prepareResolverNames($configAt);
        foreach($this->resolverNames as $key => $name) {
            $resolver = $this->prepareResolver($name, $configAt, $resolversAt);
            $this->method($resolver, 'match', null, array($fragment), 0);
        }
        
        $this->assertSame(null, $this->group->match($fragment));
    }
    
    /**
     * @covers ::generate
     * @covers ::<protected>
     */
    public function testGenerate()
    {
        $resolverName = $this->resolverNames[1];
        $this->prepareResolverNames();
        $configAt = 1;
        $resolver = $this->prepareResolver($resolverName, $configAt);
        
        $this->generateTest($resolverName, $resolver);
        $this->generateTest($resolverName, $resolver, true);
    }
    
    protected function generateTest($resolverName, $resolver, $withHost = false)
    {
        $match    = $this->getMatch();
        $fragment = $this->getFragment();
        
        $this->method($match, 'popResolverPath', $resolverName, array(), 0);
        
        $params = array($match);
        if($withHost) {
            $params[]= true;
        }
        
        $this->method($resolver, 'generate', $fragment, $params, 0);
        $this->assertSame($fragment, call_user_func_array(array($this->group, 'generate'), $params));
    }
    
    protected function prepareResolverNames(&$configAt = 0)
    {
        $this->method($this->configData, 'keys', $this->resolverNames, array(), $configAt++);
    }
    
    protected function prepareResolver($name, &$configAt = 0, &$resolversAt = 0)
    {
        $slice = $this->getSliceData();
        $this->method($this->configData, 'slice', $slice, array($name), $configAt++);
        
        $resolver = $this->quickMock('\PHPixie\Route\Resolvers\Resolver');
        $this->method($this->resolverBuilder, 'buildFromConfig', $resolver, array($slice), $resolversAt++);
        
        return $resolver;
    }
    
    protected function getSliceData()
    {
        return $this->quickMock('\PHPixie\Slice\Data');
    }
    
    protected function getFragment()
    {
        return $this->quickMock('\PHPixie\Route\Translator\Fragment');
    }
    
    protected function getMatch()
    {
        return $this->quickMock('\PHPixie\Route\Translator\Match');
    }
}