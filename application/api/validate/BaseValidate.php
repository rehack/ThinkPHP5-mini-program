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

    // 只获取验证器里设置的参数
    public function getDataByRule($arrays){
        if(array_key_exists('user_id',$arrays) | array_key_exists('uid',$arrays)){
            // 不允许包含user_id或者uid 防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg'=>'参数中包含有非法的参数名user_id或id'
            ]);
        }
        $newArray=[];
        foreach ($this->rule as $key => $value) {
            $newArray[$key]=$arrays[$key];
        }
        return $newArray;
    }


    // 自定义验证规则 验证正整数
    protected function positiveInteger($value){
        if(is_numeric($value) && is_int($value+0) && ($value+0)>0){
            return true;
        }else{
            // return 'id必须是正整数';
            return false;
        }
    }

    // 自定义验证规则 验证不能为空值
    protected function isNotEmpty($value,$rule='',$data='',$field=''){
        if(empty($value)){
            return false;
        }else{
            return true;
        }
    }

    // 自定义验证规则 验证手机号
    protected function isMobile($value){
        $rule='^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result=preg_match($rule, $value);
        if($result){
            return true;
        }else{
            return false;
        }

        // return $result;
    }


}
