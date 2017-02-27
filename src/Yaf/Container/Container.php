<?php

namespace Yaf\Container;
use Yaf\Contract\Container\Container as ContainerContract;
use Closure;
use ArrayAccess;
use ReflectionClass;

class Container implements ContainerContract, ArrayAccess
{
    protected $services;
    
    protected $instances;
    
    public function bind ($service, $class=null)
    {
        if(is_null($class)){
            $class=$service;
        }
        $this->services[$service]=$this->getClosure($class);
        return $this;
    }
    
    protected function getClosure($class)
    {
        if($class instanceof Closure){
            return $class();
        }
        
        return function () use ($class){
            return $this->resolve($class);
        };
    }
    public function make($service)
    {
        if(isset($this->services[$service])){
            $class= $this->services[$service];
        }else{
            $class=$service;
            $this->bind($service);
        }
        return $this->resolve($class);
    }
    public function resolve($class)
    {
        if ($class instanceof Closure) {
            return $class();
        }
        $reflector=new ReflectionClass($class);
        if(!$reflector->isInstantiable()){
            throw new Exception("class $class in not instantiable");
        }
        
        $constructor = $reflector->getConstructor();
        
        if(is_null($constructor)){
            return new $class;
        }
        
        $parameters=$constructor->getParameters();
        $dependencies=[];
        
        foreach ($parameters as $parameter) {
            if($parameter->isOptional()){
                $dependencies[]=$parameter->getDefaultValue();
            }else{
                $dependencies[]=$this->resolve($parameter->getClass()->name);
                
            }
        }
        return $reflector->newInstanceArgs($dependencies);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->services[$offset]);
    }

    public function offsetGet($offset)
    {
        if($this->offsetExists($offset)){
            return $this->make($offset);
        }
        return null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->bind($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        if($this->offsetExists($offset)){
            unset($this->services[$offset]);
        }
    }

}
