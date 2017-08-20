<?php
namespace app\api\service;

/**
*Token基类
*/
class Token
{
    // 生成令牌
    public static function generateToken()
    {
        // 32个字符组成一组随机字符串
        $randChars = getRandChar(32);//公共函数

        // 用三组字符串进行md5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];//第二组字符串 当前时间戳
        $salt=config('secure.token_salt');//第三组字符串 salt盐（即自定义的一组随机字符串）

        return md5($randChars.$timestamp.$salt);

    }
}
