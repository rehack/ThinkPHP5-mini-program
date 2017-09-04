<?php
namespace app\api\service;

use think\Exception;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;
use app\api\service\Token as TokenService;
use app\lib\exception\TokenException;
use app\lib\enum\OrderStatusEnum;
use think\Loader;

// Loader::import方法先导入没有使用命名空间的扩展类库  extend/WxPay/WxPay.Api.php
Loader::import('WxPay.WxPay',EXTEND_PATH,'Api.php');

class Pay{
    private $orderID;
    private $orderNo;

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

        return $this->makeWxPreOrder($status['orderPrice']);
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
            ]);
        }


        $this->orderNO=$order->order_no;
        return true;
    }


    // 向微信服务器发送预定单请求
    private function makeWxPreOrder($totalPrice){
        $openid=TokenService::getCurrentTokenVal('openid');// 获得当前用户的openid
        if(!$openid){
            throw new TokenException();
        }

        $wxOrderData=new \WxPayUnifiedOrder();

        $wxOrderData->SetOut_trade_no($this->orderNO);//设置订单编号
        $wxOrderData->SetTrade_type('JSAPI');//设置订单类型
        $wxOrderData->SetTotal_fee($totalPrice*100);//设置订单总价格
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url('http://qq.com');//

        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData){
        // 调用微信预订单接口
        $wxOrder=new \WxPayApi;
        dump($wxOrder);die;


        // $wxOrder=new \WxPayApi::unifiedOrder($wxOrderData);
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS'){

            // 记录到日志
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
        }

        return null;
    }
}
