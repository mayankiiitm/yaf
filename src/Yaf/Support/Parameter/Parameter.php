<?php
namespace Yaf\Support\Parameter;
class Parameter
{
    protected $parameters;
    public function __construct($parameters=[])
    {
        $this->parameters=$parameters;
    }
    
    public function all()
    {
        return $this->parameters;
    }
    
    public function get($key,$default=null)
    {
        return array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $default;
    }
    public function set($key,$value)
    {
        $this->parameters[$key]=$value;
        return $this;
    }
    public function has($key)
    {
        return array_key_exists($key, $this->parameters);
    }
    public function remove($key)
    {
        unset($this->parameters[$key]);
    }
}
