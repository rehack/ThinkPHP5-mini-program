<?php
namespace app\lib\exception;

class ForbiddenException extends BaseException{
    public $code=403;
    public $msg='权限不够，禁止访问';
    public $errorCode=10001;
}
