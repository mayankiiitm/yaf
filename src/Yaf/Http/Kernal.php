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
    public function __construct(Application $app){
        $this->app=$app;
    }
    public function handle(RequestContract $request): ResponseContract {
        $pipeline= $this->app->make(Pipeline::class);
        return new Response($request->get());
    }
}
