<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 叶程鹏
 * 修改时间: 2018-05-18 16:50
 * 功能说明: 渠道明细统计控制器
 */
 namespace Admin\Controller\Operation;
 
 use Admin\Controller\System\BaseController;
 use XBCommon;

 class TuiguangrecordController extends BaseController{

     const T_TABLE='tg_admin';
     const T_MEMINFO='mem_info';
     const T_ADMIN='sys_administrator';

     public function index(){
     	//判断是不是推广渠道会员
         $tdmemid=M(self::T_TABLE)->where(array('UserName'=>$_SESSION['AdminInfo']['Admin']))->getField('ID');
         $adminlist=M(self::T_TABLE)->field('ID,Name')->where(array('Status'=>'1','IsDel'=>'0'))->select();
         $this->assign(array(
            'adminlist'=>$adminlist,
            'tdmemid'=>$tdmemid,
            ));
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

         //搜索条件
         $TgadminID=I('post.TgadminID',-5,'int');

         //判断是不是推广渠道会员
         $tdmemid=M(self::T_TABLE)->where(array('UserName'=>$_SESSION['AdminInfo']['Admin']))->getField('ID');
         if($tdmemid){
            $TgadminID=$tdmemid;
         }
         
         if($TgadminID!=-5){
            $where['TgadminID']=$TgadminID;
         }

         //变更时间
        $StartTime=I('post.StartTime');  //按时间查询
        $EndTime=I('post.EndTime');
        $ToStartTime=$StartTime;
        $ToEndTime=date('Y-m-d',strtotime($EndTime."+1 day"));
        if($StartTime!=null){
            if($EndTime!=null){
                //有开始时间和结束时间
                $where['RegTime']=array('between',$ToStartTime.','.$ToEndTime);
            }else{
                //只有开始时间
                $where['RegTime']=array('egt',$ToStartTime);
            }
        }else{
            //只有结束时间
            if($EndTime!=null){
                $where['RegTime']=array('elt',$ToEndTime);
            }
        }

         $where['IsDel']=0;
         //查询的数据表字段名
         $col='';//默认全字段查询
		
         //获取主表的数据
         $query=new XBCommon\XBQuery;
         $array=$query->GetDataList(self::T_MEMINFO,$where,$page,$rows,$sort,$col);
         //重组数据返还给前段
         $result=array();
         if($array['rows']){
             foreach ($array['rows'] as $val) {
             	 $val['Typname']='注册';
             	 if($val['TgadminID']){
             	 	$val['TgadminID']=$query->GetValue(self::T_TABLE,array('ID'=>(int)$val['TgadminID']),'Name');
             	 }else{
             	 	$val['TgadminID']='';
             	 }
             	 if($val['Mobile']){
             	 	//$val['Mobile']=substr_replace($val['Mobile'],'****',3,4);
             	 }
                 $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
         }
         $this->ajaxReturn($result);
     }


	 public function exportexcel(){
         //接收POST信息,拼接查询条件
        $page=I('post.page',1,'intval');
         $rows=I('post.rows',20,'intval');
         $sort=I('post.sort');
         $order=I('post.order');
         if ($sort && $order){
             $sort=$sort.' '.$order;
         }else{
             $sort='Sort asc,ID desc';
         }

         //搜索条件
         $TgadminID=I('post.TgadminID',-5,'int');

         //判断是不是推广渠道会员
         $tdmemid=M(self::T_TABLE)->where(array('UserName'=>$_SESSION['AdminInfo']['Admin']))->getField('ID');
         if($tdmemid){
            $TgadminID=$tdmemid;
         }
         
         if($TgadminID!=-5){
            $where['TgadminID']=$TgadminID;
         }

         //变更时间
        $StartTime=I('post.StartTime');  //按时间查询
        $EndTime=I('post.EndTime');
        $ToStartTime=$StartTime;
        $ToEndTime=date('Y-m-d',strtotime($EndTime."+1 day"));
        if($StartTime!=null){
            if($EndTime!=null){
                //有开始时间和结束时间
                $where['RegTime']=array('between',$ToStartTime.','.$ToEndTime);
            }else{
                //只有开始时间
                $where['RegTime']=array('egt',$ToStartTime);
            }
        }else{
            //只有结束时间
            if($EndTime!=null){
                $where['RegTime']=array('elt',$ToEndTime);
            }
        }

         $where['IsDel']=0;
         //查询的数据表字段名
         $col='';//默认全字段查询
		
         //获取主表的数据
         $query=new XBCommon\XBQuery;
         $array=$query->GetDataList(self::T_MEMINFO,$where,$page,$rows,$sort,$col);
         //重组数据返还给前段
         $result=array();
         if($array['rows']){
             foreach ($array['rows'] as $val) {
             	 $val['Typname']='注册';
             	 if($val['TgadminID']){
             	 	$val['TgadminID']=$query->GetValue(self::T_TABLE,array('ID'=>(int)$val['TgadminID']),'Name');
             	 }else{
             	 	$val['TgadminID']='';
             	 }
                 $result['rows'][]=$val;
            }
         }

        //导出拼装
        $html = '<table cellpadding="1" cellspacing="1" border="1" width="100%" bgcolor="#000000;">
            <tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" _REQUEST>日期</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>姓名</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>手机号码</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>类型</td>
				<td bgcolor="#FFFFFF" align="center" _REQUEST>渠道</td>
            </tr>';

        foreach($result['rows'] as $key=>$row){
            $html .= '<tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" >'.$row['RegTime'].'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['TrueName'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['Mobile'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['Typname'].'</td>
				<td bgcolor="#FFFFFF" align="center" >'.$row['TgadminID'].'</td>
            </tr>';
        }

        $html .= '</table>';
        $str_filename = date('Y-m-d', time()).'channellist';
        $html = iconv('UTF-8', 'GB2312//IGNORE',$html);
        header("Content-type: application/vnd.ms-excel; charset=GBK");
        header("Content-Disposition: attachment; filename=$str_filename.xls");
        echo $html;
        exit;
    }

 }
