<?php
namespace Yaf\Http;
use Yaf\Contract\Http\Kernal AS KernalContract;
use Yaf\Contract\Http\{Request,Response};
use Yaf\Pipeline\Pipeline;
class Kernal implements KernalContract
{
    public function __construct(Application $app){
        $this->app=$app;
    }
    public function handle(Request $request): Response {
        
    }
}
