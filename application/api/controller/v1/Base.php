<?php
namespace app\api\controller\v1;
use think\Controller;
use app\api\service\Token as TokenService;
// 控制器基类
class Base extends Controller{
    // 定义前置方法 验证权限
    protected function checkPrimaryScope(){
        TokenService::needPrimaryScope();

    }

    // 定义前置方法
    protected function checkExclusiveScope(){
        TokenService::needExclusiveScope();

    }
}
