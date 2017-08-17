<?php
namespace app\api\validate;

class IdCollection extends BaseValidate{
    protected $rule=[
        'ids'=>'require|checkIds',
    ];

    protected $message=[
        'ids.require'=>'ids参数必须传',
        'ids.checkIds'=>'ids必须是以逗号分隔的正整数'
    ];

    protected function checkIds($value){
        $values=explode(',', $value);
        if(empty($values)){
            return false;
        }
        foreach ($values as $id) {
            if(!$this->positiveInteger($id)){
                return false;
            }
        }
        return true;
    }
}
