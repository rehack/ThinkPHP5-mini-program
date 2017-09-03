<?php
namespace app\api\service;

use think\Exception;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;
use app\api\service\Token as TokenService;
use app\lib\exception\TokenException;
use app\lib\enum\OrderStatusEnum;


class Pay{
    private $orderID;
    private $orderNoO

    function __construct($orderID){
        if(!$orderID){
            throw new Exception('订单号不允许为NULL');
        }

        $this->orderID=$orderID;
    }

    public function pay(){
        $this->checkOrderValid();
        $orderService=new OrderService;
        $status=$orderService->checkOrderStock($this->orderID);//库存量检测
        if(!$status['pass']){
            return $status;// 库存量检测未通过
        }
    }

    // 通过订单id号检测该订单是否存在
    private function checkOrderValid(){
        $order=OrderModel::where('id',$this->orderID)->find();

        if(!$order){
            throw new OrderException();
        }

        // 如果订单存在 但是订单对应的uid和当前的uid不一致
        if(!Token::isValidOperate($order->user_id)){
            throw new TokenException([
                'msg'=>'订单与当前用户不匹配',
                'errorCode'=>10003
            ]);

        }

        // 如果订单已经支付
        if(!$order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg'=>'订单已经支付过了',
                'ErrorCode'=>80003,
                'code'=>400
            ])
        }


        $this->orderNO=$order->order_no;
        return true;
    }


    // 向微信服务器发送预定单请求
    private function makeWxPreOrder(){

    }
}
