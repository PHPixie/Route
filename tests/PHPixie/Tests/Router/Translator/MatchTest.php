<?php

namespace PHPixie\Tests\Router\Translator;

/**
 * @coversDefaultClass \PHPixie\Router\Translator\Match
 */
class MatchTest extends \PHPixie\Test\Testcase
{
    protected $path = 'pixie.trixie.stella';
    protected $attributes = array(
        'a' => 1,
        'b' => 2
    );
    
    protected $match;
    
    public function setUp()
    {
        $this->match = new \PHPixie\Router\Translator\Match(
            $this->path,
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
     * @covers ::path
     * @covers ::<protected>
     */
    public function testPath()
    {
        $this->assertSame($this->path, $this->match->path());
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
     * @covers ::popPath
     * @covers ::<protected>
     */
    public function testPopPath()
    {
        $exploded = explode('.', $this->path);
        $last = count($exploded) - 1;
        for($i = 0; $i <= $last; $i++) { 
            $this->assertSame(array_shift($exploded), $this->match->popPath());
            $path = $i == $last ? null : implode('.', $exploded);
            $this->assertSame($path, $this->match->path());
        }
        
        $match = $this->match;
        $this->assertException(function() use($match) {
            $match->popPath();
        }, '\PHPixie\Router\Exception');
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