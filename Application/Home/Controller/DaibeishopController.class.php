<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 叶程鹏
 * 修改时间: 2017/04/13 14:59
 * 功能说明: 待备店控制器
 */
namespace Home\Controller;
class DaibeishopController extends HomeController{
	//秒办卡 列表页面
	public function index(){
		$uid=I('get.uid','');//推荐人的会员id

		$cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }
        $twhere=array();
        $twhere['IsRec']=array('eq','1');
        $twhere['IsHot']=array('eq','0');
        $twhere['Itype']=array('eq','2');
        $twhere['IsDel']=array('eq','0');
        $twhere['Status']=array('eq','1');
        $twhere['Showtype']=array('in',array('2','3'));
        if($areid){
            $twhere['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
        }else{
            $twhere['Isshow']=array('eq','1');
        }
		//三个推荐的
		$referInfo=M('items')->field('ID,Itype,Name,Logurl,Intro')->where($twhere)->order('Sort asc,ID desc')->limit(3)->select();
		//通知公告 10个
		$addinfos=M('apply_listresult')->field('ID,Name,TrueName,Opentime')->where(array('Itype'=>'2','IsDel'=>'0'))->limit(10)->order('ID desc')->select();
		$this->assign(array(
			'uid'=>$uid,
			'referInfo'=>$referInfo,
			'addinfos'=>$addinfos,
			));
		$this->display();
	}
	//秒办卡 获取数据
	public function getdatas(){
        $current_page = I('post.pages',0);//当前页数
        $per_numbs = 10;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }

        $where=array();
        $where['a.Itype']=array('eq','2');
        $where['a.Showtype']=array('in',array('2','3'));
        $where['a.IsDel']=array('eq','0');
        $where['a.Status']=array('eq','1');
        if($areid){
            $where['_string']="a.Isshow=1 OR (a.Isshow=2 AND find_in_set(".$areid.",a.Cityids))";
        }else{
            $where['a.Isshow']=array('eq','1');
        }
        //分页
        $model=M('items');
        $info=$model->alias('a')
              ->join('left join xb_credit_bank b on a.BankID=b.ID')
              ->field('a.ID,a.Name,a.GoodsNo,a.Logurl,a.AppNumbs,b.BankName')
              ->where($where)
              ->limit($start_numbs,$per_numbs)
              ->order('a.Sort desc,a.ID desc')->select();
        foreach ($info as $k=>$v){
            if($v['AppNumbs']>=100000000){
                $info[$k]['AppNumbs']=round($v['AppNumbs']/100000000,1)."亿";
            }elseif($v['AppNumbs']>=10000){
                $info[$k]['AppNumbs']=round($v['AppNumbs']/10000,1)."万";
            }
        }
        if(empty($info)){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn(1,$info);
        }
	}
	//信用卡网贷详情
	public function cdetail(){
		$uid=I('get.uid','');
		$id=I('get.id');
        $info=M('items')->alias('a')
              ->field('a.ID,a.MainPic,a.Quanyconts,b.Name as yarfeename,a.Yeardesc,a.Jifen1,a.Jifen2,a.Freetime,a.Freedesc,a.Levelname,a.Fakaurl,a.Openurl')
              ->join('left join xb_credit_yearfee b on a.YearfeeID=b.ID')
              ->where(array('a.ID'=>$id,'a.IsDel'=>'0'))
              ->find();
        if($info['Quanyconts']){
            $info['Quanyconts']=unserialize($info['Quanyconts']);
        }
        M('items')->where(array('ID'=>$id))->setInc('AppNumbs');
        $this->assign(array(
            'info'=>$info,
            'uid'=>$uid,
            ));
        $this->display();
	}
	//秒到账
	public function platweb(){
		$uid=I('get.uid','');//推荐人的会员id
		$this->assign(array(
			'uid'=>$uid,
			));
		$this->display();
	}
	//秒到账 数据获取
	public function platwebdata(){
    	$current_page = I('post.pages',0);//当前页数
        $per_numbs = 3;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }

        $where=array();
        $where['a.Itype']=array('eq','1');
        $where['a.Showtype']=array('in',array('2','3'));
        $where['a.IsDel']=array('eq','0');
        $where['a.Status']=array('eq','1');
        if($areid){
            $where['_string']="a.Isshow=1 OR (a.Isshow=2 AND find_in_set(".$areid.",a.Cityids))";
        }else{
            $where['a.Isshow']=array('eq','1');
        }
        //分页
        $model=M('items');
        $info=$model->alias('a')
                    ->join('left join xb_items_moneytype b on a.MoneytypeID=b.ID')
                    ->join('left join xb_items_jktimes c on a.JktimesID=c.ID')
                    ->where($where)
                    ->field('a.ID,a.Name,a.Logurl,a.Intro,a.AppNumbs,c.Name as Jkdays,a.DayfeeRate,b.Name as eduname')
                    ->limit($start_numbs,$per_numbs)->order('a.Sort asc,a.ID desc')->select();
        foreach ($info as $k=>$v){
            if($v['AppNumbs']>=100000000){
                $info[$k]['AppNumbs']=round($v['AppNumbs']/100000000,1)."亿";
            }elseif($v['AppNumbs']>=10000){
                $info[$k]['AppNumbs']=round($v['AppNumbs']/10000,1)."万";
            }
        }
        if(empty($info)){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn(1,$info);
        }
	}
	//平台网贷详情
	public function detail(){
		$uid=I('get.uid','');
		$id=I('get.id','');
        $where=array();
        $where['a.ID']=array('eq',$id);
        $where['a.IsDel']=array('eq','0');
        $infos=M('items')->alias('a')
               ->field('a.ID,a.Name,a.Logurl,a.Intro,a.AppNumbs,c.Name as Jkdays,a.DayfeeRate,a.Downconts,a.ConditIDs,a.NeedIDs,a.Openurl,b.Name as eduname')
               ->join('left join xb_items_moneytype b on a.MoneytypeID=b.ID')
               ->join('left join xb_items_jktimes c on a.JktimesID=c.ID')
               ->where($where)
               ->find();
        if($infos['Downconts']){
            $infos['Downconts']=unserialize($infos['Downconts']);
        }
        //申请条件
        $conditArr=array();
        if($infos['ConditIDs']){
            $result=M('items_conditions')->field('Name')->where(array('ID'=>array('in',$infos['ConditIDs'])))->select();
            foreach($result as $k=>$v){
                $conditArr[]=$v['Name'];
            }
            //$conditArr=array_column($result, 'Name');
        }
        //所需材料
        $needArr=array();
        if($infos['NeedIDs']){
            $result2=M('items_needs')->field('Name')->where(array('ID'=>array('in',$infos['NeedIDs'])))->select();
            foreach($result2 as $k=>$v){
                $needArr[]=$v['Name'];
            }
            //$needArr=array_column($result2, 'Name');
        }
        M('items')->where(array('ID'=>$id))->setInc('AppNumbs');
        $this->assign(array(
            'infos'=>$infos,
            'conditArr'=>$conditArr,
            'needArr'=>$needArr,
            'uid'=>$uid,
            ));
        $this->display();
	}
	//信用卡贷 和 平台网贷申请 身份校验
	public function checkinfo(){
		$uid=I('get.uid','');
		$id=I('get.id','');
		$infos=M('items')->field('ID,Itype,Name')->where(array('ID'=>$id,'IsDel'=>'0'))->find();
		$this->assign(array(
			'uid'=>$uid,
			'id'=>$id,
			'infos'=>$infos,
			));
		$this->display();
	}
	//信用卡贷 和 平台网贷申请 身份校验 
	public function ajaxcheckinfo(){
		$uid=I('post.uid');
		$id=I('post.id');
		$TrueName=I('post.TrueName');
		$Mobile=I('post.Mobile');
		$IDCard=I('post.IDCard');
		$agreeid=I('post.agreeid','');
		//校验
		if(!$id){
			$this->ajaxReturn(0,'身份校验失败!');
		}
		if(!$TrueName){
			$this->ajaxReturn(0,'请输入姓名!');
		}
		if(!$Mobile){
			$this->ajaxReturn(0,'请输入手机号码!');
		}
		if(!is_mobile($Mobile)){
			$this->ajaxReturn(0,'请输入正确的手机号码!');
		}
		if(!checkIdCard($IDCard)){
			$this->ajaxReturn(0,'请输入正确的身份证号码!');
		}
		if(!$agreeid){
			$this->ajaxReturn(0,'必须同意用户协议!');
		}
		$iteminfo=M('items')->where(array('ID'=>$id,'IsDel'=>'0','Status'=>'1'))->find();
		if(!$iteminfo){
			$this->ajaxReturn(0,'身份校验失败!');
		}
		//如果xb_apply_list已经有记录了就不在添加了
		$check_apply=M('apply_list')->where(array('GoodsNo'=>$iteminfo['GoodsNo'],'TrueName'=>$TrueName,'IDCard'=>$IDCard,'Mobile'=>$Mobile,'UserID'=>$uid))->find();
		if($check_apply){
			$this->ajaxReturn(1,'身份校验成功!',$iteminfo['Openurl']);
		}

		$meminfo=M('mem_info')->where(array('ID'=>$uid))->find();
		list($msec, $sec) = explode(' ', microtime());
		$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
		$data=array(
			'ItemID'=>$iteminfo['ID'],
			'GoodsNo'=>$iteminfo['GoodsNo'],
			'Itype'=>$iteminfo['Itype'],
			'TrueName'=>$TrueName,
			'IDCard'=>$IDCard,
			'Mobile'=>$Mobile,
			'OrderSn'=>$msectime.rand(1,9).rand(111,999),
			'UserID'=>$uid,
			'Addtime'=>date('Y-m-d H:i:s'),
			);
		if($iteminfo['Yjtype']=='1'){
			//按比例
			$BonusRate='';
			if($meminfo['Mtype']=='1'){
				$BonusRate=$iteminfo['BonusRate1'];
			}elseif($meminfo['Mtype']=='2'){
				$BonusRate=$iteminfo['BonusRate2'];
			}elseif($meminfo['Mtype']=='3'){
				$BonusRate=$iteminfo['BonusRate3'];
			}elseif($meminfo['Mtype']=='4'){
				$BonusRate=$iteminfo['BonusRate4'];
			}
			$data['BonusRate']=$BonusRate;
		}elseif($iteminfo['Yjtype']=='2'){
			//按金额
			$Ymoney='';
			if($meminfo['Mtype']=='1'){
				$Ymoney=$iteminfo['Ymoney1'];
			}elseif($meminfo['Mtype']=='2'){
				$Ymoney=$iteminfo['Ymoney2'];
			}elseif($meminfo['Mtype']=='3'){
				$Ymoney=$iteminfo['Ymoney3'];
			}elseif($meminfo['Mtype']=='4'){
				$Ymoney=$iteminfo['Ymoney4'];
			}
			$data['Ymoney']=$Ymoney;
		}
		$data['Yjtype']=$iteminfo['Yjtype'];
		//新增
		$applyBonus = 0;
        if($meminfo['Mtype']=='1'){
            $applyBonus=$iteminfo['Smoney1'];
        }elseif($meminfo['Mtype']=='2'){
            $applyBonus=$iteminfo['Smoney2'];
        }elseif($meminfo['Mtype']=='3'){
            $applyBonus=$iteminfo['Smoney3'];
        }elseif($meminfo['Mtype']=='4'){
            $applyBonus=$iteminfo['Smoney4'];
        }
        $data['applyBonus']=$applyBonus;

		$result=M('apply_list')->add($data);
		if($result){
			//短信和微信推送信息
            $Message = new \Extend\Message($result,1);
            $Message->sms();
            
			$this->ajaxReturn(1,'身份校验成功!',$iteminfo['Openurl']);
		}else{
			$this->ajaxReturn(0,'身份校验失败!');
		}
	}
	//用户协议
	public function agreeinfo(){
		$uid=I('get.uid','');
		$id=I('get.id','');
		$pages=M('sys_simplepage')->where(array('ID'=>7))->find();
        $this->assign(array(
            "pages"=>$pages,
            "uid"=>$uid,
            "id"=>$id,
            "title"=>$pages['Title'],
        ));
        $this->display();
	}
}
