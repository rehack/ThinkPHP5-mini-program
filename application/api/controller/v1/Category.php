<?php
namespace app\api\controller\v1;
use app\api\model\Category as CategoryModel;

// 分类页面接口
class Category{

    /*
        所有分类接口
        @url /category/all
     */
    public function getAllCategories(){
        $categories=CategoryModel::all([],'img');//等同于CategoryModel::with('img')-select();
        // dump($categorys);die;//array
        if(!$categories){
            throw new CategoryException();
        }

        return json($categories);
    }

}
