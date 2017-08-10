<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');

// 自定义日志目录 默认在runtime
define('LOG_PATH', __DIR__ . '/../log/');

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';

// 开启全局日志 只记录sql日志  这个只是在开发环境下临时使用，利于调试，在生产环境下不需要开启
\think\Log::init([
    'type'=>'file',
    'path'=>LOG_PATH,
    'level'=>['sql']
]);
