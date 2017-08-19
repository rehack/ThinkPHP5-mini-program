<?php
namespace app\api\controller\v1;

// use think\Controller;
// use think\Db;
// use think\Validate;
use app\api\validate\IdCollection;
use app\api\validate\IdPositiveInt;
use app\api\model\Theme as ThemeModel;
use app\lib\exception\ThemeException;
// 首页专题
class Theme{

    // 获取专题列表接口
    /*
        @url /theme?ids=1,2,3...
        @return 一组theme模型
     */
    public function getSimpleList($ids=''){
        (new IdCollection())->doCheck();
        $ids=explode(',',$ids);
        // dump($ids);
        $result=ThemeModel::with('topicImg,headImg')
            ->select($ids);
        // dump($result);die;//数组
        if(!$result){
            throw new ThemeException();
        }
        // dump($result);
        return json($result);

    }


    /*
        获取专题内容接口
        @url /theme/:id
     */
    public function getComplexOne($id){
        (new IdPositiveInt())->doCheck();
        $result=ThemeModel::getThemeWithProducts($id);
        // dump($result);die;//模型对象实例
        if(!$result){
            throw new ThemeException();
        }
        // $result->hidden(['name','id']);//模型对象实例方法
        return $result;
    }
}
