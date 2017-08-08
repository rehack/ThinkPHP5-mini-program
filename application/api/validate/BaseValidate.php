<?php
namespace app\api\validate;
use think\Validate;
use think\Request;
use think\Exception;

class BaseValidate extends Validate{
    public function doCheck(){
        // 获取所有http参数
        $params=Request::instance()->param();

        // $this就是validate对象 因为类继承了Validate
        $result=$this->check($params);//验证结果

        if(!$result){
            $error=$this->error;
            throw new Exception($error);//抛出异常

        }else{
            return true;
        }
    }
}
