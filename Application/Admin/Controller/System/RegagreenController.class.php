<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2017/12/4 8:47
 * 功能说明: 相关协议控制器
 */

 namespace Admin\Controller\System;

 use XBCommon;

 class RegagreenController extends BaseController{

     const T_TABLE='sys_reg';
     const T_ADMIN='sys_administrator';

     public function index(){
         $this->display();
     }

     /**
      * 按钮列表数据获取、检测、显示 （）
      * @access   public
      * @return   object    返回json数据
      */
     public function DataList(){

         //接收POST信息,拼接查询条件
         $page=I('post.page',1,'intval');
         $rows=I('post.rows',20,'intval');
         $sort=I('post.sort');
         $order=I('post.order');
         if($sort && $order){
             $sort = $sort.' '.$order;
         }else{
             $sort = 'ID asc';
         }

         $Title = I('post.Title');
         if($Title <> ''){
             $where['Title'] = array('like','%'.$Title.'%');
         }
         //查询的列名
         $col='';

         //获取最原始的数据列表
         $query=new XBCommon\XBQuery();
         $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);
         $Mtype=array("所有会员共用","我要考试会员","我是人才会员","我是企业会员");


         //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
         $result=array();
         if($array['rows']){
             foreach ($array['rows'] as $val){
                 $val['OperatorName']=$query->GetValue(self::T_ADMIN,array('ID'=>(int)$val['OperatorID']),'UserName');
                 $val['Mtype']=$Mtype[$val['Mtype']];
                 $result['rows'][]=$val;
             }
             $result['total']=$array['total'];
         }
         $this->ajaxReturn($result);
     }

     /**
      * 编辑页面数据获取
      * @access   public
      * @param    intval  $id   编辑该条数据的id 用于打开相应页面并传递id值
      * @param    intval  $getid   用于获取该条数据的id
      * @return  返回处理结果
      */
     public function edit(){
         $id=I("request.ID",0,'intval');
         $this->assign("id",$id);
         $this->display();
     }

     /**
      * @功能说明：显示编辑内容
      * @return [type]                           [description]
      */
     public function shows(){
         $id = I("request.ID");
         $centerModel = M(self::T_TABLE);
         $center_rec = $centerModel->where("ID=".$id)->find();
         $center_rec['Contents'] = htmlspecialchars_decode($center_rec['Contents']);
         $this->ajaxReturn($center_rec);
     }

     /**
      * 保存编辑数据 添加数据
      * @access   public
      * @param    string  $id   获取id组成的字符串
      * @return  返回处理结果
      */
     public function save(){
         $mod = M(self::T_TABLE);
         $id=I("request.ID",0,'intval');//获取数据ID
         IF(IS_POST){
             if (false === $data = $mod->create()) {
                 $this->ajaxReturn(0, $mod->getError());
             }
             if ($id) {//修改数据
                 $source_data=$mod->find($id);
                 $data['UpdateTime'] = date('Y-m-d H:i:s');

                 $data['OperatorID'] = $_SESSION['AdminInfo']['AdminID'];
                 $res = $mod->where(' ID='.$id)->save($data);
                 if ($res===false) {
                     $this->ajaxReturn(0, '修改失败');
                 }else{
                     admin_opreat_log($source_data,$data,$parameter="ID:$id",'修改');
                     $this->ajaxReturn(1, '修改成功');
                 }
             }
         }
     }
 }