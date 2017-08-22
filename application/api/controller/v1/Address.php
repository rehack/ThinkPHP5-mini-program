<?php
namespace app\api\controller\v1;
use app\api\model\Category as CategoryModel;

// 写入客户地址接口
class Address{
    /**
     * [createOrUpdateAddress 创建或更新客户收货地址]
     * @return [type] [description]
     */
    public function createOrUpdateAddress(){
        (new AddressNew())->doCheck();
        // 1.根据token来获取uid
        // 2.根据uid来查找用户数据，判断用户是否存在，不存在就抛出异常
        // 3.获取用户从客户端提交来的地址信息
    }


}
