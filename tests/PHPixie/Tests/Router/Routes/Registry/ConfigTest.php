<?php

namespace PHPixie\Tests\Router\Routes\Registry;

/**
 * @coversDefaultClass \PHPixie\Router\Routes\Registry\Config
 */
class ConfigTest extends \PHPixie\Test\Testcase
{
    protected $routeBuilder;
    protected $configData;
    
    protected $config;
    
    public function setUp()
    {
        $this->routeBuilder = $this->quickMock('\PHPixie\Router\Routes\Builder');
        $this->configData   = $this->getData();
        
        $this->config = new \PHPixie\Router\Routes\Registry\Config(
            $this->routeBuilder,
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
        
        $locator = $this->quickMock('\PHPixie\Router\Routes\Locator');
        $this->method($this->routeBuilder, 'buildFromConfig', $locator, array($locatorConfig), 0);
        
        for($i=0; $i<2; $i++) {
            $this->assertSame($locator, $this->config->get('pixie'));
        }
    }
    
    protected function getData()
    {
        return $this->quickMock('\PHPixie\Slice\Data');
    }
}