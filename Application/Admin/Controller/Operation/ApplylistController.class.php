<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      叶程鹏
 * @修改日期：  2018-04-08
 * @功能说明：  申请记录
 */
namespace Admin\Controller\Operation;
use XBCommon;
use Admin\Controller\System\BaseController;
class ApplylistController extends BaseController {

    const T_TABLE='apply_list';
    const T_ADMIN='sys_administrator';
    const T_ITEMS='items';
    const T_MEMINFO='mem_info';
	/**
	 * @功能说明：显示分类页面
	 * @return [type]                           [description]
	 */
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
         $Recordid=I('post.Recordid','');
         if ($sort && $order){
             $sort=$sort.' '.$order;
         }else{
             $sort='ID desc';
         }

         //搜索条件
         if($Recordid){
            $where['ID']=array('eq',$Recordid);
         }
         $GoodsNo=I('post.GoodsNo');
         if($GoodsNo){
            $where['GoodsNo']=array('eq',$GoodsNo);
         }
         $Itype=I('post.Itype',-5,'int');
         if($Itype!=-5){
            $where['Itype']=$Itype;
         }
         $UserName=I('post.UserName','');
         if($UserName){
         	$memidArr=M(self::T_MEMINFO)->field('ID')->where(array('UserName'=>array('like','%'.$UserName.'%')))->select();
         	if($memidArr){
         		$memids=array_column($memidArr, 'ID');
         		$where['UserID']=array('in',$memids);
         	}else{
         		$where['UserID']=array('eq','0');
         	}
         }
         $TrueName=I('post.TrueName','');
         if($TrueName){
         	$where['TrueName']=array('like','%'.$TrueName.'%');
         }
         $IDCard=I('post.IDCard','');
         if($IDCard){
         	$where['IDCard']=array('eq',$IDCard);
         }
         $Mobile=I('post.Mobile','');
         if($Mobile){
         	$where['Mobile']=array('eq',$Mobile);
         }
         $Yjtype=I('post.Yjtype',-5,'int');
         if($Yjtype!=-5){
            $where['Yjtype']=$Yjtype;
         }
         $Status=I('post.Status',-5,'int');
         if($Status!=-5){
            $where['Status']=array('eq',$Status);
         }


         //添加时间
         $StartTime=I('post.StartTime');  //按时间查询
         $EndTime=I('post.EndTime');
         $ToStartTime=$StartTime;
         $ToEndTime=date('Y-m-d',strtotime($EndTime."+1 day"));
         if($StartTime!=null){
             if($EndTime!=null){
                 //有开始时间和结束时间
                 $where['Addtime']=array('between',$ToStartTime.','.$ToEndTime);
             }else{
                 //只有开始时间
                 $where['Addtime']=array('egt',$ToStartTime);
             }
         }else{
             //只有结束时间
             if($EndTime!=null){
                 $where['Addtime']=array('elt',$ToEndTime);
             }
         }

         $where['IsDel']=array('eq','0');

         //查询的数据表字段名
         $col='';//默认全字段查询

         //获取主表的数据
         $query=new XBCommon\XBQuery;
         $array=$query->GetDataList(self::T_TABLE,$where,$page,$rows,$sort,$col);

         //重组数据返还给前段
         $result=array();
         if($array['rows']){
             foreach ($array['rows'] as $val) {
                 $val['Recordid']=$val['ID'];
             	 $val['ItemName']=$query->GetValue(self::T_ITEMS,array('ID'=>(int)$val['ItemID']),'Name');
             	 if($val['OperatorID']){
             	 	$val['OperatorID']=$query->GetValue(self::T_ADMIN,array('ID'=>(int)$val['OperatorID']),'UserName');
             	 }
             	 if($val['Itype']=='1'){
             	 	$val['Itype']='平台网贷';
             	 }elseif($val['Itype']=='2'){
             	 	$val['Itype']='信用卡贷';
             	 }
                 if($val['Yjtype']=='1'){
                    $val['Yjtype']='按比例';
                    $val['Ymoney']='';
                 }elseif($val['Yjtype']=='2'){
                    $val['Yjtype']='按金额';
                    $val['BonusRate']='';
                 }
                 if($val['Status']=='0'){
                    $val['Status']='<span style="color:red;">未匹配</span>';
                 }elseif($val['Status']=='1'){
                    $val['Status']='<span style="color:green;">匹配上了</span>';
                 }
                 if($val['Isfan']=='0'){
                    $val['Isfan']='<span style="color:red;">未返</span>';
                 }elseif($val['Isfan']=='1'){
                    $val['Isfan']='<span style="color:green;">已返</span>';
                 }

				if($val['applyStatus']=='0'){
                    $val['applyStatus']='<span style="color:red;">未匹配</span>';
                 }elseif($val['applyStatus']=='1'){
                    $val['applyStatus']='<span style="color:green;">匹配上了</span>';
                 }
                 if($val['IsApplyfan']=='0'){
                    $val['IsApplyfan']='<span style="color:red;">未返</span>';
                 }elseif($val['IsApplyfan']=='1'){
                    $val['IsApplyfan']='<span style="color:green;">已返</span>';
                 }

             	 $val['UserName']=$query->GetValue(self::T_MEMINFO,array('ID'=>(int)$val['UserID']),'TrueName');
                 /*$TrueName = $val['TrueName'];
                 $len = mb_strlen($TrueName);
                 $nameArr = array();
                 array_push($nameArr, $TrueName);
                 for ($j=1; $j < $len; $j++) {
                     $vlen = $len - $j;
                     $rex = mb_substr($TrueName,$j,$vlen,'utf-8');
                     $rexx = mb_substr($TrueName,0,$j,'utf-8');
                     for ($k=0; $k < $j; $k++) { 
                         $rex = '*'.$rex;
                     }
                     array_push($nameArr, $rex);
                     for ($k=0; $k < $vlen; $k++) { 
                         $rexx = $rexx.'*';
                     }
                     array_push($nameArr, $rexx);
                 }
                 $Mobile = $val['Mobile'];
                 $mobile1 = substr($Mobile, 0,3);
                 $mobile11 = substr($Mobile, 10,1);
                 $mobileArr = array();
				 array_push($mobileArr,$Mobile);
                 array_push($mobileArr,$mobile1.'*******'.$mobile11);
                 $mobile11 = substr($Mobile, 9,2);
                 array_push($mobileArr,$mobile1.'******'.$mobile11);
                 $mobile11 = substr($Mobile, 8,3);
                 array_push($mobileArr,$mobile1.'*****'.$mobile11);
                 $mobile11 = substr($Mobile, 7,4);
                 array_push($mobileArr,$mobile1.'****'.$mobile11);
                 $where['Mobile'] = array('in',$mobileArr);//cid在这个数组中，
                 $where1['TrueName'] = array('in',$nameArr);//cid在这个数组中，
                 $count = M('apply_import')
                        ->where($where)
                        ->where($where1)
                       ->where(array('GoodsNo'=>$val['GoodsNo']))
                      ->count();
                 if($count==0){
                    $val['import']='<span style="color:red;">未匹配</span>';
                 }else{
                    $val['import']='<span style="color:green;">匹配上了</span>';
					if($val['rBack']==1){
						$val['import']='<span style="color:green;">已返现</span>';
					}
                 }*/
                 $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
         }
         $this->ajaxReturn($result);
     }
     //详情界面
    public function detail(){
        $id=I('request.ID');
        $infos=M(self::T_TABLE)->alias('a')
              ->field('a.*,b.Name as ItemName,c.UserName')
              ->join('left join xb_items b on a.ItemID=b.ID')
              ->join('left join xb_mem_info c on a.UserID=c.ID')
              ->where(array('a.ID'=>$id))->find();
        $this->assign('infos',$infos);
        $this->display();
    }
    //逻辑删除
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

    //放款记录批量导入功能
    public function data_import(){
        set_time_limit(0);
        ini_set("memory_limit", "1024M");
        ini_set("error_reporting","E_ALL & ~E_NOTICE");//屏蔽警告信息

        if($_FILES['datalist']){
            $tmp_file = $_FILES['datalist']['tmp_name']; 
            $file_types = explode('.', $_FILES['datalist']['name']);
            $file_type = $file_types[count($file_types)-1];
            if(!in_array(strtolower($file_type), array('xls', 'xlsx'))){
                $this->ajaxReturn2(0,'不是Excel文件，请重新选择');
            }else{ 
                vendor('Excel.PHPExcel');//引入
                $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($tmp_file);             
                $objWorksheet = $objPHPExcel->getActiveSheet();
                //获取上传Excel工作簿标题名 2016-02...   
                $sheetTitle=$objPHPExcel->getActiveSheet()->getTitle(); 
                $highestRow = $objWorksheet->getHighestRow();
                $highestColumn = $objWorksheet->getHighestColumn();
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn); 
                $excelData = array(); 
                //$row从2开始，因为1是标题,读取文件所有数据
                for ($row = 2; $row <= $highestRow; $row++) { 
                    for ($col = 0; $col < $highestColumnIndex; $col++) { 
                        $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    } 
                }
                //分割数组
                $chunkData = array_chunk($excelData,1000);
                $count = count($chunkData);  
                $numbs='0';
                for ($i = 0; $i < $count; $i++) {  
                    $insertRows = array(); 
                    foreach($chunkData[$i] as $k=>&$value){ 
                        //处理数据
                        $row = array();  
                        $row['Itype']=trim($value[0]); 
                        $row['GoodsNo']=trim($value[1]); 
                        $row['Name']=trim($value[2]); 
                        $row['TrueName']=trim($value[3]); 
                        $row['Mobile']=trim($value[4]); 
                        $row['Addtime']= $value[5]; 
                        $numbs++;
                        $sqlString='('."'".implode("','",$row)."'".')'; //批量  
                        $insertRows[]=$sqlString; 
                        //清空变量
                    }  
                    unset($chunkData[$i]);
                    //var_dump($insertRows);exit;
                    $result = $this->add_alldata($insertRows); //批量将sql插入数据库。
                }
                $this->ajaxReturn2(1,'共导入'.$numbs.'条记录');
            }
        }else{
            $this->ajaxReturn2(0,'请选择文件');
        }

     }
     //批量插入xb_apply_listresult数据库
    public function add_alldata($rows){ 

        if(empty($rows)){  
            return false;  
        }  
        $model=M('apply_listresult');
        //数据量较大,采取批量插入  IGNORE
        $data = implode(',', $rows);  
        $sql = "INSERT INTO xb_apply_import(Itype,GoodsNo,Name,TrueName,Mobile,Addtime)  
                 VALUES {$data}";  
        $result = $model->execute($sql);
        return true;
    }

    /**
      * AJAX返回数据标准
      * @param int $status  状态
      * @param string $msg  内容
      * @param mixed $data  数据
      * @param string $dialog  弹出方式
      */
     protected function ajaxReturn2($status = 0, $msg = '成功', $data = '', $dialog = '')
     {
         $return_arr = array();
         if (is_array($status)) {
             $return_arr = $status;
         } else {
             $return_arr = array(
                 'result' => $status,
                 'message' => $msg,
                 'des' => $data,
                 'dialog' => $dialog
             );
         }
         ob_clean();
         echo json_encode($return_arr);
         exit;
     }


	  //匹配返现
    public function mateback(){
        set_time_limit(0);
        $id=I("post.ID",'','trim');
        if(!$id){
            $this->ajaxReturn(0,"请选择要返现的记录");
        }
        //检验数据 
        $checkrest=M(self::T_TABLE)->where(array('ID'=>array('in',$id),'rBack'=>1))->find();
        if($checkrest){
            $this->ajaxReturn(0,"请先去除已返现的记录!");
        }
        $idArr=explode(',',$id);
        //开始匹配返现-------------------start
        $i=0;
        foreach($idArr as $k=>$v){
            $fk_info=M(self::T_TABLE)->find($v);
            //校验此数据是否是重复数据
            $TrueName = $fk_info['TrueName'];
			 $len = mb_strlen($TrueName);
			 $nameArr = array();
			 array_push($nameArr, $TrueName);
			 for ($j=1; $j < $len; $j++) {
				 $vlen = $len - $j;
				 $rex = mb_substr($TrueName,$j,$vlen,'utf-8');
				 $rexx = mb_substr($TrueName,0,$j,'utf-8');
				 for ($k=0; $k < $j; $k++) { 
					 $rex = '*'.$rex;
				 }
				 array_push($nameArr, $rex);
				 for ($k=0; $k < $vlen; $k++) { 
					 $rexx = $rexx.'*';
				 }
				 array_push($nameArr, $rexx);
			 }
			 $Mobile = $fk_info['Mobile'];
			 $mobile1 = substr($Mobile, 0,3);
			 $mobile11 = substr($Mobile, 10,1);
			 $mobileArr = array();
			 array_push($mobileArr,$Mobile);
			 array_push($mobileArr,$mobile1.'*******'.$mobile11);
			 $mobile11 = substr($Mobile, 9,2);
			 array_push($mobileArr,$mobile1.'******'.$mobile11);
			 $mobile11 = substr($Mobile, 8,3);
			 array_push($mobileArr,$mobile1.'*****'.$mobile11);
			 $mobile11 = substr($Mobile, 7,4);
			 array_push($mobileArr,$mobile1.'****'.$mobile11);
			 $where['Mobile'] = array('in',$mobileArr);//cid在这个数组中，
			 $where1['TrueName'] = array('in',$nameArr);//cid在这个数组中，
			 $count = M('apply_import')
					->where($where)
					->where($where1)
				   ->where(array('GoodsNo'=>$fk_info['GoodsNo']))
				  ->count();
			 if($count==0){
				continue;
			 }
			 if($count>0){
				//匹配上了
				$savedata['rBack']='1';
				//计算推荐人 奖金
				$item = M('items')->where(array('GoodsNo'=>$fk_info['GoodsNo']))->find();
				$parent=M(self::T_MEMINFO)->where(array('ID'=>$fk_info['UserID']))->find();
				$Mtype = $parent['Mtype'];
				$Smoney = 0;
				if($Mtype==1){
					$Smoney = is_null($item['Smoney1']) ? 0 : $item['Smoney1'];
				}
				if($Mtype==2){
					$Smoney = is_null($item['Smoney2']) ? 0 : $item['Smoney2'];
				}
				if($Mtype==3){
					$Smoney = is_null($item['Smoney3']) ? 0 : $item['Smoney3'];
				}
				if($Mtype==4){
					$Smoney = is_null($item['Smoney4']) ? 0 : $item['Smoney4'];
				}
				
				if($Smoney>0){
					//余额增加
					$addrest=M(self::T_MEMINFO)->where(array('ID'=>$fk_info['UserID']))->setInc('Balance',$Smoney);
					if($addrest){
						$savedata['rBack']='1';
						$savedata['Smoney']=$Smoney;
						//记录余额变动明细
						$CurrentBalance=M(self::T_MEMINFO)->where(array('ID'=>$fk_info['UserID']))->getField('Balance');
						$Description=M('items')->where(array('GoodsNo'=>$fk_info['GoodsNo']))->getField('Name');
						$Description.='申请分佣,申请记录id:'.$v;
						$dataintro=array(
							'Type'=>'0',
							'SruType'=>2,
							'oid'=>$v,
							'Amount'=>$Smoney,
							'CurrentBalance'=>$CurrentBalance,
							'Description'=>$Description,
							'Intro'=>$fk_info['Mobile'],
							'UserID'=>$fk_info['UserID'],
							'UpdateTime'=>date('Y-m-d H:i:s'),
							"TradeCode"=>date("YmdHis").rand(10000,99999),
							);
						M('mem_balances')->add($dataintro);
					}
				}
            }
            $dealrest=M(self::T_TABLE)->where(array('ID'=>$v))->save($savedata);
            if($savedata['rBack']=='1'){
                //短信和微信推送信息
                $Message = new \Extend\Message($v,2);
                $Message->sms();
            }
            $i++;
        }
        $this->ajaxReturn(1,'处理了'.$i.'条记录');
    }
}