<?php

namespace PHPixie\Tests\Route\Resolvers\Registry;

/**
 * @coversDefaultClass \PHPixie\Route\Resolvers\Registry\Config
 */
class ConfigTest extends \PHPixie\Test\Testcase
{
    protected $resolverBuilder;
    protected $configData;
    
    protected $config;
    
    public function setUp()
    {
        $this->resolverBuilder = $this->quickMock('\PHPixie\Route\Resolvers\Builder');
        $this->configData   = $this->getData();
        
        $this->config = new \PHPixie\Route\Resolvers\Registry\Config(
            $this->resolverBuilder,
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
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $locatorConfig = $this->getData();
        $this->method($this->configData, 'slice', $locatorConfig, array('pixie'), 0);
        
        $locator = $this->quickMock('\PHPixie\Route\Resolvers\Locator');
        $this->method($this->resolverBuilder, 'buildFromConfig', $locator, array($locatorConfig), 0);
        
        for($i=0; $i<2; $i++) {
            $this->assertSame($locator, $this->config->get('pixie'));
        }
    }
    
    protected function getData()
    {
        return $this->quickMock('\PHPixie\Slice\Data');
    }
}