<?php

namespace PHPixie\Routing\Routes;

interface Route
{
    public function match($segment);
    public function generate($match, $withHost = false);
}