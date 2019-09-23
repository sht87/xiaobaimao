<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 刁洪强
 * 修改时间: 2017-06-02 10:42
 * 功能说明:缓存基本操作类库。
 */
namespace XBCommon;

use Think\Controller;
class XBCache extends Controller
{

    /**
     * 缓存实例插入
     */
    public static function Insert($name,$val,$expire=0){
        if(!empty($name) && !empty($val)){
            S($name,$val,$expire);
        }
    }

    /**
     * 移除单个缓存
     */
    public static function Remove($name){
        if(!empty($name)){
            S($name,null);
        }
    }


    /**
     * 获取缓存实例
     */
    public static function GetCache($name){
        if(!empty($name)){
            return S($name);
        }else{
            return null;
        }
    }


}