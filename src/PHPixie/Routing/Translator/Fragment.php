<?php

namespace PHPixie\Routing\Translator;

class Fragment
{
    protected $path;
    protected $host;
    protected $serverRequest;
    
    public function __construct($path = null, $host = null, $serverRequest = null)
    {
        $this->path          = $path;
        $this->host          = $host;
        $this->serverRequest = $serverRequest;
    }
    
    public function path()
    {
        return $this->path;
    }
    
    public function host()
    {
        return $this->host;
    }
    
    public function serverRequest()
    {
        return $this->serverRequest;
    }
    
    public function setPath($path)
    {
        $this->path = $path;
    }
    
    public function setHost($host)
    {
        $this->host = $host;
    }
    
    public function setServerRequest($serverRequest)
    {
        $this->serverRequest = $serverRequest;
    }
    
    public function copy()
    {
        return clone $this;
    }
}