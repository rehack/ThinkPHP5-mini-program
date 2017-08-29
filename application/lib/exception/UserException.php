<?php
namespace app\lib\exception;

class UserException extends BaseException{
    public $code=404;//http状态码
    public $msg='用户不存在';//错误具体信息
    public $errorCode=60000;//自定义错误码
}
