<?php
namespace app\api\validate;
use think\Validate;

class TestValidate extends Validate{
    // 验证规则
    protected $rule=[
        'id'=>'require|number',
        // 'name'=>'require|max:10',
    ];

    // 验证提示信息
    protected $msg=[];

    // 验证场景
    protected $scene=[];
}