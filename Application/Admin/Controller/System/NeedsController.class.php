<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2017/12/4 9:45
 * 功能说明: 首页控制器
 */
 
 namespace Admin\Controller\System;

use XBCommon;

 class NeedsController extends BaseController{

     const  T_TABLE='comp_needs';
     const  T_ADMIN='sys_administrator';
     const  T_BUYMSG='comp_buymsg';
/*
 * 渲染
 */
     public function index(){
         $this->display();
     }

     /*
      * EASYUI模板前台数据处理
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
             $sort = 'ID desc';
         }

         //获取搜索条件
         $CompType = I('post.CompType','','trim');
         if($CompType <> ''){
             $where['CompType'] = array('like','%'.$CompType.'%');
         }
         $ZiziName = I('post.ZiziName','','trim');
         if($ZiziName <> ''){
             $where['ZiziName'] = array('like','%'.$ZiziName.'%');
         }
         $where['IsDel']=0;

         //查询的列名
         $col='';
         //获取最原始的数据列表
         $query=new XBCommon\XBQuery();
         $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

         //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
         $result=array();
         if($array['rows']){
             foreach ($array['rows'] as $val){
                 $val['OperatorName']=$query->GetValue(self::T_ADMIN,array('ID'=>(int)$val['OperatorID']),'UserName');
                 $result['rows'][]=$val;
             }
             $result['total']=$array['total'];
         }
         $this->ajaxReturn($result);
     }
      /*
       * 详情界面
       */
     public function detail(){
         $id=I('request.ID',0,'intval');
         $tabList=M(self::T_TABLE)->where('ID='.$id)->find();
         $Contents=htmlspecialchars_decode($tabList['Contents']);
         if($tabList['OperatorID']){
             $operator=M(self::T_ADMIN)->field('UserName')->where(array('ID'=>$tabList['OperatorID']))->find();
             $this->assign('operator',$operator);
         }

         $this->assign('tabList',$tabList);
         $this->assign('Contents',$Contents);
         $this->display();
     }


     /*
      * 保存数据
      */
     public function save(){
         if( !IS_POST ){
             $this->ajaxReturn(0, '数据提交方式不对');
         }

         //数据保存前的验证规则
         $rules = array(
             array('CompType','require','公司类型必填'),
             array('MemName','require','联系人必填'),
             array('ZiziName','require','资质名称及级别必填'),
             array('PriceMin','require','最小收购价格必填'),
             array('PriceMax','require','最大收购价格必填'),
         );
         //处理表单接收的数据
         $model=D(self::T_TABLE);
         $data=$model->validate($rules)->create();
         if(!$data){
             //验证不通过,提示保存失败的JSON信息
             $this->ajaxReturn(0,$model->getError());
         }else{
             $id=I('request.ID',0,'intval');
             $data['OperatorID']=$_SESSION['AdminInfo']['AdminID'];
             $data['UpdateTime']=date("Y-m-d H:i:s");
             if( $id>0 ){
                 $res=M(self::T_TABLE)->where(array('ID'=>$id))->save($data);
                 if($res){
                     $this->ajaxReturn(1,'修改数据成功！');
                 }else{
                     $this->ajaxReturn(0,'数据修改出错了！');
                 }
             }else{
                 $data['AddTime']=date("Y-m-d H:i:s");
                 $res=M(self::T_TABLE)->add($data);
                 if( $res ){
                     $this->ajaxReturn(1,'新增数据成功！');
                 }else{
                     $this->ajaxReturn(0,'数据保存失败！');
                 }
             }
         }
     }



     /*
      * 编辑页面
      */
     public function edit(){
         $ID=I('request.ID');
         $this->assign('id',$ID);
         $this->display();
     }

     /**
      * 查询详细信息
      */
     public function shows(){
         $id = I("request.ID",0, 'intval');
         if ($id) {
             $model = M(self::T_TABLE);
             $result = $model->find($id);
             if(!$result==null){
                 //对隐秘数据进行特殊化处理，防止泄露
                 $this->ajaxReturn($result);
             }else{
                 //没有查询到内容
                 $this->ajaxReturn(array('result'=>false,'message'=>'不存在的记录！'));
             }
         }
     }

     /*
      * 数据库中的数据逻辑删除  多条或单条删除
      * @access   public
      * @param    string  $id   获取id组成的字符串
      * @return  返回处理结果
      */
     public function Del(){
         $tabModel=M(self::T_TABLE);

         //获取要删除的记录的id(单条或者多条)
         $ids=I('request.ID','','trim');
         $idArr=explode(',',$ids);

         //逻辑删除的字段值
         $data['IsDel']=1;
         $where['ID']=array('in',$idArr);
         $result=$tabModel->where($where)->save($data);  //逻辑删除
         if($result){
             $this->ajaxReturn(1,'删除成功');
         }else{
             $this->ajaxReturn(0,'删除失败');
         }
     }
 }
 