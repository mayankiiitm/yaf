<?php
namespace Yaf\Http;
use Yaf\Contract\Http\Response as ResponseContract;
class Response implements ResponseContract
{
    private $data;
    public function __construct($data) 
    {
        $this->data=$data;
    }

    public function send() 
    {
        print_r($this->data);
    }

}
