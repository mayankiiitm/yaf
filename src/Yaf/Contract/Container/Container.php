<?php
namespace Yaf\Contract\Container;
interface Container {
    public function bind($service,$class);
    public function unbind($service);
    public function rebind($service,$class);
    public function getBinding($service);
    public function make($service);
    public function instance($service,$instance);
}
