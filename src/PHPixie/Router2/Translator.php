<?php

class Translator
{
    public function match($serverRequest)
    {
        $segment = $this->builder->segment($serverRequest);
        return $this->routes->match($serverRequest);
    }
    
    public function path($arguments)
    {
        $segment = $this->builder->segment($serverRequest);
        return $this->routes->match($serverRequest);
    }
}