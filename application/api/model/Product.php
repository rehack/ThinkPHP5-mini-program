<?php
namespace app\api\model;

class Product extends Base{
    // product表里面的冗余数据可以提升数据查询性能 可以减少join

    protected $hidden=['delete_time','update_time','create_time','category_id','img_id','from','pivot'];//pivot是代表中间的字段 是tp5加上的

    // 获取器
    public function getMainImgUrlAttr($value,$data)
    {
        return $this->prefixImgUrl($value,$data);
    }
}
