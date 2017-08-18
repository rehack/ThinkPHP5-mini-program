<?php
namespace app\api\model;

class Theme extends Base{
    protected $hidden=['delete_time','update_time','topic_img_id','head_img_id'];

    // 定义专题封面图片模型
    public function topicImg(){
        return $this->belongsTo('Image','topic_img_id','id');
    }

    // 定义主题内页头图关联模型
    public function headImg(){
        return $this->belongsTo('Image','head_img_id','id');
    }

    // 定义产品关联模型
    public function products(){
        //第一个参数是关联的模型名，第二个参数是中间表的表名，第三个参数是外键名，第四个参数是当前模型关联键名
        //最后两个参数都在中间表里面
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    // 查询业务逻辑 根据主题id查询主题下面的产品和相关图片
    public static function getThemeWithProducts($id){
        $theme=self::with("products,topicImg,headImg")->find($id);
        return $theme;
    }






}
