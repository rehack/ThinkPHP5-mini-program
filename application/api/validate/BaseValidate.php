<?php
namespace app\api\validate;
use think\Validate;
use think\Request;
use think\Exception;
use app\lib\exception\ParameterException;
class BaseValidate extends Validate{
    public function doCheck(){
        // 获取所有http参数
        $params=Request::instance()->param();

        // $this就是validate对象 因为类继承了Validate
        $result=$this->batch()->check($params);//批量验证结果

        if(!$result){
            // 如果参数校验不通过 进行异常处理
            $e=new ParameterException([
                'msg'=>$this->error,
            ]);
            throw $e;//抛出自定义异常

        }else{
            return true;
        }
    }


    // 自定义验证规则 验证正整数
    protected function positiveInteger($value,$rule){
        if(is_numeric($value) && is_int($value+0) && ($value+0)>0){
            return true;
        }else{
            return 'id必须是正整数' . $rule;
        }
    }



}
