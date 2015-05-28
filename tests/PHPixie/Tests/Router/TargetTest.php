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
        
        $uri = $this->getUri();
        $this->method($this->translator, 'generateUri', $uri, array($this->routePath, $attributes, true), 0);
        $this->assertSame($uri, $this->target->uri($attributes, true));
        
        $uri = $this->getUri();
        $this->method($this->translator, 'generateUri', $uri, array($this->routePath, array(), false), 0);
        $this->assertSame($uri, $this->target->uri());
    }
    
    protected function getUri()
    {
        return $this->quickMock('\Psr\Http\Message\UriInterface');
    }
}