<?php
namespace app\api\validate;
use think\Validate;

class TestValidate extends Validate{
    // 验证规则
    protected $rule=[
        'id'=>'require|positiveInteger',
        // 'name'=>'require|max:10',
    ];

    // 验证提示信息
    protected $msg=[];

    // 验证场景
    protected $scene=[];

    // 自定义验证规则 验证正整数
    protected function positiveInteger($value,$rule){
        if(is_numeric($value) && is_int($value+0) && ($value+0)>0){
            return true;
        }else{
            return 'id必须是正整数' . $rule;
        }
    }
}
