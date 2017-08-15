<?php

namespace app\api\model;

use think\Model;

class Base extends Model
{
    protected function prefixImgUrl($value,$data)
    {
        $img_prefix=config('setting.img_prefix');//获取自定义配置文件
        // from字段如果是1 说明是本地图片资源，需要对图片路径处理一下； from是2就是网络图片资源，直接返回地址即可
        return $data['from']==1 ? $img_prefix.$value : $value;
        // return $img_prefix.$value;
    }
}
