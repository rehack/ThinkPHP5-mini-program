<?php
namespace app\api\validate;
use think\Validate;

class IdPositiveInt extends BaseValidate{
    // 验证规则
    protected $rule=[
        'id'=>'require|positiveInteger',
        // 'name'=>'require|max:10',
    ];

    // 验证提示信息
    protected $message=[
        'id.require'=>'id参数必须传',
        'id.positiveInteger'=>'id必须是正整数'
    ];

    // 验证场景
    protected $scene=[];


}
