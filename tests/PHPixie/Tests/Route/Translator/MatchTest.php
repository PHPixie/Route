<?php

namespace PHPixie\Tests\Route\Translator;

/**
 * @coversDefaultClass \PHPixie\Route\Translator\Match
 */
class MatchTest extends \PHPixie\Test\Testcase
{
    protected $attributes = array(
        'a' => 1,
        'b' => 2
    );
    protected $resolverPath = 'pixie.trixie.stella';
    
    protected $match;
    
    public function setUp()
    {
        $this->match = new \PHPixie\Route\Translator\Match(
            $this->attributes,
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
        $this->assertSame($this->resolverPath, $this->match->resolverPath());
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
     * @covers ::popResolverPath
     * @covers ::<protected>
     */
    public function testPopResolverPath()
    {
        $exploded = explode('.', $this->resolverPath);
        $last = count($exploded) - 1;
        for($i = 0; $i <= $last; $i++) { 
            $this->assertSame(array_shift($exploded), $this->match->popResolverPath());
            $path = $i == $last ? null : implode('.', $exploded);
            $this->assertSame($path, $this->match->resolverPath());
        }
        
        $match = $this->match;
        
        $this->assertException(function() use($match) {
            $match->popResolverPath();
        }, '\PHPixie\Route\Exception');
    }
    
    /**
     * @covers ::prependResolverPath
     * @covers ::<protected>
     */
    public function testPrependResolverPath()
    {
        $this->match->prependResolverPath('fairy');
        $this->assertSame('fairy.'.$this->resolverPath, $this->match->resolverPath());
        
        $this->match = new \PHPixie\Route\Translator\Match();
        $this->match->prependResolverPath('fairy');
        $this->assertSame('fairy', $this->match->resolverPath());
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