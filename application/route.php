<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/*return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];*/

use think\Route;
// Route::rule('路由表达式','路由地址','请求类型','路由参数(数组)','变量规则(数组)');

// Route::rule('hello','sample/test/hello','GET|POST',['https'=>false]);
// Route::get('hello/:id','sample/test/hello');
// Route::post('hello/:id','sample/test/hello');
// Route::any('hello','sample/test/hello');


// banner接口路由
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');

// 专题接口路由
Route::get('api/:version/theme/','api/:version.Theme/getSimpleList');

// 专题内容接口路由  需要在配置里开启路由完整匹配模式
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');

// 最新产品接口路由
Route::get('api/:version/product/recent','api/:version.Product/getNewProducts');

// 获取指定分类id下面的产品
Route::get('api/:version/product/category','api/:version.Product/getAllInCategory');

//所有分类接口
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

//
Route::post('api/:version/token/user','api/:version.Token/getToken');//用post是为了不让code参数在url里显示出来
