<?php
namespace Yaf\Contract\Container;
interface Container {
    public function bind($service,$class);
    public function make($service);
}
