<?php

namespace PHPixie\Tests\Router\Translator;

/**
 * @coversDefaultClass \PHPixie\Router\Translator\Match
 */
class MatchTest extends \PHPixie\Test\Testcase
{
    protected $routePath = 'pixie.trixie.stella';
    protected $attributes = array(
        'a' => 1,
        'b' => 2
    );
    
    protected $match;
    
    public function setUp()
    {
        $this->match = new \PHPixie\Router\Translator\Match(
            $this->routePath,
            $this->attributes
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
        $this->assertSame($this->routePath, $this->match->routePath());
    }
    
    /**
     * @covers ::attributes
     * @covers ::<protected>
     */
    public function testAttributes()
    {
        $this->assertSame($this->attributes, $this->match->attributes());
    }
    
    /**
     * @covers ::popRoutePath
     * @covers ::<protected>
     */
    public function testPopRoutePath()
    {
        $exploded = explode('.', $this->routePath);
        $last = count($exploded) - 1;
        for($i = 0; $i <= $last; $i++) { 
            $this->assertSame(array_shift($exploded), $this->match->popRoutePath());
            $path = $i == $last ? null : implode('.', $exploded);
            $this->assertSame($path, $this->match->routePath());
        }
        
        $match = $this->match;
        
        $this->assertException(function() use($match) {
            $match->popRoutePath();
        }, '\PHPixie\Router\Exception');
    }
    
    /**
     * @covers ::prependRoutePath
     * @covers ::<protected>
     */
    public function testPrependRoutePath()
    {
        $this->match->prependRoutePath('fairy');
        $this->assertSame('fairy.'.$this->routePath, $this->match->routePath());
    }
    
    /**
     * @covers ::prependAttributes
     * @covers ::<protected>
     */
    public function testPrependAttributes()
    {
        $attributes = array(
            'b' => 3,
            'c' => 3
        );
        
        $merged = array_merge($attributes, $this->attributes);
        
        $this->match->prependAttributes($attributes);
        $this->assertSame($merged, $this->match->attributes());
    }
    
}