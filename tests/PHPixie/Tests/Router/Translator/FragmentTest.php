<?php

namespace PHPixie\Tests\Router\Translator;

/**
 * @coversDefaultClass \PHPixie\Router\Translator\Fragment
 */
class FragmentTest extends \PHPixie\Test\Testcase
{
    protected $path = 'pixie/trixie';
    protected $host = 'fairy';
    protected $serverRequest;
    
    protected $fragment;
    
    public function setUp()
    {
        $this->serverRequest = $this->getServerRequest();
        
        $this->fragment = new \PHPixie\Router\Translator\Fragment(
            $this->path,
            $this->host,
            $this->serverRequest
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
     * @covers ::host
     * @covers ::serverRequest
     * @covers ::setPath
     * @covers ::setHost
     * @covers ::setServerRequest
     * @covers ::<protected>
     */
    public function testGetSet()
    {
        $updates = array(
            'path' => 'pixie',
            'host' => 'fairy',
            'serverRequest' => $this->getServerRequest(),
        );
        
        foreach($updates as $name => $value) {
            $this->assertSame($this->$name, $this->fragment->$name());
            
            $method = 'set'.ucfirst($name);
            $this->fragment->$method($value);
            $this->assertSame($value, $this->fragment->$name());
        }
    }
    
    /**
     * @covers ::copy
     * @covers ::<protected>
     */
    public function testCopy()
    {
        $copy = $this->fragment->copy();
        $this->assertSame(false, $this->fragment === $copy);
        
        foreach(array('path', 'host', 'serverRequest') as $name) {
            $this->assertSame($this->$name, $this->fragment->$name());
        }
    }
    
    protected function getServerRequest()
    {
        return $this->quickMock('\Psr\Http\Message\ServerRequestInterface');
    }
}