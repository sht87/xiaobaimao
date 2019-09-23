<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      叶程鹏
 * @修改日期：  2018-04-08
 * @功能说明：  放款记录管理
 */
namespace Admin\Controller\Operation;
use XBCommon;
use Admin\Controller\System\BaseController;
class ListresultController extends BaseController {

    const T_TABLE='apply_listresult';
    const T_APPLYLIST='apply_list';
    const T_ADMIN='sys_administrator';
    const T_ITEMS='items';
    const T_MEMINFO='mem_info';
    const T_BALANCES='mem_balances';

	//显示页面
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
         $Status=I('post.Status',-5,'int');
         if($Status!=-5){
         	$where['Status']=array('eq',$Status);
         }
         $Isfan=I('post.Isfan',-5,'int');
         if($Isfan!=-5){
         	$where['Isfan']=array('eq',$Isfan);
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
             	 if($val['Opentime']){
             	 	$val['Opentime']=date('Y-m-d H:i:s',$val['Opentime']);
             	 }
             	 if($val['Itype']=='1'){
             	 	$val['Itype']='平台网贷';
             	 }elseif($val['Itype']=='2'){
             	 	$val['Itype']='信用卡贷';
             	 }
             	 if($val['isDown']=='2'){
             	 	$val['isDown']='<span style="color:red;">否</span>';
             	 }elseif($val['isDown']=='1'){
             	 	$val['isDown']='<span style="color:green;">是</span>';
             	 }
				
				$val['Status'] = '<span style="color:red;">未匹配</span>';
				$val['isTfan'] = '<span style="color:red;">未返现</span>';
				$val['applyStatus'] = '<span style="color:red;">未匹配</span>';
				$val['IsApplyfan'] = '<span style="color:red;">未返现</span>';
				$val['applyBonus'] = "";
				if(!is_null($val['applyListId'])){
					$model = M('apply_list');
					$vs = $model->find($val['applyListId']);
					$Status = $vs['Status'];
					if($Status==1){
						$val['Status'] = '<span style="color:green;">匹配上了</span>';
					}
					$isfan = $vs['Isfan'];
					if($isfan==1){
						$val['isTfan'] = '<span style="color:green;">已返现</span>';
					}
					$applyStatus = $vs['applyStatus'];
					if($Status==1){
						$val['applyStatus'] = '<span style="color:green;">匹配上了</span>';
					}
					$IsApplyfan = $vs['IsApplyfan'];
					if($IsApplyfan==1){
						$val['IsApplyfan'] = '<span style="color:green;">已返现</span>';
					}
					$val['applyBonus'] = $vs['applyBonus'];
				}

                 $result['rows'][]=$val;
            }
            $result['total']=$array['total'];
         }
         $this->ajaxReturn($result);
     }
     /**
     * @功能说明：编辑 添加 页面
     * @return [type]                           [description]
     */
	public function edit(){
		$id=I("request.ID",0,'intval');
		if($id){
			$model = M(self::T_TABLE);
            $result = $model->find($id);
			$this->assign("isfan",$result['isfan']);
			$this->assign("id",$id);
		}
		$this->display(); 
	}
	/**
     * 查询详细信息
     */
    public function Shows()
    {
        $id = I("request.ID", 0, 'intval');
        if ($id) {
            $model = M(self::T_TABLE);
            $result = $model->find($id);
            if($result){
            	$result['Opentime']=date('Y-m-d',$result['Opentime']);
                $this->ajaxReturn($result);
            }else{
                //没有查询到内容
                $this->ajaxReturn(array('result'=>false,'message'=>'不存在的记录！'));
            }
        }
    }
    /**
     * @功能说明：添加 编辑 分类信息
     * @return [type]                           [description]
     */
    public function save(){
        if(IS_POST){
            $model=M(self::T_TABLE);
			$v = $model->find(I('post.ID'));
			if($v['isfan']==1){
				 $this->ajaxReturn(0, '已经匹配数据，不可修改');
			}
            $data['ID']=I('post.ID');
            $data['Name']=I('post.Name','');
            $data['GoodsNo']=I('post.GoodsNo','');
            $data['Itype']=I('post.Itype','1');
            $data['TrueName']=I('post.TrueName','');
            $data['Mobile']=I('post.Mobile','');
			$data['Applytime']=I('post.Applytime','');
			$data['isDown']=I('post.isDown','');
            $data['Money']=I('post.Money','');
            $data['Opentime']=I('post.Opentime','');
            $data['Opentime']=strtotime($data['Opentime']);
            $data['OperatorID']=$_SESSION['AdminInfo']['AdminID'];
            $data['UpdateTime']=date("Y-m-d H:i:s");
            //更新数据判断
            if($data['ID']>0){
                $res=$model->where(array('ID'=>$data['ID']))->save($data);
                if($res>0){
                    $this->ajaxReturn(1, '修改成功');
                }else{
                    $this->ajaxReturn(0, '修改失败');
                }
            }else{
				$data['Addtime']=date("Y-m-d H:i:s");
                if($result3=$model->add($data)){
                    $this->ajaxReturn(1, '添加成功');
                }else{
                    $this->ajaxReturn(0, '添加失败');
                }
            }
        }else{
            $this->ajaxReturn(0, '数据提交方式不对');
        }
    }
    //获取商品---废弃
    public function getLastCate(){
         $cateData=M(self::T_ITEMS)->field('ID,Itype,Name,GoodsNo')->where(array('IsDel'=>'0'))->select();

         foreach ($cateData as $key=>$val){
         	 $typename='平台网贷';
         	 if($val['Itype']=='2'){
         	 	$typename='信用卡贷';
         	 }
             $row[]= array('id'=>$val['ID'],'text'=>$val['Name']."-".$val['GoodsNo']."-".$typename);
         }
         $this->ajaxReturn($row);
    }
    //导入页面
    public function importexcel(){
        $this->display();
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
						$row['Applytime']=trim($value[5]);
						$row['isDown']=2; 
						if(trim($value[6])=='是'){
							$row['isDown']=1; 
						}
                        $row['Money']=trim($value[7]); 
                        $row['Opentime']=trim($value[8]); 
                        $row['Opentime']=strtotime($row['Opentime']);
                        $row['Addtime']=date('Y-m-d H:i:s');

						$count = M(self::T_TABLE)
								->where(array('Mobile'=>$row['Mobile']))
								->where(array('TrueName'=>$row['TrueName']))
							   ->where(array('GoodsNo'=>$row['GoodsNo']))
							  ->count();
						if($count>0){
							continue;
						}
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
        $sql = "INSERT INTO xb_apply_listresult(Itype,GoodsNo,Name,TrueName,Mobile,Applytime,isDown,Money,Opentime,Addtime)  
                 VALUES {$data}";  
        $result = $model->execute($sql);
        return true;
    }
	//返现
	public function matebackMoney(){
		set_time_limit(0);
        $id=I("post.ID",'','trim');
        if(!$id){
			$list = M(self::T_TABLE)->where('isfan = 1 and applyListId is not null')->select();
			$num = 0;
			for($i=0;$i<count($list);$i++){
				$result = $list[$i];
				$applyListId = $result['applyListId'];
				$model =  M(self::T_APPLYLIST)->find($applyListId);
				$applyStatus = $model['applyStatus'];
				$IsApplyfan = $model['IsApplyfan'];
				$applyBouns = $model['applyBonus'];//申请返佣金额
				$send = false;
				if($applyStatus==1&&$IsApplyfan==0&&!is_null($applyBouns)){
					M(self::T_MEMINFO)->where(array('ID'=>$model['UserID']))->setInc('Balance',$applyBouns);
					//记录余额变动明细
					$CurrentBalance=M(self::T_MEMINFO)->where(array('ID'=>$model['UserID']))->getField('Balance');
					$Description=M('items')->where(array('GoodsNo'=>$model['GoodsNo']))->getField('Name');
					$Description.='申请分佣,申请记录id:'.$model['ID'];
					$dataintro=array(
						'Type'=>'0',
						'SruType'=>2,
						'oid'=>$v,
						'Amount'=>$applyBouns,
						'CurrentBalance'=>$CurrentBalance,
						'Description'=>$Description,
						'Intro'=>$model['Mobile'],
						'UserID'=>$model['UserID'],
						'UpdateTime'=>date('Y-m-d H:i:s'),
						"TradeCode"=>date("YmdHis").rand(10000,99999),
						);
					M('mem_balances')->add($dataintro);

					$savedata['IsApplyfan'] = 1;
					M(self::T_APPLYLIST)->where(array('ID'=>$model['ID']))->save($savedata);
					$send = true;
				}
				$Isfan = $model['Isfan'];
				$isDown = $result['isDown'];
				if($Isfan==0&&$isDown==1){
					$Money = $result['Money'];//放款金额
					$Yjtype = $model['Yjtype'];
					$Ymoney = $model['Ymoney'];//佣金金额
					$BonusRate = $model['BonusRate'];
					$bonusmoney='';
                    if($Yjtype=='1'){
                        //按比例
						if(!is_null($Money)){
							$bonusmoney=$Money*($BonusRate/100);
							$bonusmoney = round($bonusmoney,2);
						}
                    }elseif($Yjtype=='2'){
                        //按金额
                        $bonusmoney = $Ymoney;
                    }
					$savedata['Isfan'] = 1;
					$savedata['Bonus'] = $bonusmoney;
					if(!is_null($bonusmoney)&&$bonusmoney!=''){
						M(self::T_APPLYLIST)->where(array('ID'=>$model['ID']))->save($savedata);

						M(self::T_MEMINFO)->where(array('ID'=>$model['UserID']))->setInc('Balance',$bonusmoney);
						//记录余额变动明细
						$CurrentBalance=M(self::T_MEMINFO)->where(array('ID'=>$model['UserID']))->getField('Balance');
						$Description=M('items')->where(array('GoodsNo'=>$model['GoodsNo']))->getField('Name');
						$Description.='申请分佣,申请记录id:'.$model['ID'];
						$dataintro=array(
							'Type'=>'0',
							'SruType'=>2,
							'oid'=>$v,
							'Amount'=>$bonusmoney,
							'CurrentBalance'=>$CurrentBalance,
							'Description'=>$Description,
							'Intro'=>$model['Mobile'],
							'UserID'=>$model['UserID'],
							'UpdateTime'=>date('Y-m-d H:i:s'),
							"TradeCode"=>date("YmdHis").rand(10000,99999),
							);
						M('mem_balances')->add($dataintro);
						$sv['Ymoney'] = $bonusmoney;
						M(self::T_TABLE)->where(array('ID'=>$result['ID']))->save($sv);
						$send = true;
					}
				}
				if($send){
					//短信和微信推送信息
					$Message = new \Extend\Message($model['ID'],2);
					$Message->sms();
					$num++;
				}
			}
			$this->ajaxReturn(1,'处理了'.$num.'条记录');
		}
		$idArr=explode(',',$id);
        //开始匹配返现
        $num=0;
        foreach($idArr as $k=>$v){
            $result = M(self::T_TABLE)->find($v);
			$applyListId = $result['applyListId'];
			$model =  M(self::T_APPLYLIST)->find($applyListId);
			$applyStatus = $model['applyStatus'];
			$IsApplyfan = $model['IsApplyfan'];
			$applyBouns = $model['applyBonus'];//申请返佣金额
			$send = false;
			if($applyStatus==1&&$IsApplyfan==0&&!is_null($applyBouns)){
				M(self::T_MEMINFO)->where(array('ID'=>$model['UserID']))->setInc('Balance',$applyBouns);
				//记录余额变动明细
				$CurrentBalance=M(self::T_MEMINFO)->where(array('ID'=>$model['UserID']))->getField('Balance');
				$Description=M('items')->where(array('GoodsNo'=>$model['GoodsNo']))->getField('Name');
				$Description.='申请分佣,申请记录id:'.$model['ID'];
				$dataintro=array(
					'Type'=>'0',
					'SruType'=>2,
					'oid'=>$v,
					'Amount'=>$applyBouns,
					'CurrentBalance'=>$CurrentBalance,
					'Description'=>$Description,
					'Intro'=>$model['Mobile'],
					'UserID'=>$model['UserID'],
					'UpdateTime'=>date('Y-m-d H:i:s'),
					"TradeCode"=>date("YmdHis").rand(10000,99999),
					);
				M('mem_balances')->add($dataintro);

				$savedata['IsApplyfan'] = 1;
				M(self::T_APPLYLIST)->where(array('ID'=>$model['ID']))->save($savedata);
				$send = true;
			}
			$Isfan = $model['Isfan'];
			$isDown = $result['isDown'];
			if($Isfan==0&&$isDown==1){
				$Money = $result['Money'];//放款金额
				$Yjtype = $model['Yjtype'];
				$Ymoney = $model['Ymoney'];//佣金金额
				$BonusRate = $model['BonusRate'];
				$bonusmoney='';
				if($Yjtype=='1'){
					//按比例
					if(!is_null($Money)){
						$bonusmoney=$Money*($BonusRate/100);
						$bonusmoney = round($bonusmoney,2);
					}
				}elseif($Yjtype=='2'){
					//按金额
					$bonusmoney = $Ymoney;
				}
				$savedata['Isfan'] = 1;
				$savedata['Bonus'] = $bonusmoney;
				if(!is_null($bonusmoney)&&$bonusmoney!=''){
					M(self::T_APPLYLIST)->where(array('ID'=>$model['ID']))->save($savedata);

					M(self::T_MEMINFO)->where(array('ID'=>$model['UserID']))->setInc('Balance',$bonusmoney);
					//记录余额变动明细
					$CurrentBalance=M(self::T_MEMINFO)->where(array('ID'=>$model['UserID']))->getField('Balance');
					$Description=M('items')->where(array('GoodsNo'=>$model['GoodsNo']))->getField('Name');
					$Description.='申请分佣,申请记录id:'.$model['ID'];
					$dataintro=array(
						'Type'=>'0',
						'SruType'=>2,
						'oid'=>$v,
						'Amount'=>$bonusmoney,
						'CurrentBalance'=>$CurrentBalance,
						'Description'=>$Description,
						'Intro'=>$model['Mobile'],
						'UserID'=>$model['UserID'],
						'UpdateTime'=>date('Y-m-d H:i:s'),
						"TradeCode"=>date("YmdHis").rand(10000,99999),
						);
					M('mem_balances')->add($dataintro);
					$sv['Ymoney'] = $bonusmoney;
					M(self::T_TABLE)->where(array('ID'=>$result['ID']))->save($sv);
					$send = true;
				}
			}
			if($send){
				//短信和微信推送信息
				$Message = new \Extend\Message($model['ID'],2);
				$Message->sms();
				$num++;
			}
		}
		$this->ajaxReturn(1,'处理了'.$num.'条记录');
	}

    //匹配
    public function mateback(){
        set_time_limit(0);
        $id=I("post.ID",'','trim');
        if(!$id){
			$list = M(self::T_TABLE)->where('isfan = 0')->select();
			$num = 0;
			foreach($list as $k=>$fk_info){
				$GoodsNo = $fk_info['GoodsNo'];
				$Mobile = $fk_info['Mobile'];
				$TrueName = $fk_info['TrueName'];
				$nums = substr_count($TrueName,'*');
				$where = "";
				if($nums>0){
					$exTrueName = explode('*',$TrueName);
					for($i = 0;$i<count($exTrueName);$i++){
						if($exTrueName[$i]==''){
							continue;
						}
						$where = $where." and locate('".$exTrueName[$i]."',TrueName)";
					}
				}else{
					$where = " and TrueName = '".$TrueName."'";
				}
				$nums = substr_count($Mobile,'*');
				if($nums>0){
					$exMobile = explode('*',$Mobile);
					for($i = 0;$i<count($exMobile);$i++){
						if($exMobile[$i]==''){
							continue;
						}
						$where = $where." and locate('".$exMobile[$i]."',Mobile)";
					}
				}else{
					$where = " and Mobile = '".$Mobile."'";
				}

				$vvList = M(self::T_APPLYLIST)->query("select * from xb_apply_list where (Status=0 or applyStatus=0) and GoodsNo = '".$GoodsNo."'".$where);
				if(count($vvList)>0){
					$vv = $vvList[0];
					$data['Status'] = 1;
					$data['applyStatus'] = 1;
					$data['PipeiTime'] = date('Y-m-d H:i:s');
					M(self::T_APPLYLIST)->where(array('ID'=>$vv['ID']))->save($data);
					$savedata['applyListId'] = $vv['ID'];
					$savedata['isfan'] = 1;
					M(self::T_TABLE)->where(array('ID'=>$fk_info['ID']))->save($savedata);
					$num++;
				}
			}
			$this->ajaxReturn(1,'处理了'.$num.'条记录');
        }
        $idArr=explode(',',$id);
        //开始匹配返现
        $num=0;
        foreach($idArr as $k=>$v){
            $fk_info='';
            $fk_info=M(self::T_TABLE)->find($v);
			$GoodsNo = $fk_info['GoodsNo'];
			$Mobile = $fk_info['Mobile'];
			$TrueName = $fk_info['TrueName'];
			$nums = substr_count($TrueName,'*');
			$where = "";
			if($nums>0){
				$exTrueName = explode('*',$TrueName);
				for($i = 0;$i<count($exTrueName);$i++){
					if($exTrueName[$i]==''){
						continue;
					}
					$where = $where." and locate('".$exTrueName[$i]."',TrueName)";
				}
			}else{
				$where = " and TrueName = '".$TrueName."'";
			}
			$nums = substr_count($Mobile,'*');
			if($nums>0){
				$exMobile = explode('*',$Mobile);
				for($i = 0;$i<count($exMobile);$i++){
					if($exMobile[$i]==''){
						continue;
					}
					$where = $where." and locate('".$exMobile[$i]."',Mobile)";
				}
			}else{
				$where = " and Mobile = '".$Mobile."'";
			}

			$vvList = M(self::T_APPLYLIST)->query("select * from xb_apply_list where (Status=0 or applyStatus=0) and GoodsNo = '".$GoodsNo."'".$where);
			if(count($vvList)>0){
				$vv = $vvList[0];
				$data['Status'] = 1;
				$data['applyStatus'] = 1;
				$data['PipeiTime'] = date('Y-m-d H:i:s');
				M(self::T_APPLYLIST)->where(array('ID'=>$vv['ID']))->save($data);
				$savedata['applyListId'] = $vv['ID'];
				$savedata['isfan'] = 1;
				M(self::T_TABLE)->where(array('ID'=>$v))->save($savedata);
				$num++;
			}
        }
        $this->ajaxReturn(1,'处理了'.$num.'条记录');
        //开始匹配返现-------------------end
    }
    /**
     * 数据删除处理 单条或多条
     * @access   public
     * @param    string  $id   获取id组成的字符串
     * @return  返回处理结果
     */
    public function del(){
        //实例化分类表
        $mod = M(self::T_TABLE);
        //获取删除数据id (单条或数组)
        $id=I("post.ID",'','trim');
        if(!$id){
            $this->ajaxReturn(0,"请选择要删除的记录");
        }
        $data['ID'] = array('in',$id);
        //删除前校验
        $check=$mod->where(array('ID'=>array('in',$id),'Status'=>'1'))->select();
        if($check){
            $this->ajaxReturn(0,"匹配上的数据不能删除！");
        }
        $res = $mod->where($data)->save(array('IsDel'=>1));
        if($res){
            $this->ajaxReturn(1,"删除数据成功！");
        }else{
            $this->ajaxReturn(0,"删除数据时出错！");
        }
    }
    /**
     * 查看详情
     */
    public function Detail(){
        $ID=I('get.ID');
        if(!empty($ID)){
            $infos=M(self::T_TABLE)->where(array('ID'=>$ID))->find();
            $this->assign('infos',$infos);
        }
        $this->display();
    }

    /**
     *资金明细
     */
    public function BalanceDetail(){
        $oid=I('get.oid');
        if(!empty($oid)){
            //接收POST信息,拼接查询条件
            $page=I('post.page',1,'intval');
            $rows=I('post.rows',20,'intval');
            $sort='UpdateTime Desc';

            $where['oid']=$oid;
            //查询的列名
            $col='';
            //获取最原始的数据列表
            $query=new XBCommon\XBQuery();
            $array=$query->GetDataList(self::T_BALANCES,$where,$page,$rows,$sort,$col);

            //如果查询结果有些数据不需要输出，或者需要特殊处理，则进行重组后输出
            $result=array();
            if($array['rows']){
                foreach ($array['rows'] as $val){
                    
                    $val['Mobile']=$query->GetValue(self::T_MEMINFO,array('ID'=>(int)$val['UserID']),'Mobile');
                    if($val['Type']==0){
                        $val['Type']="收入";
                        switch ($val['SruType']){
                            case 1:$val['SruType']="推荐会员";
                                break;
                            case 2:$val['SruType']="借网贷";
                                break;
                            case 3:$val['SruType']="办信用卡";
                                break;
                            case 4:$val['SruType']="查征信";
                                break;
                        }
                    }
                    if($val['Type']==1){
                        $val['Type']="支出";
                        $val['SruType']="提现";
                        $val['Mtype']="";
                    }
                    $val['Amount']=number_format($val['Amount'],2).'元';
                    $val['CurrentBalance']=number_format($val['CurrentBalance'],2).'元';
                    $result['rows'][]=$val;
                }
                $result['total']=$array['total'];
            }
            $this->ajaxReturn($result);
        }
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
}