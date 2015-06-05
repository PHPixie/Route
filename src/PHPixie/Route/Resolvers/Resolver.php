<?php

namespace PHPixie\Route\Resolvers;

interface Resolver
{
    public function match($segment);
    public function generate($match, $withHost = false);
}