<?php
namespace app\api\controller\v1;

// use think\Controller;
// use think\Db;
// use think\Validate;
use app\api\validate\IdPositiveInt;
use think\Exception;
use app\lib\exception\BannerMissException;

use app\api\model\Banner as BannerModel;

// 首页banner接口
class Banner{
    /**
     * [getBanner 获取指定id的banner信息]
     * @url /banner/:id     [访问接口的路径]
     * @http GET方式请求
     * @id [banner的id号]
     */
    public function getBanner($id){

        (new IdPositiveInt())->doCheck();//调用验证器 验证id必须是正整数

        // $banner=BannerModel::with(['items','items.img'])->find($id);//查询数据
        $banner=BannerModel::getBannerById($id);

        // dump($banner);die;//是一个模型对象实例 不是数组也不是数据集对象

        // 总结：模型的单个数据查询返回的都是模型对象实例，但查询多个数据的时候默认返回的是一个包含模型对象实例的数组

        if(!$banner){
            throw new BannerMissException();//抛出异常
        }
        $banner=$banner->hidden(['id','update_time','items.delete_time']);//使用模型对象示例的hidden方法在局部进行隐藏指定的字段 全局隐藏需要在模型中设置

        // return json($banner);
        return $banner;
        // dump($banner);







        /*
        异常处理流程
         try {
            $banner=BannerModel::getBannerById($id);
        } catch (Exception $e) {
            $err=[
                'error_code'=>1001,
                'msg'=>$e->getMessage()
            ];
            return json($err,400);//返回错误信息和指定状态码
        }
        return $banner;*/

    }
}
