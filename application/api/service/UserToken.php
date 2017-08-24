<?php
namespace app\api\service;
use app\lib\exception\WechatException;
use think\Exception;
use app\api\model\User as UserModel;
use app\lib\exception\TokenException;
use app\lib\enum\ScopeEnum;

// service是model的分层 用来处理较为复杂的业务逻辑
class UserToken extends Token{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code){
        $this->code=$code;
        $this->wxAppID=config('wechat.app_id');
        $this->wxAppSecret=config('wechat.app_secret');
        $this->wxLoginUrl=sprintf(config('wechat.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get(){
        $result=curl_get($this->wxLoginUrl);
        // $result=curl_get('https://api.weixin.qq.com/sns/jscode2session?appid=wxb87146459a7b65ac&secret=f9c25252b1ab037df78490192e23cf0c&js_code=081gLPdY0t2UgZ1bMKdY0JmPdY0gLPdC&grant_type=authorization_code');
        $wxResult=json_decode($result,true);//将json格式字符串转换成数组格式
        if(empty($wxResult)){
            throw new Exception('获取session_key和openID时异常，微信内部错误');//此异常不会返回到客户端去
        }else{
            $loginFail=array_key_exists('errcode', $wxResult);
            if($loginFail){
                //如果返回的数据里存在errcode说明调用微信接口失败
                $this->processLoginError($wxResult);
            }else{
                return $this->grantToken($wxResult);
            }
        }
    }

    // 处理微信接口调用异常 单独写一个方法方便以后扩展 如记录日志、发送邮件
    private function processLoginError($wxResult){
        throw new WechatException([
            'msg'=>$wxResult['errmsg'],
            'errorCode'=>$wxResult['errcode']
        ]);
    }

    // 授权令牌
    private function grantToken($wxResult){
        // 1.拿到openid
        // 2.到数据库里看一下这个openid是不是已经存在（如果存在则不处理，如果不存在就新增一条user记录）
        // 3.生成令牌，准备缓存数据，写入缓存（key:令牌   value：wxResult,uid,scope）
        // 4.把令牌返回到客户端

        // return $wxResult;
        $openid=$wxResult['openid'];
        $user=UserModel::getByOpenID($openid);
        if($user){
            $uid=$user->id;
        }else{
            $uid=$this->addUser($openid);
        }

        $cachedValue=$this->prepareCachedValue($wxResult,$uid);

        $token=$this->saveToCache($cachedValue);
        return $token;
    }

    // 新增用户
    private function addUser($openid){
        $user=UserModel::create([
            'openid'=>$openid,
        ]);
        return $user->id;//返回插入成功的记录的id
    }

    // 准备缓存
    private function prepareCachedValue($wxResult,$uid){
        $cachedValue=$wxResult;
        $cachedValue['uid']=$uid;


        $cachedValue['scope']=ScopeEnum::User;//数字越大访问的权限越大
        // $cachedValue['scope']=12;//数字越大访问的权限越大

        return $cachedValue;
    }

    // 写入缓存
    private function saveToCache($cachedValue){
        $key=self::generateToken();//调用基类方法生成令牌
        $value=json_encode($cachedValue);//将数据转换成字符串
        $expire_in=config('setting.token_expire_in');//缓存过期时间

        $request=cache($key,$value,$expire_in);//写入缓存
        if(!$request){
            throw new TokenException([
                'msg'=>'服务器缓存异常',
                'errorCode'=>10005
            ]);
        }

        // 将令牌返回到客户端
        return $key;
    }
}
