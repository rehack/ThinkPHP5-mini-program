<?php
namespace app\api\service;
use app\api\model\Product as ProductModel;
use app\lib\exception\OrderException;

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
        $this->$uid=$uid;

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
            'orderPrice'=0,// 订单合计金额
            'pStatusArray'=[]// 保存订单里面商品的详细信息
        ];

        foreach ($this->orderProducts as $orderProduct) {
            $pStatus=$this->getProductStatus($orderProduct['product_id'],$orderProduct['count'],$this->products);
        }
    }

    private getProductStatus($orderProductsIds,$orderCount,$products){
        $pIndex=-1;

        $pStatus=[
            'id'=>null,
            'havaStock'=>false,
            'count'=>0,
            'name'=>'',
            'TotalPrice'=>0
        ];

        for($id=0;$i<count($products);$i++){
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
            $pStatus['TotalPrice']=$product['price'] * $orderCount;
            $pStatus['havaStock']=$product['stockt'] - $orderCount>=0 ? true : flase;
        }

        return $pStatus;

    }
}
