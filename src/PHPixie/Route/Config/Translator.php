<?php

namespace PHPixie\Route\Config;

class Translator
{
    private $params = array(
        'basePath' => '/',
        'baseHost' => ''
    );

    public function basePath($pathPrefix)
    {
        $this->params['basePath'] = $pathPrefix;
        return $this;
    }

    public function baseHost($hostPrefix)
    {
        $this->params['baseHost'] = $hostPrefix;
        return $this;
    }

    public function params()
    {
        return $this->params;
    }
}