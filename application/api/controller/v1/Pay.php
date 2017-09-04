<?php
namespace app\api\controller\v1;
use app\api\validate\IdPositiveInt;
use app\api\service\Pay as PayService;
// 支付接口
class Pay extends Base{
    // 前置操作-验证权限
    protected $beforeActionList=['checkExclusiveScope' => ['only' => 'getPreOrder']];

    // 生成预定单
    public function getPreOrder($id=''){
        (new IdPositiveInt())->doCheck();
        $pay=new PayService($id);

        $pay->pay();
    }


}
