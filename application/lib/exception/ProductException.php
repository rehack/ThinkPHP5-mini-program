<?php
namespace app\lib\exception;

class ThemeException extends BaseException{
    public $code=404;//http状态码
    public $msg='指定的商品不存在,请检查参数';//错误具体信息
    public $errorCode=20000;//自定义错误码
}
