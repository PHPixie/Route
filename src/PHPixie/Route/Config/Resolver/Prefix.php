<?php

namespace PHPixie\Route\Config\Resolver;

class Prefix extends Pattern
{
    protected $type = 'prefix';

    public function resolver($resolver)
    {
        return $this->set('resolver', $resolver->params());
    }
}