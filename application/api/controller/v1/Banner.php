<?php
namespace app\api\controller\v1;

// use think\Controller;
// use think\Db;
// use think\Validate;
use app\api\validate\IdPositiveInt;
use think\Exception;
use app\lib\exception\BannerMissException;

use app\api\model\Banner as BannerModel;
class Banner{
    /**
     * [getBanner 获取指定id的banner信息]
     * @url /banner/:id     [访问接口的路径]
     * @http GET方式请求
     * @id [banner的id号]
     */
    public function getBanner($id){

        (new IdPositiveInt())->doCheck();//验证id必须是正整数

        // $banner=BannerModel::with(['items','items.img'])->find($id);//查询数据
        $banner=BannerModel::getBannerById($id);
        // $banner=$banner->hidden(['update_time','items.delete_time']);//隐藏指定的字段

        if(!$banner){
            throw new BannerMissException();//抛出异常
        }
        return json($banner);
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
