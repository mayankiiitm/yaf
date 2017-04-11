<?php

namespace Yaf\Http;

use Yaf\Contract\Http\Request AS RequestContract;
use Yaf\Support\Parameter\{Parameter,Files};
class Request implements RequestContract
{
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) 
    {
        $this->initialize($query, $request, $attributes, $cookies, $files, $server, $content);
    }
    
    public function initialize(array $query = [], array $request = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->request = new Parameter($request);
        $this->query = new Parameter($query);
        $this->cookies = new Parameter($cookies);
        $this->files = new Files($files);
        $this->server = new Parameter($server);
        $this->headers = new Parameter(getallheaders());
        $this->content= $content;
    }
    
    public static function capture(): RequestContract
    {
        return new Request($_GET,$_POST,$_COOKIE,$_FILES,$_SERVER);
    }
}