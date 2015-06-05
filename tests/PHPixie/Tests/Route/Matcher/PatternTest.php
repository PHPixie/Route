<?php

namespace PHPixie\Tests\Route\Matcher;

/**
 * @coversDefaultClass \PHPixie\Route\Matcher\Pattern
 */
class PatternTest extends \PHPixie\Test\Testcase
{
    protected $patternString = '(<pixie>(/<trixie>))';
    protected $defaultAttributePattern = '[^/]+';
    protected $attributePatterns = array(
        'pixie' => '[0-9]+'
    );
    
    protected $pattern;
    
    public function setUp()
    {
        $this->pattern = new \PHPixie\Route\Matcher\Pattern(
            $this->patternString,
            $this->defaultAttributePattern,
            $this->attributePatterns
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
     * @covers ::pattern
     * @covers ::regex
     * @covers ::parameterNames
     * @covers ::<protected>
     */
    public function testMethods()
    {
        $this->assertMethods($this->pattern, array(
            'pattern'        => $this->patternString,
            'regex'          => '(?:([0-9]+)(?:/([^/]+))?)?',
            'parameterNames' => array('pixie', 'trixie')
        ));
    }
    
    /**
     * @covers ::generate
     * @covers ::<protected>
     */
    public function testGenerate()
    {
        $this->assertSame('stella/blum', $this->pattern->generate(array(
            'pixie'  => 'stella',
            'trixie' => 'blum'
        )));
    }
    
    protected function assertMethods($pattern, $methodMap)
    {
        foreach($methodMap as $method => $value) {
            $this->assertSame($value, $pattern->$method());
        }
    }
}