<?php

namespace app\api\model;



class ProductImage extends Base
{
    protected $hidden=['img_id','delete_time','product_id'];


    // 关联图片模型
    public function imgUrl()
    {
        return $this->belongsTo('Image','img_id','id');
    }
}



