<?php
namespace app\api\service;
use app\api\model\Product as ProductModel;
use app\lib\exception\OrderException;
use app\api\model\UserAddress as UserAddressModel;
use app\lib\exception\UserException;
use app\api\model\Order as OrderModel;
use app\api\model\OrderProduct as OrderProductModel;
use think\Db;

class Order{
    // 订单的商品列表 也就是客户端传过来的products参数
    protected $orderProducts;
    /*$orderProducts=[
        [
            'product_id'=>1,
            'count'=>2
        ],
        [
            'product_id'=>2,
            'count'=>4
        ]
    ];//模拟数据结构*/

    // 从数据库里查询出来的商品信息(包括库存量)
    protected $products;
    /*$products=[
        [
            'product_id'=>1,
            'count'=>1
        ],
        [
            'product_id'=>2,
            'count'=>6
        ]
    ];//模拟数据结构*/


    protected $uid;

    public function place($uid,$orderProducts){
        // 将orderProducts和products进行对比 检测库存
        $this->orderProducts=$orderProducts;
        $this->products=$this->getProductsByOrder($orderProducts);
        $this->uid=$uid;
        $status=$this->getOrderStatus();
        if(!$status['pass']){
            // 如果库存量检测不通过
            $status['order_id']=-1;
            return $status;
        }

        // 开始创建订单
        $orderSnap=$this->snapOrder($status);//创建订单快照
        $order=$this->createOrder($orderSnap);
        $order['pass']=true;
        return $order;
    }

    // 根据订单信息查询真实的商品信息
    private function getProductsByOrder($orderProducts){
        $orderProductsIds=[];//存放orderProducts商品订单里面的所有的商品id
        foreach ($orderProducts as $item) {
            array_push($orderProductsIds, $item['product_id']);
        }

        // 根据订单商品id从数据库里进行查询
        $products=ProductModel::all($orderProductsIds)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();

        return $products;
    }

    // 获取订单状态
    private function getOrderStatus(){
        $status=[
            'pass'=>true,
            'orderPrice'=>0,// 订单合计金额
            'totalCount'=>0,
            'pStatusArray'=>[]// 保存订单里面商品的详细信息
        ];

        foreach ($this->orderProducts as $orderProduct) {
            $pStatus=$this->getProductStatus($orderProduct['product_id'],$orderProduct['count'],$this->products);

            if(!$pStatus['havaStock']){
                $status['pass']=false;
            }
            $status['orderPrice']+=$pStatus['totalPrice'];
            $status['totalCount']+=$pStatus['count'];
            array_push($status['pStatusArray'],$pStatus);
        }
        // dump($status);die;
        return $status;
    }

    private function getProductStatus($orderProductsIds,$orderCount,$products){
        $pIndex=-1;

        $pStatus=[
            'id'=>null,
            'havaStock'=>false,
            'count'=>0,
            'name'=>'',
            'totalPrice'=>0
        ];

        for($i=0;$i<count($products);$i++){
            if($orderProductsIds==$products[$i]['id']){
                $pIndex=$i;
            }
        }

        if($pIndex==-1){
            // 客户端传递的oroduct_id 有可能根本不存在
            throw new OrderException([
                'msg'=>'id为'.$orderProductsIds.'的商品不存在，创建订单失败'
            ]);
        }else{
            $product=$products[$pIndex];
            $pStatus['id']=$product['id'];
            $pStatus['count']=$orderCount;
            $pStatus['name']=$product['name'];
            $pStatus['totalPrice']=$product['price'] * $orderCount;
            $pStatus['havaStock']=$product['stock'] - $orderCount>=0 ? true : flase;
        }

        return $pStatus;

    }


    // 准备订单快照数据
    private function snapOrder($status){
        // 快照信息
        $snap=[
            'orderPrice'=>0,
            'totalCount'=>0,
            'pStatus'=>[],
            'snapAddress'=>null,
            'snapName'=>'',
            'snapImg'=>'',
        ];

        $snap['orderPrice']=$status['orderPrice'];
        $snap['totalCount']=$status['totalCount'];
        $snap['pStatus']=$status['pStatusArray'];
        $snap['snapAddress']=json_encode($this->getUserAddress());//将数组序列化成json字符串
        $snap['snapName']=count($this->products) > 1 ? $this->products[0]['name'].'等' : $this->products[0]['name'];
        $snap['snapImg']=$this->products[0]['main_img_url'];


        return $snap;
    }


    // 获取用户收货地址
    private function getUserAddress(){
        $userAddress=UserAddressModel::where('user_id',$this->uid)->find();
        if(!$userAddress){
            throw new UserException([
                'msg'=>'用户收货地址不存在，下单失败',
                'errorCode'=>60001
            ]);
        }

        return $userAddress->toArray();
    }

    // 生成订单编号
    public static function makeOrderNo(){
        $yCode=array('A','B','C','D','E','F','G','H','I','J');
        $orderSn=$yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date('d') . substr(time(),-5) . substr(microtime(),2,5) . sprintf('%02d',rand(0,99));
        return $orderSn;
    }

    // 创建订单（将订单写入数据库）
    private function createOrder($snap){
        Db::startTrans();// 启动事务
        try {
            $orderNo=$this->makeOrderNo();//订单号
            $order=new OrderModel();
            $order->user_id=$this->uid;
            $order->order_no=$orderNo;
            $order->total_price=$snap['orderPrice'];
            $order->total_count=$snap['totalCount'];
            $order->snap_img=$snap['snapImg'];
            $order->snap_name=$snap['snapName'];
            $order->snap_address=$snap['snapAddress'];
            $order->snap_items=json_encode($snap['pStatus']);

            // dump($order);die;

            $order->save();


            $orderID=$order->id;
            $create_time=$order->create_time;//下单时间

            foreach ($this->orderProducts as &$p) {
                $p['order_id']=$orderID;
            }

            $orderProduct=new orderProductModel();
            $orderProduct->saveAll($this->orderProducts);

            // 提交事务
            Db::commit();

            return [
                'order_no'=>$orderNo,
                'order_id'=>$orderID,
                'create_time'=>$create_time
            ];


        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();

            // 抛出异常
            throw $e;
        }

    }


    // 对外提供一个库存量检测的方法 通过订单id查询
    public function checkOrderStock($orderId){
        $orderProducts=OrderProductModel::where('order_id',$orderId)->select();

        $this->orderProducts=$orderProducts;
        $this->products=$this->getProductsByOrder($orderProducts);
        $status=$this->getOrderStatus();
        return $status;
    }
}
