<?php
namespace app\api\model;
//
class User extends Base{
    // 关联UserAdress模型
    public function address(){
        return $this->hasOne('UserAddress','user_id','id');
    }


    public static function getByOpenID($openid){
        $user=self::where('openid',$openid)->find();
        return $user;
    }
}
