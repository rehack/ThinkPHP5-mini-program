<?php
namespace app\lib\exception;

class BannerMissException extends BaseException{
    public $code=404;//http状态码
    public $msg='请求的banner不存在';//错误具体信息
    public $errorCode=40000;//自定义错误码
}