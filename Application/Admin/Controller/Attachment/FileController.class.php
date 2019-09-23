<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 刁洪强
 * 修改时间: 2017-05-26 15:50
 * 功能说明:上传控制器
 */
namespace Admin\Controller\Attachment;

use Think\Controller;
use Admin\Controller\System\BaseController;
use XBCommon;
class FileController extends  BaseController
{
    public function UploadBatch(){
        $data=I("request.");
        $data['size']=get_basic_info('PicSize');
        $data['ext']=get_basic_info('PicExt');
        $this->assign("data",$data);
        $this->display('UploadBatch');
    }

    public function Upload(){
        $upload=new XBCommon\XBUpload();
        //获取后台设置的存储方式
        $store=get_basic_info('Store');
        $file_type=I("request.Path",'Files','trim');  //获取上传的是图片还是文件
        if($store=='0') {
            //为0时，文件保存到服务器本机
            //var_dump($file_type);exit;
            if($file_type=='image'){
                $res=$upload->uploadimage();
            }elseif($file_type=='video'){
//                $res=$upload->uploadvideo();
                $res=$upload->cutupload(); //调用分块上传
            }else{
                $res=$upload->uploadfile();
            }
        }elseif($store=='1'){
            //为1时，文件保存在七牛云服务器
            if($file_type=='image'){
                $res= $upload->QiniuUpload('image');
            }else{
                $res= $upload->QiniuUpload('file');
            }
        }elseif($store=='2'){
            //为2时,文件存储在阿里OSS服务器
            if($file_type=='image'){
                $res= $upload->OSSUpload('image');
            }else{
                $res= $upload->OSSUpload('file');
            }
        }

        //存储成功，返回路径
        if($res['result']==='success') {
            $file_path = array("FilePath" => $res['path']);
            echo json_encode($file_path);
        }else{
            $this->ajaxReturn(0,$res['error']);
        }
    }
}