<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 张雨
 * 修改时间: 2018/3/12 13:48
 * 功能说明:
 */

namespace Admin\Controller\System;

use XBCommon;

class NeedsmsgController extends BaseController
{

    const  T_TABLE='comp_needsmsg';
    const  T_NEEDS='comp_needs';
    const  T_ADMIN='sys_administrator';

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
        $Name = I('post.Name','','trim');
        if($Name <> ''){
            $where['Name'] = array('like','%'.$Name.'%');
        }
        $Tel = I('post.Tel','','trim');
        if($Tel <> ''){
            $where['Tel'] = array('like','%'.$Tel.'%');
        }
        $Company= I('post.Company','','trim');
        if($Company <> ''){
            $CompID=M(self::T_NEEDS)->field('ID')->where(array('like','%'.$Company.'%'))->select();
            $where['NeedsID']=array('in',array_column($CompID,'ID'));
        }
        $IsAudit=I('post.IsAudit',-5,'intval');
        if($IsAudit!="-5"){
            $where['IsAudit']=$IsAudit;
        }
        $where['IsDel']=0;

        //查询的列名
        $col='';
        //获取最原始的数据列表
        $query=new XBCommon\XBQuery();
        $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

        $checkState=array("提交审核中","通过","未通过");

        //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
        $result=array();
        if($array['rows']){
            foreach ($array['rows'] as $val){
                $val['CompType']=$query->GetValue(self::T_NEEDS,array('ID'=>(int)$val['NeedsID']),'CompType');
                $val['OperatorName']=$query->GetValue(self::T_ADMIN,array('ID'=>(int)$val['OperatorID']),'UserName');
                $val['IsAudit']=$checkState[$val['IsAudit']];
                $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
        }
        $this->ajaxReturn($result);
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

    /*
      * 详情界面
      */
    public function detail(){

        //留言详情
        $id=I('request.ID',0,'intval');
        $tabList=M(self::T_TABLE)->where(array('ID'=>$id))->find();
        $tabList['Contents']=htmlspecialchars_decode($tabList['Contents']);
        if($tabList['OperatorID']){
            $operator=M(self::T_ADMIN)->field('UserName')->where(array('ID'=>$tabList['OperatorID']))->find();
            $this->assign('operator',$operator);
        }

        //留言人中意的转让公司详情
        if($tabList['NeedsID']){
            $CompType=M(self::T_NEEDS)->where(array('ID'=>$tabList['NeedsID']))->find();
            $CompType['Contents']=htmlspecialchars_decode($CompType['Contents']);
            if($CompType['OperatorID']){
                $operat=M(self::T_ADMIN)->field('UserName')->where(array('ID'=>$CompType['OperatorID']))->find();
                $this->assign('operat',$operat);
            }
            $this->assign('CompType',$CompType);
        }
        $this->assign('tabList',$tabList);
        $this->display();
    }

    /*
     * 审核
     */
    public function aduit(){
        $ID = I("request.ID",0,'intval');
        $reinfo=M(self::T_TABLE)->field('ID,Name,Tel,NeedsID,IsAudit') ->where(array('ID'=>$ID)) ->find();
        $transComp=M(self::T_NEEDS)->field('CompType')->where(array('ID'=>$reinfo['NeedsID']))->find();
        $reinfo['CompType']=$transComp['CompType'];
        $this->assign('reinfo',$reinfo);
        $this->display();
    }

    /*
     * 审核处理
     */
    public function auditSave()
    {
        $ID = I("request.ID", 0, 'intval');
        $IsAudit = I('request.IsAudit');

        //接受参数
        $rules = array(
            array('IsAudit', 'require', '请选择审核状态！')
        );
        //根据表单提交的POST数据和验证规则条件，创建数据对象
        $model = D(self::T_TABLE);
        $FormData = $model->validate($rules)->create();
        if (!$FormData) {
            //验证不通过,提示保存失败的JSON信息
            $this->ajaxReturn(0, $model->getError());
        }

        $data['OperatorID']=$_SESSION['AdminInfo']['AdminID'];
        $data['IsAudit'] = $IsAudit;
        $data['UpdateTime'] = date('Y-m-d H:i:s');

        $res=$model->where(array('ID'=>$ID))->save($data);

        if (false!==$res || 0!==$res) {
            $this->ajaxReturn(1, '审核提交成功');
        } else {
            $this->ajaxReturn(0, '审核提交失败');
        }
    }
}