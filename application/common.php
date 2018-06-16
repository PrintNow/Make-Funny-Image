<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 根据 `模板英文名` 选模板
 * @param $arr
 * @param $en_name
 * @return bool
 */
function pick_template($arr, $en_name){
    foreach ($arr as $val){
        if($val['en_name'] == $en_name){
            return $val;
            break;
        }
    }

    return false;
}