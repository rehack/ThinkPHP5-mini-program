<?php
namespace app\api\validate;

class IdCollection extends BaseValidate{
    protected $rule=[
        'ids'=>'require|checkIds',
    ];

    protected $msg=[
        'ids'=>'ids必须是以逗号分隔的正整数'
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

            return true;
        }
    }
}