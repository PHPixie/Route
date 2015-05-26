<?php

namespace PHPixie\Tests\Router;

/**
 * @coversDefaultClass \PHPixie\Router\Path
 */
class PathTest extends \PHPixie\Test\Testcase
{
    protected $translator;
    protected $routePath;
    
    protected $path;
    
    public function setUp()
    {
        $this->translator = $this->quickMock('\PHPixie\Router\Translator');
        
        $this->path  = new \PHPixie\Router\Path(
            $this->translator,
            $this->routePath
        );
    }
}