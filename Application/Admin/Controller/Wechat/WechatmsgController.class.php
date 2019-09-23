<?php
/**
 *  版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 叶程鹏
 * 修改时间: 2018-01-06 15:50
 * 功能说明: 微信发送消息管理
 */

 namespace Admin\Controller\Wechat;

use XBCommon;

use Admin\Controller\System\BaseController;

class WechatmsgController extends BaseController
{
    const T_TABLE='wx_wechatmsgs';
    const T_MEMBER='mem_info';
    const T_ADMIN='sys_administrator';

    public function index(){
        $this->display();
    }
    /**
     * 获取消息列表
     */
    public function DataList(){
   		//接收POST信息,拼接查询条件
        $page=I('post.page',1,'intval');
        $rows=I('post.rows',20,'intval');
        $sort=I('post.sort','','trim');
        $order=I('post.order');
        if($sort && $order) {
              $sort = $sort . ' ' . $order;
        }else{
            $sort = 'ID desc';
        }

        $UserID = I('post.UserID','','trim');
        $Status=I('post.Status',-5,'intval');

        if($UserID){
          $where['UserID']=array('eq',$UserID);
        }
        if($Status!=-5){
            $where['Status']=$Status;
        }
        $where['IsDel'] = 0;

        //查询的列名
        $col='';
        //获取最原始的数据列表
        $query=new XBCommon\XBQuery();
        $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

   		  //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
        $result=array();
        if($array['rows']){
   			  foreach ($array['rows'] as $val){
                  $MemInfo=M(self::T_MEMBER)->field('TrueName,Mobile')->where(array('ID'=>$val['UserID']))->find();
                  $val['TrueName']=$MemInfo['TrueName'];
                  $val['Mobile']=$MemInfo['Mobile'];

                  $val['OperatorID']=$query->GetValue(self::T_ADMIN,array('ID'=>$val['OperatorID']),'UserName');

                  if($val['Status']=='1'){
                     $val['Status']='<span style="color:green;">成功</span>';
                  }elseif($val['Status']=='0'){
                     $val['Status']='<span style="color:red;">失败</span>';
                  }
   				        $result['rows'][]=$val;
   			  }
   			  $result['total']=$array['total'];
   		  }
        $this->ajaxReturn($result);
   	}

    /**
     * 逻辑删除
     */
    public function del(){
        $mod = M(self::T_TABLE);
        $id_str=I("post.ID",'','trim');
        $data = array('IsDel'=>1);

        $map['ID']  = array('in',$id_str);

        $res = $mod->where($map)->setField($data);
        if($res===false){
            $this->ajaxReturn(0,"删除数据失败！");
        }else{
            $this->ajaxReturn(1,"删除数据成功！");
        }
    }

    /**
     * 详情界面
     */
    public function detail(){
        $id=I('request.ID');
        $tabList=M(self::T_TABLE)->alias('a')
            ->join('left join xb_mem_info b on a.UserID=b.ID')
            ->field('a.*,b.TrueName,b.Mobile')
            ->where(array('a.ID'=>$id))
            ->find();
        $this->assign('tabList',$tabList);
        $this->display();
    }

}