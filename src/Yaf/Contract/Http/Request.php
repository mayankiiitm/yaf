<?php
namespace Yaf\Contract\Http;
interface Request
{
    public static function capture(): Request;
}