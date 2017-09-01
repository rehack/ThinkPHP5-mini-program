<?php
namespace app\api\controller\v1;

// 支付接口
class Pay extends Base{
    public function getPreOrder(){
        protected $beforeActionList=['checkExclusiveScope' => ['only' => 'getPreOrder']];
    }


}
