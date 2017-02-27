<?php

namespace Yaf\Contract\Pipeline;

use Closure;

interface Pipeline 
{
    public function send($request);
    public function through($middleware);
    public function then(Closure $destination);
}
