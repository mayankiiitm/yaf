<?php

namespace Yaf\Http;

use Yaf\Contract\Http\Request AS RequestContract;

class Request implements RequestContract
{
    
    public function get() 
    {
        return $_GET;
    }
}