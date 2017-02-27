<?php
namespace Yaf\Contract\Middleware;
use Closure;
interface Middleware
{
    public function handle($request, Closure $next);
}
