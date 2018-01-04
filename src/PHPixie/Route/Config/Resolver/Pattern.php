<?php

namespace PHPixie\Route\Config\Resolver;

class Pattern extends \PHPixie\Route\Config\Resolver
{
    protected $type = 'pattern';

    public function host($host)
    {
        return $this->set('host', $host);
    }

    public function path($path)
    {
        return $this->set('path', $path);
    }

    public function defaults($defaults)
    {
        return $this->set('defaults', $defaults);
    }

    public function attributePatterns($attributePatterns)
    {
        return $this->set('attributePatterns', $attributePatterns);
    }

    public function methods($methods)
    {
        return $this->set('methods', $methods);
    }
}