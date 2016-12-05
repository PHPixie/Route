<?php

namespace PHPixie\Tests\Route\Translator;

/**
 * @coversDefaultClass \PHPixie\Route\Translator\Target
 */
class TargetTest extends \PHPixie\Test\Testcase
{
    protected $translator;
    protected $resolverPath = 'pixie';
    
    protected $target;
    
    public function setUp()
    {
        $this->translator = $this->quickMock('\PHPixie\Route\Translator');
        
        $this->target  = new \PHPixie\Route\Translator\Target(
            $this->translator,
            $this->resolverPath
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
     * @covers ::resolverPath
     * @covers ::<protected>
     */
    public function testResolverPath()
    {
        $this->assertSame($this->resolverPath, $this->target->resolverPath());
    }
    
    /**
     * @covers ::path
     * @covers ::<protected>
     */
    public function testPath()
    {
        $attributes = array('t' => 1);
        
        $this->method($this->translator, 'generatePath', '/trixie', array($this->resolverPath, $attributes), 0);
        $this->assertSame('/trixie', $this->target->path($attributes));
        
        $this->method($this->translator, 'generatePath', '/fairy', array($this->resolverPath, array()), 0);
        $this->assertSame('/fairy', $this->target->path());
    }
    
    /**
     * @covers ::uri
     * @covers ::<protected>
     */
    public function testUri()
    {
        $attributes = array('t' => 1);
        $serverRequest = $this->quickMock('\Psr\Http\Message\ServerRequestInterface');
        
        $uri = $this->getUri();
        $this->method(
            $this->translator,
            'generateUri',
            $uri,
            array($this->resolverPath, $attributes, true, $serverRequest),
            0
        );
        
        $this->assertSame($uri, $this->target->uri($attributes, true, $serverRequest));
        
        $uri = $this->getUri();
        $this->method(
            $this->translator,
            'generateUri',
            $uri,
            array($this->resolverPath, array(), false, null),
            0
        );
        $this->assertSame($uri, $this->target->uri());
    }
    
    protected function getUri()
    {
        return $this->quickMock('\Psr\Http\Message\UriInterface');
    }
}