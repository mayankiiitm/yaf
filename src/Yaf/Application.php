<?php
namespace Yaf;
use Yaf\Container\Container;
class Application extends Container
{

    private $basePath;

    public function __construct($basePath=null) 
    {
        $this->basePath=$basePath;
        $this->baseBinding();
    }
    protected function baseBinding()
    {
        $this->bind('app',function(){return $this;},true);
        $this->bind(Yaf\Application::class,function () {
            return $this->make('app');
        },true);
    }
}
