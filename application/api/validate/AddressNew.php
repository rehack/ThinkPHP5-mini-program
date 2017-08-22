<?php
namespace app\api\validate;

class AddressNew extends BaseValidate{
    protected $rule=[
        'name'=>'require|isNotEmpty',
        'mobile'=>'require|isMobile',
        'province'=>'require|isNotEmpty',
        'city'=>'require|isNotEmpty',
        'country'=>'require|isNotEmpty',
        'detail'=>'require|isNotEmpty',
    ];

    protected $message=[
        'count.positiveInteger'=>'count必须是正整数',
        // 'count.between'=>'count参数必须传',
    ];
}
