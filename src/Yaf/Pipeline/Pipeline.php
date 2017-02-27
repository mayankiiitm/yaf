<?php
namespace Yaf\Pipeline;
use Yaf\Contract\Pipeline\Pipeline AS PipelineContract;
use Closure;
class Pipeline implements PipelineContract
{
    protected $container;
    
    protected $param;
    
    protected $method='handle';
    
    protected $middlewares;
    
    public function __construct(Container $container=null)
    {
        $this->container=$container;
    }
    
    public function send($param)
    {
        $this->param=$param;
        return $this;
    }

    public function through($middlewares)
    {
        $this->middlewares= is_array($middlewares)?$middlewares:func_get_args();
        return $this;
    }
    
    public function via(String $method)
    {
        $this->method=$method;
        return $this;
    }
    
    public function then(Closure $destination)
    {
        $pipeline=array_reduce(
            $this->middlewares,
            $this->callBack(), 
            $this->destination($destination)
        );
        return $pipeline($this->param);
    }
    
    protected function callBack() 
    {
        return function ($next,$item){
            return function ($param) use ($next, $item) {
                $middleware = new $item;
            return $middleware->{$this->method}($param, $next);
            };
        };
    }
    
    protected function destination(Closure $destination)
    {
        return function ($param) use ($destination){
            return $destination($param);
        };
    }
}