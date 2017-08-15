<?php
namespace app\api\model;
// use think\Model;

class BannerItem extends Base{
    protected $hidden=['id','img_id','banner_id','update_time','delete_time'];//隐藏指定的字段

    public function img(){
        return $this->belongsTo('Image','img_id','id');
    }
}
