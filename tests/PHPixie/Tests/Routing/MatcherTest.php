<?php

namespace PHPixie\Tests\Routing;

/**
 * @coversDefaultClass \PHPixie\Routing\Matcher
 */
class MatcherTest extends \PHPixie\Test\Testcase
{
    protected $matcher;
    
    public function setUp()
    {
        $this->matcher = new \PHPixie\Routing\Matcher();
    }
    
    /**
     * @covers ::match
     * @covers ::<protected>
     */
    public function testMatch()
    {
        $sets = array(
            array('trixie', array(), 'pixie', null),
            array('([a-z]+)/([a-z]+)', array('a', 'b'), 'pixie/Trixie', array(
                'a' => 'pixie',
                'b' => 'Trixie',
            ))
        );
        
        foreach($sets as $set) {
            $pattern = $this->pattern($set[0], $set[1]);
            $this->assertSame($set[3], $this->matcher->match($pattern, $set[2]));
        }
    }
    
    /**
     * @covers ::matchPrefix
     * @covers ::<protected>
     */
    public function testMatchPrefix()
    {
        $sets = array(
            array('trixie', array(), 'pixie', array(null, 'pixie')),
            array('([a-z]+)/([a-z]+)/', array('a', 'b'), 'pixie/Trixie/stella', array(
                array(
                    'a' => 'pixie',
                    'b' => 'Trixie',
                ), 'stella'
            ))
        );
        
        foreach($sets as $set) {
            $pattern = $this->pattern($set[0], $set[1]);
            $this->assertSame($set[3], $this->matcher->matchPrefix($pattern, $set[2]));
        }
    }
    
    protected function pattern($regex, $parameterNames = array())
    {
        $pattern = $this->quickMock('\PHPixie\Routing\Matcher\Pattern');
        
        $this->method($pattern, 'regex', $regex, array());
        $this->method($pattern, 'parameterNames', $parameterNames, array());
        
        return $pattern;
    }
}