<?php
namespace app\lib\enum;

class ScopeEnum{
    // scope=16代表app用户的权限数值 scope=32代表cms（管理员）用户的权限数值
    const User=16;
    const Super=32;
}