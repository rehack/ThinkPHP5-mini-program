<?php
namespace app\api\service;
use think\Request;
use think\Cache;
use app\lib\exception\TokenException;
use think\Exception;
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

    public static function getCurrentTokenVar($key){
        // 获取用户携带的token 在http请求的header里面
        $token=Request::instance()->header('token');

        // 从缓存中获取token对应的值
        $vars = Cache::get($token);

        if(!$vars){
            throw new TokenException();
        }else{
            if(!is_array($vars)){
                $vars=json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }

    }
    /**
     * [getCurrentUID 获取当前用户的uid]
     * @return [type] [description]
     */
    public static function getCurrentUID(){
        // 通过用户携带的令牌token来得到uid
        $uid=self::getCurrentTokenVar('uid');
        return $uid;
    }
}
