<?php

namespace PHPixie\Tests\Router;

/**
 * @coversDefaultClass \PHPixie\Router\Target
 */
class TargetTest extends \PHPixie\Test\Testcase
{
    protected $translator;
    protected $routePath = 'pixie';
    
    protected $target;
    
    public function setUp()
    {
        $this->translator = $this->quickMock('\PHPixie\Router\Translator');
        
        $this->target  = new \PHPixie\Router\Target(
            $this->translator,
            $this->routePath
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
     * @covers ::routePath
     * @covers ::<protected>
     */
    public function testRoutePath()
    {
        $this->assertSame($this->routePath, $this->target->routePath());
    }
    
    /**
     * @covers ::path
     * @covers ::<protected>
     */
    public function testPath()
    {
        $attributes = array('t' => 1);
        
        $this->method($this->translator, 'generatePath', '/trixie', array($this->routePath, $attributes), 0);
        $this->assertSame('/trixie', $this->target->path($attributes));
        
        $this->method($this->translator, 'generatePath', '/fairy', array($this->routePath, array()), 0);
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
            array($this->routePath, $attributes, true, $serverRequest),
            0
        );
        
        $this->assertSame($uri, $this->target->uri($attributes, true, $serverRequest));
        
        $uri = $this->getUri();
        $this->method(
            $this->translator,
            'generateUri',
            $uri,
            array($this->routePath, array(), false, null),
            0
        );
        $this->assertSame($uri, $this->target->uri());
    }
    
    protected function getUri()
    {
        return $this->quickMock('\Psr\Http\Message\UriInterface');
    }
}