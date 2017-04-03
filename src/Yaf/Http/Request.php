<?php

namespace Yaf\Http;

use Yaf\Contract\Http\Request AS RequestContract;

class Request implements RequestContract
{
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) 
    {
        $this->initialize($query, $request, $attributes, $cookies, $files, $server, $content);
    }
    
    public function initialize(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->request = new ParameterBag($request);
        $this->query = new ParameterBag($query);
        $this->attributes = new ParameterBag($attributes);
        $this->cookies = new ParameterBag($cookies);
        $this->files=new Files($files);
    }

    public function get() 
    {
        
    }

}