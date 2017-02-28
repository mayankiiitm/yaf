<?php
namespace Yaf\Container;

use ArrayAccess;
use Closure;
use Yaf\Contract\Container\Container as ContainerContract;
use ReflectionClass;
use LogicException;
use ReflectionParameter;
use ReflectionException;

class Container implements ContainerContract, ArrayAccess
{
    public $services=[];
    
    protected $instances=[];
    
    protected $buildStack=[];

    public function bind($service, $class=null) 
    {
        if(is_null($class)){
            $class=$service;
        }
        $this->services[$service]= $this->getClosure($class);
    }
    
    protected function getClosure($class)
    {
        if ($class instanceof Closure) {
            return $class;
        }
        
        return function() use ($class){
          return $this->resolve($class);  
        };
    }
    
    public function make($service)
    {
        $class=$this->services[$service] ?? $service;
        return $this->resolve($class);
    }
    
    public function getBinding($service)        
    {
        return $this->services[$service] ?? null;
    }
    
    protected function resolve($class)
    {
        if ($class instanceof Closure) {
            return $class();
        }
        try {
            $reflection = new ReflectionClass($class);   
        } catch (ReflectionException $e) {
            return $this->notInstantiable($class);
        }
        if(!$reflection->isInstantiable()){
            return $this->notInstantiable($class);
        }
        $this->buildStack[]=$class;
        $constructor=$reflection->getConstructor();
        if(is_null($constructor)){
            array_pop($this->buildStack);
            return new $class;
        }
        
        $parameters=$constructor->getParameters();
        $dependencies=[];
        
        foreach ($parameters as $parameter) {
            $dependencies[]=$this->resolveDependencies($parameter);
        }
        return $reflection->newInstanceArgs($dependencies);
    }
    
    protected function resolveDependencies(ReflectionParameter $parameter) 
    {
        if($parameter->isOptional()){
            return $parameter->getDefaultValue();
        }
        return $this->make($parameter->getClass()->name);  
    }
    protected function notInstantiable($class)
    {
        if (! empty($this->buildStack)) {
            $previous = implode(', ', $this->buildStack);

            $message = "Target [$class] is not instantiable while building [$previous].";
        } else {
            $message = "Target [$class] is not instantiable.";
        }
        throw new LogicException($message);
    }
    
    public function instance($service, $instance) {
        
    }

    public function rebind($service, $class) {
        
    }

    public function unbind($service) {
        
    }

    public function offsetExists($offset): bool {
        
    }

    public function offsetGet($offset) {
        
    }

    public function offsetSet($offset, $value): void {
        
    }

    public function offsetUnset($offset): void {
        
    }

}