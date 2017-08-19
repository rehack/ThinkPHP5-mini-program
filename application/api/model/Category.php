<?php
namespace app\api\model;
// 分类表模型
class Category extends Base{
    protected $hidden=['delete_time','update_time'];
    // 关联图片模型
    public function img(){
        return $this->belongsTo('Image','topic_img_id','id');
    }
}
