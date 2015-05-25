<?php

namespace PHPixie\Tests\Router\Routes\Route;

/**
 * @coversDefaultClass \PHPixie\Router\Routes\Route\Pattern\Prefix\Implementation
 */
class ImplementationTest extends \PHPixie\Tests\Router\Routes\Route\Pattern\PrefixTest
{
    protected function route()
    {
        return new \PHPixie\Router\Routes\Route\Pattern\Prefix\Implementation(
            $this->builder,
            $this->configData
        );
    }
    
    protected function routeMock($methods = array())
    {
        return $this->getMock(
            '\PHPixie\Router\Routes\Route\Pattern\Prefix\Implementation',
            $methods,
            array(
                $this->builder,
                $this->configData
            )
        );
    }
}