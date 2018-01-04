<?php

namespace PHPixie\Route\Config\Resolver;

class Group extends \PHPixie\Route\Config\Resolver
{
    protected $type = 'group';

    protected $params = array(
        'resolvers' => array()
    );

    public function defaults($defaults)
    {
        return $this->set('defaults', $defaults);
    }

    public function add($name, $resolver)
    {
        $this->params['resolvers'][$name] = $resolver->params();
        return $this;
    }
}