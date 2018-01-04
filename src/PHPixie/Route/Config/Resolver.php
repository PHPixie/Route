<?php

namespace PHPixie\Route\Config;

abstract class Resolver
{
    protected $type;
    protected $params = array();

    public function params()
    {
        $this->params['type'] = $this->type;
        return $this->params;
    }

    protected function set($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }
}