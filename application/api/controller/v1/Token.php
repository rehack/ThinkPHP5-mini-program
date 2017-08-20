<?php
namespace app\api\controller\v1;
use app\api\validate\TokenGet;
use app\api\service\UserToken;
//
class Token{
    public function getToken($code=''){
        (new TokenGet())->doCheck();
        $ut=new UserToken($code);
        $token=$ut->get();
        return json([
            'token'=>$token
        ]);

        /*return [
            'token'=>$token
        ];*/
    }

}
