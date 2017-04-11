<?php
namespace Yaf\Middleware;
use Yaf\Contract\Middleware\Middleware;
use Yaf\Contract\Http\{Request,Response};
use Closure;
class M2 implements Middleware
{
    public function handle($request, Closure $next): Response {
        return $next($request);
    }

}