<?php
namespace Yaf\Http;
use Yaf\Contract\Http\Kernal AS KernalContract;
use Yaf\Contract\Http\Response as ResponseContract;
use Yaf\Contract\Http\Request as RequestContract;
use Yaf\Http\{Request,Response};
use Yaf\Pipeline\Pipeline;
use Yaf\Application;
class Kernal implements KernalContract
{
    protected $middleware=[];
    public function __construct(Application $app)
    {
        $this->app=$app;
    }
    protected function setMiddleware($middlewares)
    {
        $this->middleware = is_array($middlewares)?$middlewares:func_get_args();
    }
    public function handle(RequestContract $request): ResponseContract
    {
        $pipeline= $this->app->make(Pipeline::class);
        return $pipeline->send($request)
                        ->through($this->middleware)
                        ->then($this->handleClosure($request));    
    }
    
    protected function handleClosure($request)
    {
        return function () use ($request){
           return  new Response($request);
        };
    }
}
