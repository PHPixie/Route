<?php

namespace PHPixie\Route\Translator;

class Fragment
{
    protected $path;
    protected $host;
    /**
     *
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $serverRequest;
    
    /**
     * 
     * @param string $path
     * @param string $host
     * @param \Psr\Http\Message\ServerRequestInterface $serverRequest
     */
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
    
    /**
     * 
     * @param ServerRequestInterface $serverRequest
     */
    public function setServerRequest($serverRequest)
    {
        $this->serverRequest = $serverRequest;
    }
    
    public function copy()
    {
        return clone $this;
    }
}