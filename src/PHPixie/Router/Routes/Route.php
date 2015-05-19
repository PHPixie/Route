<?php

namespace PHPixie\Router\Routes;

interface Route
{
    public function match($segment);
    public function generate($match, $withHost = false);
}