<?php

namespace PHPixie\Tests\Route\Resolvers\Resolver;

/**
 * @coversDefaultClass \PHPixie\Route\Resolvers\Resolver\Mount
 */
class MountTest extends \PHPixie\Test\Testcase
{
    protected $resolverRegistry;
    protected $configData;
    
    protected $resolver;
    
    public function setUp()
    {
        $this->resolverRegistry = $this->quickMock('\PHPixie\Route\Resolvers\Registry');
        $this->configData    = $this->getSliceData();
        
        $this->resolver = new \PHPixie\Route\Resolvers\Resolver\Mount(
            $this->resolverRegistry,
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
     * @covers ::resolver
     * @covers ::<protected>
     */
    public function testRoute()
    {
        $resolver = $this->prepareResolver();
        
        for($i = 0; $i < 2; $i++) {
            $this->assertSame($resolver, $this->resolver->resolver());
        }
    }
    
    /**
     * @covers ::match
     * @covers ::<protected>
     */
    public function testMatch()
    {
        $resolver = $this->prepareResolver();
        
        for($i = 0; $i < 2; $i++) {
            $fragment = $this->getFragment();
            $match    = $this->getMatch();
            
            $this->method($resolver, 'match', $match, array($fragment), 0);
            $this->assertSame($match, $this->resolver->match($fragment));
        }
    }
    
    /**
     * @covers ::generate
     * @covers ::<protected>
     */
    public function testGenerate()
    {
        $resolver = $this->prepareResolver();
        
        foreach(array(true, false) as $withHost) {
            $match    = $this->getMatch();
            $fragment = $this->getFragment();
            
            $params = array($match, $withHost);
            $this->method($resolver, 'generate', $fragment, $params, 0);
            
            $this->assertSame($fragment, call_user_func_array(array($this->resolver, 'generate'), $params));
        }
    }
    
    protected function prepareResolver()
    {
        $this->method($this->configData, 'getRequired', 'pixie', array('name'), 0);
        
        $resolver = $this->quickMock('\PHPixie\Route\Resolvers\Resolver');
        $this->method($this->resolverRegistry, 'get', $resolver, array('pixie'), 0);
        
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