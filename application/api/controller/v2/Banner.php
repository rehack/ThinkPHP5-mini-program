<?php
namespace app\api\controller\v2;

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

        return 'This is version 2.0';

    }
}
