<?php
namespace Api\Controller\Core;
use Think\Controller\RestController;
use XBCommon\CacheData;
use XBCommon\XBCache;
use XBCommon;

class PublicController extends RestController{
    const T_TABLE='version';

    /**
     * @功能说明: 获取对应客户的包名
     * @传输方式: get
     * @提交网址: /core/public/package
     * @提交方式：client=android
     * @返回方式: {'result'=>1,'message'=>'获取信息成功!','data'=>''}
     */
    public function package(){
        $Client = I('get.client','','trim');
        if(empty($Client)){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写客户端名称，如Android、IOS、WAP、PC')));
        }

        $find = M(self::T_TABLE)->where(array('Client'=>$Client,'Status'=>1))->order('ID desc')->find();
        if(!$find){
            $data=array(
                'result'=>1,
                'message'=>'网站后台已禁止该客户端访问!',
                'data'=>array(),
            );
        }else{
            $data=array(
                'result'=>'1',
                'message'=>'success',
                'data'=>array(
                    'client'=> $find['Client'],
                    'package'=> $find['Package'],
                    'ver'=> $find['Ver'],
                ),
            );
        }
        exit(json_encode($data));
    }
    /**
     * @功能说明: 图片上传(登录前)
     * @传输格式: post
     * @提交网址: /core/public/bfupload
     * @提交信息：非josn form 表单 post方式提交 array("client"=>"ios","package"=>"ios.ceshi","ver"=>"1.1") FILES  Multipart/form-data
     * @返回信息: {'result'=>1,'message'=>'修改成功！'}
     */
    public function bfupload(){
        //post 方式
       // $para=get_json_data(); //接收参数
        $para=I('post.');
        //-------把信息记录下来,测试白屏问题-----end
        if(empty($para['client'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写客户端名称，如Android、IOS、PC')));
        }
        if(empty($para['package'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写客户端包名')));
        }
        if(empty($para['ver'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写当前软件版本号')));
        }

        $common_package = common_package($para['client'],$para['package'],$para['ver']);
        if($common_package['result'] == 0){
            exit(json_encode(array('result'=>0,'message'=>$common_package['msg'])));
        }

        $upload=new XBCommon\XBUpload();
        $result=$upload->uploadimage();
        if($result['result']!='success'){
            $result=json_decode($result,true);
            exit(json_encode(array("result"=>0,"message"=>$result['message'])));
        }
        //返回图片存储的相对路径
        exit(json_encode(array("result"=>1,"message"=>$result['message'] ,'path'=>$result['path'] ,'filepath'=>'http://'.$_SERVER['HTTP_HOST'].$result['path'] ) ));

    }
    /**
     * @功能说明: 验证图形验证码
     * @传输方式: get
     * @提交网址: /core/tool/checkimgcode
     * @提交信息:  client=android&ver=1.1&package=android.ceshi&getcode=0&imgcode=3524&change=0
     *
     * @提交信息说明:
     *                     imgcode：接收到的图形验证码
     *                     change： 不为空则是点击更换图形验证码
     *                     getcode: 不为空则是点击“获取短信验证码”的动作，之后即是验证图形验证码是否正确
     *
     * @返回方式: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function checkimgcode(){
        $para=I('get.');

        if(empty($para['client'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写客户端名称，如Android、IOS、PC')));
        }
        if(empty($para['package'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写客户端包名')));
        }
        if(empty($para['ver'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写当前软件版本号')));
        }

        //验证包名和版本号
        $common_package = common_package($para['client'],$para['package'],$para['ver']);
        if($common_package['result'] == 0){
            exit(json_encode(array('result'=>0,'message'=>$common_package['msg'])));
        }

        $imgCode['imgPath']="http://".$_SERVER['HTTP_HOST']."/api.php/Home/T/getImgCode";
//        $imgCode['img']="<img src=http://".$_SERVER['HTTP_HOST']."/api.php/Home/T/getImgCode />";
        exit(json_encode(array('result'=>1,'message'=>"生成验证码成功！",'data'=>$imgCode)));
    }

	public function updateItem(){
		$date = date('Y-m-d').' 23:30:00';
		$time = date('Y-m-d H:i:s');
		if(strtotime($date)<strtotime($time)){//更新商品
			$list = M('item_enable')->where(array('createDate'=>date('Y-m-d')))->select();
			for($i=0;i<count($list);$i++){
				$item = $list[$i];
				$product = M('items')->where(array('ID'=>$item['itemId']))->find();
				$enableNum = $product['enableNum'];
				$deal = $item['deal'];
				$num = M('item_enable')->where(array('itemId'=>$item['itemId']))->count();
				if($num<$enableNum&&$deal==0){//需要上线
					$product['Status'] = 1;
					M('items')->save($product);
					$item['deal'] = 1;
					M('item_enable')->save($item);
				}
			}
		}
		exit(json_encode(array('result'=>1,'message'=>"成功！")));
	}
}