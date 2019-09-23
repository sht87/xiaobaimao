<?php
// +----------------------------------------------------------------------
// | 版权所有: 青岛银狐信息科技有限公司
// +----------------------------------------------------------------------
// | 公司电话: 0532-58770968
// +----------------------------------------------------------------------
// | 功能说明:上传类库、拷贝之前的
// +----------------------------------------------------------------------
// | Author: XB.ghost.42
// +----------------------------------------------------------------------
// | Date: 2017/9/1
// +----------------------------------------------------------------------
namespace Extend;

class FileUpload
{
    /**
     * 功能说明：input图片上传处理方法
     */

    public function upload(){
        //获取前端传递的参数
        header('Content-Type:application/json; charset=utf-8');
        $data = I('request.');
        $chunk = $data['chunk']; //当前块数
        $chunks = $data['chunks']; //总的块数

        $NameAdd = $data['NameAdd']; //防重复添加前缀

        $path='/Upload/'.$data['Path'].'/'.date('Y-m-d').'/'; //虚拟路径，保存碎片文件


        $file_name = $data['name'];
        $temp_arr = explode(".", $file_name);

        $file_ext = array_pop($temp_arr);
        $file_ext = trim($file_ext);
        $file_ext = strtolower($file_ext);

        $temp_name = implode(".", $temp_arr);

        //分块名
        $chunks_name = $temp_name.'_'.$NameAdd. '_' . $file_ext;

        //新文件名
        $News_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;

        $uploadpath = $_SERVER['DOCUMENT_ROOT'].'\\Upload\\'.$data['Path'].'\\'.date('Y-m-d').'\\'; //物理路径
        //判断路径是否存在,不存在创建
        if(!file_exists($uploadpath)){
            mkdir($uploadpath);
        }
        $uploadFile=file_get_contents($_FILES['file']['tmp_name']);
        $file='';
        //判断是否需要分块处理
        if($data['chunks']<=1){
            //未分块,直接保存文件
            $newfile=$uploadpath.$News_name;

            //$file=$uploadpath.$chunks_name; //文件路径

            file_put_contents($newfile, $uploadFile,FILE_APPEND);
            // file_put_contents($file,$uploadFile);
        }else{
            //有分块,保存分块文件
            $file=$uploadpath.$chunks_name.'_'.$chunk; //分块文件路径
            file_put_contents($file,$uploadFile,FILE_APPEND);
            //判断是不是最后一个分块
            if($chunks-$chunk==1){
                //合并文件保存
                $newfile=$uploadpath.$News_name;
                for ($i=0;$i<$chunks;++$i){
                    $part=$uploadpath.$chunks_name.'_'.$i;
                    $bytes=file_get_contents($part);
                    file_put_contents($newfile,$bytes,FILE_APPEND); //将分块文件逐个合并
                    unlink($part); //删除已合并的分块文件
                }
            }
        }

        //返回结果
        $result['result'] = 'success';
        $result['FilePath'] = $path.$News_name;
        $result['FileName'] = $temp_name;
        //$data['tName'] = serialize($temp_arr);
        return $result;
    }

}