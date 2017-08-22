<?php
namespace app\api\controller\v1;
use app\api\validate\Count;
use app\api\validate\IdPositiveInt;
use app\api\model\Product as ProductModel;

// 首页最新产品
class Product{

    /*
        最近新品接口
        @url /product/recent?count=
     */
    public function getNewProducts($count=15){
        (new Count())->doCheck();

        $recents=ProductModel::getRecent($count);
        // dump($recents);die;//object(think\model\Collection)数据集对象

        /*if(!$recents){对数据集对象判断只能使用数据集对象的isEmpty()方法
            throw new ProductException();
        }*/

        if($recents->isEmpty()){
            throw new ProductException();
        }

        // $result=collection($recents)->hidden(['summary']);//将数组转化成数据集对象再用hidden方法隐藏指定字段,或者在database.php文件中配置'resultset_type'  => 'collection', 可以直接返回对象类型数据集，这是设置数据集对象的第一种方法，第二种方法是在模型中设置 protected $resultSetType = 'collection'; 第三种就是这里使用的collection()助手函数方法
        // return json($recents);
        // return $results;
        $recents=$recents->hidden(['summary']);//数据集对象的hidden方法 不是模型对象实例的hidden方法
        return $recents;
    }


    /*
        根据category id 获取此分类的产品接口
        url /product/category?id=3
     */
    public function getAllInCategory($id){
        (new IdPositiveInt())->doCheck();
        $products=ProductModel::getProductsByCategoryId($id);//数据集对象
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products->hidden(['summary']);
        return $products;
    }

    /**
     * 获取商品详情接口
     * @url   /product/11
     * @param  [type] $id [description]
     * @return  json     [description]
     */
    public function getProDetail($id){
        (new IdPositiveInt())->doCheck();
        $product=ProductModel::getProductDetail($id);
        if(!$product){
            throw new ProductException();
        }
        return $product;
    }
}
