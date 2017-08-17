<?php
namespace app\lib\exception;

class ThemeException extends BaseException{
    public $code=404;//http状态码
    public $msg='请求的专题不存在,请检查id';//错误具体信息
    public $errorCode=30000;//自定义错误码
}
