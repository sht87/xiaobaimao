<?php
/**
 * 版权所有：青岛银狐信息科技有限公司
 * 联系方式：18363857597
 * 作    者：李志修
 * 修改时间: 2018-05-18 16:50
 * 功能说明: 推广渠道管理控制器
 */
 namespace Admin\Controller\Operation;
 
 use Admin\Controller\System\BaseController;
 use XBCommon;

 class TuiguangtjController extends BaseController{

     const T_TABLE='tg_admin';
     const T_MEMINFO='mem_info';
     const T_ADMIN='sys_administrator';

     public function index(){
         $userId=M(self::T_ADMIN)->where(array('UserName'=>$_SESSION['AdminInfo']['Admin'],'IsDel'=>0))->getField('ID');
         $adminlist =M('user_channel')->alias('a')->field('b.ID,b.Name')->join('left join xb_tg_admin b on a.channelId=b.ID')->where(array('userId'=>$userId))->select();	
         $this->assign(array(
            'adminlist'=>$adminlist,
			'RoleID' =>$_SESSION['AdminInfo']['RoleID'],
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
         $start=($page-1)*$rows;//开始截取位置
         //搜索条件
         $StartTime=I('post.StartTime','');
         $EndTime=I('post.EndTime','');
         $TgadminID=I('post.TgadminID',-5);
		 $userId=M(self::T_ADMIN)->where(array('UserName'=>$_SESSION['AdminInfo']['Admin'],'IsDel'=>0))->getField('ID');
         $adminv =M('user_channel')->alias('a')->field('b.ID')->join('left join xb_tg_admin b on a.channelId=b.ID')->where(array('userId'=>$userId))->select();
		 $adminlist = array();
		 $where['enable']=1;
		 for($j=0;$j<count($adminv);$j++){
			array_push($adminlist,$adminv[$j]['ID']);
		 }
         if($TgadminID!=-5){//全部
            $where['channelId']=$TgadminID;
         }else{
			$where['channelId']=array('in',$adminlist);
		 }
         //变更时间
        $StartTime=I('post.StartTime');  //按时间查询
        $EndTime=I('post.EndTime');
        $ToStartTime= $StartTime;
        $ToEndTime= $EndTime;
        if($StartTime!=null){
            if($EndTime!=null){
                //有开始时间和结束时间
                $where['createDate']=array('between',$ToStartTime.','.$ToEndTime);
            }else{
                //只有开始时间
                $where['createDate']=array('egt',$ToStartTime);
            }
        }else{
            //只有结束时间
            if($EndTime!=null){
                $where['createDate']=array('elt',$ToEndTime);
            }
        }
		$ffwh = $where;
         $col='distinct createDate';//默认全字段查询
         //获取主表的数据
         $query=new XBCommon\XBQuery;
         $array=$query->GetDataList('tg_form',$where,$page,$rows,'createDate desc',$col);
         $result=array();
		 $rows = $array['rows'];
		 $result['rows'] = array();
		for($i=0;$i<count($rows);$i++){
			$val = &$rows[$i];
			if($TgadminID!=-5){
				$val['num'] = M('tg_form')->where(array('createDate'=>$val['createDate'],'channelId'=>$TgadminID))->sum('num');
				$val['android'] = M('tg_form')->where(array('createDate'=>$val['createDate'],'channelId'=>$TgadminID))->sum('android');
				$val['ios'] = M('tg_form')->where(array('createDate'=>$val['createDate'],'channelId'=>$TgadminID))->sum('ios');
				$val['UV'] = M('channel_statistics')->where(array('channelId'=>$TgadminID,'createDate'=>$val['createDate']))->sum('UV');
			}
			else{
				$ff = array();
				$ff['channelId']=array('in',$adminlist);
				$val['num'] = M('tg_form')->where(array('createDate'=>$val['createDate']))->where($ff)->sum('num');
				$val['android'] = M('tg_form')->where(array('createDate'=>$val['createDate']))->where($ff)->sum('android');
				$val['ios'] = M('tg_form')->where(array('createDate'=>$val['createDate']))->where($ff)->sum('ios');
				$val['UV'] = M('channel_statistics')->where(array('createDate'=>$val['createDate']))->where($ff)->sum('UV');

			}
			//申请单数
			if($_SESSION['AdminInfo']['RoleID'] ==9){
				if($TgadminID==-5){
					$ffbb['ID'] = array('in',$adminlist);
					$tgA = M(self::T_TABLE)->where($ffbb)->select();
					$allo = 0;
					for($f=0;$f<count($tgA);$f++){
						$tg = $tgA[$f];
						$openFree = $tg['openFree'];
						$freekt = $tg['freekt'];
						$startNum = $tg['startNum'];
						$wheref = array('createDate'=>$val['createDate']);
						$wheref['channelId'] = $tg['ID'];
						$num = M('tg_form')->where($wheref)->sum('num');
						if($openFree==1&&$num>$startNum){
							$reduce = $num - $startNum;
							$reduce = intval($reduce*($freekt/100));
							$num = $num - $reduce;
						}
						$allo = $allo + $num;
					}
					$val['num'] = $allo;
					$ffdd['channelId'] = array('in',$adminlist);
					$val['UV'] = M('channel_statistics')->where(array('createDate'=>$val['createDate']))->where($ffdd)->sum('UV');
				}
				else{
					$tg = M(self::T_TABLE)->find($TgadminID);
					$openFree = $tg['openFree'];
					$freekt = $tg['freekt'];
					$startNum = $tg['startNum'];
					$num = M('tg_form')->where(array('channelId'=>$TgadminID,'createDate'=>$val['createDate']))->sum('num');
					if($openFree==1&&$num>$startNum){
						$reduce = $num - $startNum;
						$reduce = intval($reduce*($freekt/100));
						$num = $num - $reduce;
					}
					if(!$num){
						$num = 0;
					}
					$val['UV'] = M('channel_statistics')->where(array('channelId'=>$TgadminID,'createDate'=>$val['createDate']))->sum('UV');
					$val['num'] = $num;
				}
				
			}
			$result['rows'][]=$val;
		}
		 
		if($_SESSION['AdminInfo']['RoleID'] ==9){
			$array=$query->GetDataList('tg_form',$where,1,1000,'createDate desc',$col);
			$rows1 = $array['rows'];
			if($TgadminID==-5){
				$insertArray=array();//统计数组
				$insertArray['createDate']='合计';
				$all = 0;
				for($nf=0;$nf<count($rows1);$nf++){
					$fv = $rows1[$nf];
					$ffbb['ID'] = array('in',$adminlist);
					$tgA = M(self::T_TABLE)->where($ffbb)->select();
					for($f=0;$f<count($tgA);$f++){
						$tg = $tgA[$f];
						$openFree = $tg['openFree'];
						$freekt = $tg['freekt'];
						$startNum = $tg['startNum'];
						$where = array('channelId'=>$tg['ID']);
						$where['createDate'] = $fv['createDate'];
						$num = M('tg_form')->where($where)->sum('num');
						if($openFree==1&&$num>$startNum){
							$reduce = $num - $startNum;
							$reduce = intval($reduce*($freekt/100));
							$num = $num - $reduce;
						}
						$all = $all + $num;
					}
				}
				$insertArray['num'] = $all;
				$insertArray['UV'] = M('channel_statistics')->where($ffwh)->sum('UV');
				array_push($result['rows'],$insertArray);
			}
			else{
				$tg = M(self::T_TABLE)->find($TgadminID);
				$openFree = $tg['openFree'];
				$freekt = $tg['freekt'];
				$startNum = $tg['startNum'];
				$insertArray=array();//统计数组
				$insertArray['createDate']='合计';
				$ffnum = 0;
				for($nf=0;$nf<count($rows1);$nf++){
					$fv = $rows1[$nf];
					$num = M('tg_form')->where(array('channelId'=>$TgadminID,'createDate'=>$fv['createDate']))->sum('num');
					if($openFree==1&&$num>$startNum){
						$reduce = $num - $startNum;
						$reduce = intval($reduce*($freekt/100));
						$num = $num - $reduce;
					}
					if(!$num){
						$num = 0;
					}
					$ffnum = $ffnum + $num;
				}
				$insertArray['UV'] = M('channel_statistics')->where($ffwh)->sum('UV');
				$insertArray['num'] = $ffnum;

				array_push($result['rows'],$insertArray);
			}
			
			
		}
		else{
			$insertArray=array();//统计数组
			$insertArray['createDate']='合计';
			$insertArray['ios']=M('tg_form')->where($where)->sum('ios');
			$insertArray['android']=M('tg_form')->where($where)->sum('android');
			$insertArray['num']=M('tg_form')->where($where)->sum('num');
			$insertArray['UV'] = M('channel_statistics')->where($ffwh)->sum('UV');
			array_push($result['rows'],$insertArray);
		}
         $this->ajaxReturn($result);
     }
	 public function exportexcel(){
        //查出相应信息
         $page=I('post.page',1,'intval');
         $rows=I('post.rows',20,'intval');
         $start=($page-1)*$rows;//开始截取位置
         //搜索条件
         $StartTime=I('post.StartTime','');
         $EndTime=I('post.EndTime','');
         $TgadminID=I('post.TgadminID',-5);
		 $userId=M(self::T_ADMIN)->where(array('UserName'=>$_SESSION['AdminInfo']['Admin'],'IsDel'=>0))->getField('ID');
         $adminv =M('user_channel')->alias('a')->field('b.ID')->join('left join xb_tg_admin b on a.channelId=b.ID')->where(array('userId'=>$userId))->select();
		 $adminlist = array();
		 $where['enable']=1;
		 for($j=0;$j<count($adminv);$j++){
			array_push($adminlist,$adminv[$j]['ID']);
		 }
         if($TgadminID!=-5){//全部
            $where['channelId']=$TgadminID;
         }else{
			$where['channelId']=array('in',$adminlist);
		 }
         //变更时间
        $StartTime=I('post.StartTime');  //按时间查询
        $EndTime=I('post.EndTime');
        $ToStartTime= $StartTime;
        $ToEndTime= $EndTime;
        if($StartTime!=null){
            if($EndTime!=null){
                //有开始时间和结束时间
                $where['createDate']=array('between',$ToStartTime.','.$ToEndTime);
            }else{
                //只有开始时间
                $where['createDate']=array('egt',$ToStartTime);
            }
        }else{
            //只有结束时间
            if($EndTime!=null){
                $where['createDate']=array('elt',$ToEndTime);
            }
        }
         $col='distinct createDate';//默认全字段查询
         //获取主表的数据
         $query=new XBCommon\XBQuery;
         $array=$query->GetDataList('tg_form',$where,$page,$rows,'createDate desc',$col);
         $result=array();
		 $rows = $array['rows'];
		 $result['rows'] = array();
		for($i=0;$i<count($rows);$i++){
			$val = &$rows[$i];
			if($TgadminID!=-5){
				$val['num'] = M('tg_form')->where(array('createDate'=>$val['createDate'],'channelId'=>$TgadminID))->sum('num');
				$val['android'] = M('tg_form')->where(array('createDate'=>$val['createDate'],'channelId'=>$TgadminID))->sum('android');
				$val['ios'] = M('tg_form')->where(array('createDate'=>$val['createDate'],'channelId'=>$TgadminID))->sum('ios');
			}
			else{
				$ff = array();
				$ff['channelId']=array('in',$adminlist);
				$val['num'] = M('tg_form')->where(array('createDate'=>$val['createDate']))->where($ff)->sum('num');
				$val['android'] = M('tg_form')->where(array('createDate'=>$val['createDate']))->where($ff)->sum('android');
				$val['ios'] = M('tg_form')->where(array('createDate'=>$val['createDate']))->where($ff)->sum('ios');
			}
			//申请单数
			if($_SESSION['AdminInfo']['RoleID'] ==9){
				if($TgadminID==-5){
					$ffbb['ID'] = array('in',$adminlist);
					$tgA = M(self::T_TABLE)->where($ffbb)->select();
					$allo = 0;
					for($f=0;$f<count($tgA);$f++){
						$tg = $tgA[$f];
						$openFree = $tg['openFree'];
						$freekt = $tg['freekt'];
						$startNum = $tg['startNum'];
						$wheref = array('createDate'=>$val['createDate']);
						$wheref['channelId'] = $tg['ID'];
						$num = M('tg_form')->where($wheref)->sum('num');
						if($openFree==1&&$num>$startNum){
							$reduce = $num - $startNum;
							$reduce = intval($reduce*($freekt/100));
							$num = $num - $reduce;
						}
						$allo = $allo + $num;
					}
					$val['num'] = $allo;
				}
				else{
					$tg = M(self::T_TABLE)->find($TgadminID);
					$openFree = $tg['openFree'];
					$freekt = $tg['freekt'];
					$startNum = $tg['startNum'];
					$num = M('tg_form')->where(array('channelId'=>$TgadminID,'createDate'=>$val['createDate']))->sum('num');
					if($openFree==1&&$num>$startNum){
						$reduce = $num - $startNum;
						$reduce = intval($reduce*($freekt/100));
						$num = $num - $reduce;
					}
					if(!$num){
						$num = 0;
					}
					$val['num'] = $num;
				}
				
			}
			$result['rows'][]=$val;
		}
        //导出拼装
        $html = '<table cellpadding="1" cellspacing="1" border="1" width="100%" bgcolor="#000000;">
            <tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" _REQUEST>序号</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>日期</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>注册人数</td>
            </tr>';

        foreach($result['rows'] as $key=>$row){
            $html .= '<tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" >'.intval($key+1).'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['createDate'].'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['num'].'</td>
            </tr>';
        }

        $html .= '</table>';
        $str_filename = date('Y-m-d', time()).'推广统计';
        //$str_filename = iconv('UTF-8', 'GB2312//IGNORE',$str_filename);
        $html = iconv('UTF-8', 'GB2312//IGNORE',$html);
        header("Content-type: application/vnd.ms-excel; charset=GBK");
        header("Content-Disposition: attachment; filename=$str_filename.xls");
        echo $html;
        exit;
    }
     //会员导出功能
    public function exportexcel1(){
        //查出相应信息
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
         $StartTime=I('post.StartTime','');
         $EndTime=I('post.EndTime','');
         $TgadminID=I('post.TgadminID',-5);

         //判断是不是推广渠道会员
         $tdmemid=M(self::T_TABLE)->where(array('UserName'=>$_SESSION['AdminInfo']['Admin']))->getField('ID');
         if($tdmemid){
            $TgadminID=$tdmemid;
         }
         
         $startday='';
         $endday='';
         //算出开始和结束时间
         if(!$StartTime && !$EndTime){
            $endday=date('Y-m-d');
         }
         if($StartTime && ($StartTime>date('Y-m-d'))){
            $endday=date('Y-m-d');
         }elseif($StartTime){
            $startday=$StartTime;
         }

         if($EndTime){
            $endday=$EndTime;
         }

         $dataArr=array();//总数据记录,单位每天
         if($startday && $endday){
            $dataArr[]['Times']=$startday;
            for($i=1;true;$i++){
                $current='';
                $current=date('Y-m-d',strtotime("+".$i."day",strtotime($startday)));
                $dataArr[]['Times']=$current;
                if($current>=$endday){
                    break;
                }
            }
            //排序 按时间降序
            $dataArr=$this->arraySequence($dataArr, 'Times', $sort = 'SORT_DESC');
         }elseif($endday){
            $dataArr[]['Times']=$endday;
            //向后取31天
            for($i=1;true;$i++){
                $current='';
                $current=date('Y-m-d',strtotime("-".$i."day",strtotime($endday)));
                $dataArr[]['Times']=$current;
                if($i>=30){
                    break;
                }
            }
         }
         //数据分页
         $array='';
         $array=$dataArr;
         $result=array();
         if($array){
            foreach ($array as $val) {
                //注册人数
                $rwhere=array();
                if($TgadminID!=-5){
                    $rwhere['TgadminID']=array('eq',$TgadminID);
                }
                $rwhere['IsDel']=array('eq','0');
                $rwhere['RegTime']=array('between',$val['Times'].' 00:00:00'.','.$val['Times'].' 23:59:59');
                $val['Regists']=M(self::T_MEMINFO)->where($rwhere)->count();
                $result[]=$val;
            }
         }

        $data['rows']=$result;
        //导出拼装
        $html = '<table cellpadding="1" cellspacing="1" border="1" width="100%" bgcolor="#000000;">
            <tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" _REQUEST>序号</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>日期</td>
                <td bgcolor="#FFFFFF" align="center" _REQUEST>注册人数</td>
            </tr>';

        foreach($data['rows'] as $key=>$row){
            $html .= '<tr bgcolor="#FFFFFF">
                <td bgcolor="#FFFFFF" align="center" >'.intval($key+1).'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['Times'].'</td>
                <td bgcolor="#FFFFFF" align="center" >'.$row['Regists'].'</td>
            </tr>';
        }

        $html .= '</table>';
        $str_filename = date('Y-m-d', time()).'推广统计';
        //$str_filename = iconv('UTF-8', 'GB2312//IGNORE',$str_filename);
        $html = iconv('UTF-8', 'GB2312//IGNORE',$html);
        header("Content-type: application/vnd.ms-excel; charset=GBK");
        header("Content-Disposition: attachment; filename=$str_filename.xls");
        echo $html;
        exit;
    }
     /**
     * 二维数组根据字段进行排序
     * @params array $array 需要排序的数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
     public function arraySequence($array, $field, $sort = 'SORT_DESC'){
         $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
     }
 }
