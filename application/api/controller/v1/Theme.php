<?php
namespace app\api\controller\v1;

// use think\Controller;
// use think\Db;
// use think\Validate;
use app\api\validate\IdCollection;
// 首页专题
class Theme{

    // 获取专题列表
    /*
        @url /theme?ids=1,2,3...
        @return 一组theme模型
     */
    public function getSimpleList($ids=''){
        (new IdCollection())->doCheck();
        return 'ok';
    }
}
