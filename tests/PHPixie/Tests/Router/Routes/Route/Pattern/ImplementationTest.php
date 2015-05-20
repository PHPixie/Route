<?php

namespace PHPixie\Tests\Router\Routes\Route;

/**
 * @coversDefaultClass \PHPixie\Router\Routes\Route\Pattern\Implementation
 */
class ImplementationTest extends \PHPixie\Tests\Router\Routes\Route\PatternTest
{
    protected function route()
    {
        return new \PHPixie\Router\Routes\Route\Pattern\Implementation(
            $this->builder,
            $this->configData
        );
    }
}