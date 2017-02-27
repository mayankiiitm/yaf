<?php
namespace Yaf\Contract\Http;
interface Kernal
{
    public function handle(Request $request) : Response;
}