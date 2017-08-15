<?php

namespace app\api\model;



class Image extends Base
{
    // protected $hidden=['id','from','update_time','delete_time'];//隐藏指定的字段
    protected $visible=['url'];//指定可显示的字段


    // 获取器
    public function getUrlAttr($value,$data)
    {
        return $this->prefixImgUrl($value,$data);
    }
}
