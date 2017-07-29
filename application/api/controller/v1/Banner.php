<?php
namespace app\api\controller\v1;

use think\Db;
use think\Validate;

class Banner{
    /**
     * [getBanner 获取指定id的banner信息]
     * @url /banner/:id     [访问接口的路径]
     * @http GET方式请求
     * @id [banner的id号]
     */
    public function getBanner($id){
        $data=[
            'id'=>$id,
        ];
        $validate=new Validate([
            'id'=>'require|number',
        ]);

        if(!$validate->check($data)){
            return $validate->getError();
            exit;

        }
        $banner=Db::table('banner')->find($id);
        dump($banner);

    }
}