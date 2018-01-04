<?php

namespace PHPixie\Route\Config\Resolver;

class Mount extends \PHPixie\Route\Config\Resolver
{
    protected $type = 'mount';

    public function name($name)
    {
        return $this->set('name', $name);
    }
}