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
    protected $msg=[];

    // 验证场景
    protected $scene=[];


}
