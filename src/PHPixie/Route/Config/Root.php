<?php

namespace PHPixie\Route\Config;

class Root
{
    private $params = array();

    public function translator($translator)
    {
        $this->params['translator'] = $translator->params();
        return $this;
    }

    public function resolver($resolver)
    {
        $this->params['resolver'] = $resolver->params();
        return $this;
    }

    public function params()
    {
        return $this->params;
    }
}