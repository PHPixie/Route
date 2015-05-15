<?php

class Iterator implements \Iterator
{
    protected $routes;
    protected $names;
    
    protected $count;
    protected $currentKey;
    protected $valid;
    
    protected $current;
    protected $currentName;

    public function __construct($routes)
    {
        $this->routes = $routes;
        $this->names  = $routes->names();
        $this->count  = count($this->names);
        $this->rewind();
    }
    
    public function current()
    {
        return $this->current;
    }
    
    public function key()
    {
        return $this->currentName;
    }
    
    public function next()
    {
        if($this->valid) {
            $this->currentKey++;
            $this->checkValid();
        }
    }
    
    public function rewind()
    {
        $this->currentKey = 0;
        $this->checkValid();
    }
    
    public function valid()
    {
        return $this->valid;
    }
    
    protected function checkValid()
    {
        $this->valid = $this->currentKey < $this->count;
        
        if($this->valid) {
            $this->currentName = $this->names[$this->currentKey];
            $this->current     = $this->routes->get($this->currentName);
            
        }else{
            $this->currentName = null;
            $this->current     = null;
        }
    }
}