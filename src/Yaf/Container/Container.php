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
    protected $services=[];
    
    protected $instances=[];
    
    protected $buildStack=[];

    public function bind($service, $class=null, $singelton=false) 
    {
        if(is_null($class)){
            $class=$service;
        }
        $this->services[$service]= ['binding'=>$this->getClosure($class),'singelton'=>$singelton];
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
   
        $class=$this->getBinding($service) ?? $service;
        $singelton=$this->isSingelton($service);
        if ($singelton) {
            $instance = $this->getSingelton($service) ?? $this->resolve($class);
        }else{
            $instance= $this->resolve($class);	
        }
        $this->bindInstance($service,$instance);
        return $instance;
    }

    public function isSingelton($service)
    {
    	return $this->services[$service]['singelton'] ?? false;
    }
    protected function bindInstance($service,$instance)
    {
    	$this->instances[$service]=$instance;
    	return $this;
    }

    protected function getSingelton($service)
    {
    	return $this->instances[$service] ?? null;
    }
    public function getInstance($service)
    {
    	return $this->instance[$service] ?? null;
    }

    public function getBinding($service)        
    {
        return $this->services[$service]['binding'] ?? null;
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

    public function unbind($service) {
     	if (isset($this->services[$service])) {
     		unset($this->services[$service]);   	
     	}
     	return $this;
    }

    public function offsetExists($offset): bool {
        return isset($this->instances[$offset]);
    }

    public function offsetGet($offset) {
        return $this->make($offset);
    }

    public function offsetSet($offset, $value): void {
        $this->bindInstance($offset,$value);
    }

    public function offsetUnset($offset): void {
        unset($this->instances[$offset]);
    }

}