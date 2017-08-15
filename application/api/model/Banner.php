<?php
namespace app\api\model;
use think\Model;

class Banner extends Model{

    protected $hidden=['id','description'];//隐藏指定的字段
    public function items(){
        // 第一个参数是要关联的模型名(需要先建立好)，第二个参数是关联模型外键，第三个参数是当前模型对应表的组件
        return $this->hasMany('BannerItem','banner_id','id');
    }

    public static function getBannerById($id){
        $banner=self::with(['items','items.img'])->find($id);//查询数据
        return $banner;
    }
}
