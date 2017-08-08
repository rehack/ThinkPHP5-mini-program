<?php
namespace app\api\controller\v1;

// use think\Controller;
// use think\Db;
// use think\Validate;
use app\api\validate\IdPositiveInt;
use think\Exception;

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

        /*$data=[
            'id'=>$id,
        ];

        // $validate=new TestValidate();//验证器
        $validate=validate('TestValidate');//助手函数验证器


        if(!$validate->check($data)){
            return $validate->getError();
            exit;

        }*/
        /*$banner=Db::table('banner')->find($id);
        dump($banner);*/


        try {
            $banner=BannerModel::getBannerById($id);
        } catch (Exception $e) {
            $err=[
                'error_code'=>1001,
                'msg'=>$e->getMessage()
            ];
            return json($err,400);
        }
        return $banner;

    }
}
