<?php
namespace app\api\validate;

class Count extends BaseValidate{
    protected $rule=[
        'count'=>'positiveInteger|between:1,15',
    ];

    protected $message=[
        'count.positiveInteger'=>'count必须是正整数',
        // 'count.between'=>'count参数必须传',
    ];
}
