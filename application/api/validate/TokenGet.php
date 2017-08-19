<?php
namespace app\api\validate;

class TokenGet extends BaseValidate{
    protected $rule=[
        'code'=>'require|isNotempty',//require不能校验空值
    ];

    protected $message=[
        'code'=>'要获取Token必须要有code参数',
    ];
}
