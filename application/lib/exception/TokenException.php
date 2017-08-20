<?php
namespace app\lib\exception;

class TokenException extends BaseException{
    public $code=401;//http状态码
    public $msg='Token已过期或者无效Token';//错误具体信息
    public $errorCode=10001;//自定义错误码
}
