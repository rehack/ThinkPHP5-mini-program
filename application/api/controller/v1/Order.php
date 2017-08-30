<?php
namespace app\api\controller\v1;
use app\api\service\Token as TokenService;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use app\api\validate\OrderPlace;
use app\api\service\Order as OrderService;
//下单接口
class Order extends Base{
    // 定义前置操作
    protected $beforeActionList=[
        'checkExclusiveScope'=>['only'=>'placeOrder']
    ];









    // 1.用户在选择好商品后，向API提交他选择的商品的相关信息
    // 2.API在接收到提交来的商品数据时，需要检测订单所包含的商品的库存量
    // 3.如果有库存，把订单的数据存入数据库中，下单成功了，给客户端返回可以支付了的消息
    // 4.客户端调用支付接口，进行支付操作
    // 5.再次进行库存量检测
    // 6.服务器端调用微信支付接口进行支付
    // 7.微信会返回一个支付的结果（异步）
    // 8.成功也需要进行库存量检测
    // 9.根据微信返回的支付结果对库存量进行扣除（成功就扣除，失败就不扣除并返回信息）


    /**
     * 下单接口
     * @url /order
     * @return [type] [description]
     */
    public function placeOrder(){
        (new OrderPlace())->doCheck();
        $products=input('post.products/a');//变量修饰符 强制转换为数组类型
        $uid=TokenService::getCurrentUID();

        // dump($uid);die;
        $order=new OrderService();
        $status=$order->place($uid,$products);
        return json($status);
    }
}
