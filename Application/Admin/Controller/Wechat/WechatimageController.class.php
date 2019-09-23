<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 叶程鹏
 * 修改时间: 2018-04-08 09:21
 * 功能说明: 图文回复控制器
 */
 namespace Admin\Controller\Wechat;
 
 use Admin\Controller\System\BaseController;
 use XBCommon;

 class WechatimageController extends BaseController{

     const T_TABLE='wx_wechats';
     const T_ADMIN='sys_administrator';

     public function index(){
         $this->display();
     }

     /**
      * 后台用户管理的列表数据获取
      * @access   public
      * @return   object    返回json数据
      */
     public function DataList(){

         $page=I('post.page',1,'intval');
         $rows=I('post.rows',20,'intval');
         $sort=I('post.sort');
         $order=I('post.order');
         if ($sort && $order){
             $sort=$sort.' '.$order;
         }else{
             $sort='Sort asc,ID desc';
         }

         $where['IsDel']=0;
         $where['Xtype']=4;//消息类型:1关注回复 2默认回复 3文本回复 4图文回复 5语音回复
         //查询的数据表字段名
         $col='';//默认全字段查询

         //获取主表的数据
         $query=new XBCommon\XBQuery;
         $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

         //重组数据返还给前段
         $result=array();
         if($array['rows']){
             foreach ($array['rows'] as $val) {
                 $val['OperatorID']=$query->GetValue(self::T_ADMIN,array('ID'=>(int)$val['OperatorID']),'UserName');
                 $val['Contents']=count(unserialize($val['Contents'])).'条';
                 if($val['Keystatus']=='1'){
                    $val['Keystatus']='完全匹配';
                 }elseif($val['Keystatus']=='2'){
                    $val['Keystatus']='包含匹配';
                 }
                 $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
         }
         $this->ajaxReturn($result);
     }


     /**
      * 编辑功能
      */
     public function Edit(){
         $id=I('request.ID',0,'intval');
         $comemts=M(self::T_TABLE)->where(array('ID'=>$id))->getField('Contents');
         if($comemts){
            $comtinfos=unserialize($comemts);
         }
         
         $this->assign('ID',$id);
         $this->assign('comtinfos',$comtinfos);
         $this->display();
     }

     /**
      * 查询详细信息
      */
     public function shows(){
         $id=I('request.ID',0,'intval');
         if($id){
             $tabList=M(self::T_TABLE)->where(array('ID'=>$id))->find();
             $this->ajaxReturn($tabList);
         }else{
            $this->ajaxReturn(array('tabList'=>false,'message'=>'没有该记录'));
         }
     }

     /**
      *保存数据
      */
     public function Save(){

         if(!IS_POST){
             $this->ajaxReturn(0,'数据提交方式不对');
         }
         //验证规则
         $rules=array(
             array('Keywords','require','搜索关键词必须填写'),
         );

         //提交的表单数据进行规则验证,并创建数据对象
         $model=D(self::T_TABLE);
         $FormData=$model->validate($rules)->create();
         if(!$FormData){
              //验证不通过，返回保存失败的提示信息
             $this->ajaxReturn(0,$model->getError());
         }else{
             //创建新数组，用于存储保存的数据

             $Title=I('post.Title','');
             $linkurl=I('post.linkurl','');
             $Pic=I('post.Pic','');
             $contArr=array();
             if($Title){
                foreach($Title as $k=>$v){
                    $oneArr=array();
                    if(!$linkurl[$k]){
                        $this->ajaxReturn(0,'链接地址必须填写');
                    }
                    if(!$Pic[$k]){
                        $this->ajaxReturn(0,'图片必须上传');
                    }
                    $oneArr['Title']=$v;
                    $oneArr['linkurl']=$linkurl[$k];
                    $oneArr['Pic']=$Pic[$k];
                    $contArr[]=$oneArr;
                }
             }
             $data=array();
             //保存数据
             $data=$FormData;
             $data['Xtype']='4';
             $data['Hftype']='2';
             $data['Contents']=serialize($contArr);
             $data['OperatorID']=$_SESSION['AdminInfo']['AdminID'];
             $data['UpdateTime']=date("Y-m-d H:i:s");
         }

         //更新或添加数据
         if($FormData['ID']>0){   //修改数据
             $saveResult=$model->where(array('ID'=>$FormData['ID']))->save($data);
             //返回保存的结果
             if($saveResult){
                 $this->ajaxReturn(1,'修改成功');
             }else{
                 $this->ajaxReturn(0,'修改失败');
             }
         }else{//添加数据(ID为空)

             $addResult=$model->add($data);
             if($addResult){
                $this->ajaxReturn(1,'添加成功');
             }else{
                 $this->ajaxReturn(1,'添加失败');
             }
         }
     }

     /**
      * 逻辑删除
      */
     public function Del(){

         //获取要删除的记录的id(单条或者多条)
         $ids=I('request.ID','','trim');
         $idArr=explode(',',$ids);
         $result=M(self::T_TABLE)->where(array('ID'=>array('in',$idArr)))->setField('IsDel',1);
         if($result){
             $this->ajaxReturn(1,'删除成功');
         }else{
             $this->ajaxReturn(0,'删除失败');
         }
     }
 }