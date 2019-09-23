<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 叶程鹏
 * 修改时间: 2017/04/13 14:59
 * 功能说明: 贷款产品控制器
 */
namespace Home\Controller;
class ItemController extends HomeController{
	//找借贷 列表页面
    public function index(){
        $jkday=I('get.jkday','');
        $mtype=I('get.mtype','');
        $cateid=I('get.cateid','');
        $needid=I('get.needid','');
        //借款期限
        $jktimelist=M('items_jktimes')->field('ID,Name')->where(array('Status'=>'1','IsDel'=>'0'))->order("Sort asc")->select();
        //金额条件
        $moneylist=M('items_moneytype')->field('ID,Name')->where(array('Status'=>'1','IsDel'=>'0'))->order('Sort asc,ID desc')->select();
        //我有(分类,单选)
        $catelist=M('items_category')->field('ID,Name')->where(array('Status'=>'1','IsDel'=>'0'))->order('Sort asc,ID desc')->select();
        //我需要(所需材料 多选)
        $needlist=M('items_needs')->field('ID,Name')->where(array('Status'=>'1','IsDel'=>'0'))->order('Sort asc,ID desc')->select();
    	$this->assign(array(
            'jkday'=>$jkday,
            'mtype'=>$mtype,
            'cateid'=>$cateid,
            'needid'=>$needid,
            'jktimelist'=>$jktimelist,
            'moneylist'=>$moneylist,
            'catelist'=>$catelist,
            'needlist'=>$needlist,
    		));
    	$this->display();
    }
    //找借贷 数据
    public function indexdata(){
        //搜索条件
        $jkday=I('post.jkday','');
        $mtype=I('post.mtype','');
        $cateid=I('post.cateid','');
        $needid=I('post.needid','');

    	$current_page = I('post.pages',0);//当前页数
        $per_numbs = 6;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $where=array();
        if($jkday){
            $where[]="find_in_set($jkday,a.JktimesID)";
            $jkday_name=M('items_jktimes')->where(array('ID'=>$jkday))->getField('Name');
        }
        if($mtype){
            $where[]="find_in_set($mtype,a.MoneytypeID)";
            $mtype_name=M('items_moneytype')->where(array('ID'=>$mtype))->getField('Name');
        }
        if($cateid){
            $where[]="find_in_set($cateid,a.CateID)";
        }
        if($needid){
            $where['a.NeedIDs']=array('in',$needid);
        }

        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }

        $where['a.Showtype']=array('in',array('1','3'));
        $where['a.Itype']=array('eq','1');
        $where['a.Status']=array('eq','1');
        $where['a.IsDel']=array('eq','0');
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
        if(empty($info)){
            $this->ajaxReturn(0);
        }else{
            foreach($info as $k=>&$v){
                if($v['AppNumbs']>=100000000){
                    $info[$k]['AppNumbs']=round($v['AppNumbs']/100000000,1)."亿";
                }elseif($v['AppNumbs']>=10000){
                    $info[$k]['AppNumbs']=round($v['AppNumbs']/10000,1)."万";
                }
                $v['Intro']=mb_substr($v['Intro'], 0,'18','utf-8');
                if($jkday){
                    $v['Jkdays']=$jkday_name;
                }
                if($mtype){
                    $v['eduname']=$mtype_name;
                }
            }
            $this->ajaxReturn(1,$info);
        }
    }
    //平台网贷详情
    public function detail(){
        $id=I('get.id','');
        $where=array();
        $where['a.ID']=array('eq',$id);
        $where['a.IsDel']=array('eq','0');
        $infos=M('items')->alias('a')
               ->field('a.ID,a.Name,a.Logurl,a.Intro,a.PassRate,a.AppNumbs,c.Name as Jkdays,a.DayfeeRate,a.Downconts,a.ConditIDs,a.NeedIDs,a.Openurl,b.Name as eduname')
               ->join('left join xb_items_moneytype b on a.MoneytypeID=b.ID')
               ->join('left join xb_items_jktimes c on a.JktimesID=c.ID')
               ->where($where)
               ->find();
        M('items')->where(array('ID'=>$id))->setInc('AppNumbs');
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
        $this->assign(array(
            'infos'=>$infos,
            'conditArr'=>$conditArr,
            'needArr'=>$needArr,
            ));
        $this->display();
    }
    /****************** 以下是信用卡贷款  ***********************************************************/
    /**
     * 信用卡中心页
     */
    public function credit(){
        $db=M('items');
        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }
        // 推荐银行卡
        $twhere=array();
        $twhere['IsRec']=array('eq','1');
        $twhere['IsHot']=array('eq','0');
        $twhere['Itype']=array('eq','2');
        $twhere['IsDel']=array('eq','0');
        $twhere['Status']=array('eq','1');
        $twhere['Showtype']=array('in',array('1','3'));
        if($areid){
            $twhere['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
        }else{
            $twhere['Isshow']=array('eq','1');
        }
        $referInfo=$db->field('ID,Name,Intro,Logurl')->where($twhere)->order('Sort asc,ID desc')->limit(3)->select();
        // 办卡银行
        $bankInfo=M('credit_bank')->field('ID,BankName,Logurl,Intro,Desc')->where(array('IsTui'=>1,'Status'=>1,'IsDel'=>0))->order('Sort asc,ID desc')->select();
        // 热门银行卡
        $hwhere=array();
        $hwhere['a.Itype']=array('eq','2');
        $hwhere['a.IsHot']=array('eq','1');
        $hwhere['a.IsRec']=array('eq','0');
        $hwhere['a.IsDel']=array('eq','0');
        $hwhere['a.Status']=array('eq','1');
        $hwhere['a.Showtype']=array('in',array('1','3'));
        if($areid){
            $hwhere['_string']="a.Isshow=1 OR (a.Isshow=2 AND find_in_set(".$areid.",a.Cityids))";
        }else{
            $hwhere['a.Isshow']=array('eq','1');
        }
        $Info=$db->alias('a')
            ->join('left join xb_credit_bank b on b.ID=a.BankID')
            ->where($hwhere)
            ->field('a.ID,a.Name,a.AppNumbs,a.Intro,a.Logurl,b.BankName')
            ->order('a.Sort asc,a.ID desc')
            ->limit(6)
            ->select();

        $this->assign(array(
            "referInfo"=>$referInfo,
            "bankInfo"=>$bankInfo,
            "Info"=>$Info,
        ));
        $this->display();
    }


	/**
     * 信用卡中心页
     */
    public function creditv(){
        $db=M('items');
        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }
        // 推荐银行卡
        $twhere=array();
        $twhere['IsRec']=array('eq','1');
        $twhere['IsHot']=array('eq','0');
        $twhere['Itype']=array('eq','2');
        $twhere['IsDel']=array('eq','0');
        $twhere['Status']=array('eq','1');
        $twhere['Showtype']=array('in',array('1','3'));
        if($areid){
            $twhere['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
        }else{
            $twhere['Isshow']=array('eq','1');
        }
        $referInfo=$db->field('ID,Name,Intro,Logurl')->where($twhere)->order('Sort asc,ID desc')->limit(3)->select();
        // 办卡银行
        $bankInfo=M('credit_bank')->field('ID,BankName,Logurl,Intro,Desc')->where(array('IsTui'=>1,'Status'=>1,'IsDel'=>0))->order('Sort asc,ID desc')->select();
        // 热门银行卡
        $hwhere=array();
        $hwhere['a.Itype']=array('eq','2');
        $hwhere['a.IsHot']=array('eq','1');
        $hwhere['a.IsRec']=array('eq','0');
        $hwhere['a.IsDel']=array('eq','0');
        $hwhere['a.Status']=array('eq','1');
        $hwhere['a.Showtype']=array('in',array('1','3'));
        if($areid){
            $hwhere['_string']="a.Isshow=1 OR (a.Isshow=2 AND find_in_set(".$areid.",a.Cityids))";
        }else{
            $hwhere['a.Isshow']=array('eq','1');
        }
        $Info=$db->alias('a')
            ->join('left join xb_credit_bank b on b.ID=a.BankID')
            ->where($hwhere)
            ->field('a.ID,a.Name,a.AppNumbs,a.Intro,a.Logurl,b.BankName')
            ->order('a.Sort asc,a.ID desc')
            ->limit(6)
            ->select();

        $this->assign(array(
            "referInfo"=>$referInfo,
            "bankInfo"=>$bankInfo,
            "Info"=>$Info,
        ));
        $this->display();
    }

    /**
     * 信用卡列表页
     */
    public function clist(){
        $BankID=I('get.BankID',0,'intval');
        $KatypeID=I('get.KatypeID',0,'intval');
        $Sbid=I('get.Sbid',0,'intval');
        $YearfeeID=I('get.YearfeeID',0,'intval');
        
        // 银行种类
        $bankList=M('credit_bank')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->select();
        // 卡种
        $cardList=M('credit_katype')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->select();
        //币种
        $subjectlist=M('credit_bitype')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->select();
        //年费
        $feeList=M('credit_yearfee')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->select();

        $this->assign(array(
            "BankID"      =>$BankID,
            "KatypeID"    =>$KatypeID,
            "Sbid"    =>$Sbid,
            'YearfeeID' => $YearfeeID,
            "bankList"    =>$bankList,
            "cardList"    =>$cardList,
            "subjectlist"     =>$subjectlist,
            'feeList' => $feeList
        ));
        $this->display();
    }

    /**
     * 信用卡列表页加载数据
     */
    public function clistdata(){
        //搜索条件
        $BankID=I('post.BankID',0,'intval');//银行种类
        $KatypeID=I('post.KatypeID',0,'intval');//卡种
        $Sbid=I('post.Sbid',0,'intval');//币种
        $YearfeeID=I('post.YearfeeID',0,'intval');//年费

        $current_page = I('post.pages',0);//当前页数
        $per_numbs = 10;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }

        if($BankID){
            $where['a.BankID']=$BankID;
        }
        if($KatypeID){
            $where['a.KatypeID']=$KatypeID;
        }
        if($Sbid){
            $where['a.BitypeID']=$Sbid;
        }
        if ($YearfeeID){
            $where['a.YearfeeID']=$YearfeeID;
        }
        $where['a.Itype']=array('eq','2');
        $where['a.Showtype']=array('in',array('1','3'));
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
            ->field('a.ID,a.Name,a.AppNumbs,a.Intro,a.Logurl,b.BankName')
            ->where($where)
            ->limit($start_numbs,$per_numbs)
            ->order('a.Sort asc,a.ID desc')
            ->select();
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

    /**
     * 信用卡网贷详情
     */
    public function cdetail(){
        $id=I('get.id');
        $info=M('items')->alias('a')
              ->field('a.MainPic,a.Quanyconts,b.Name as yarfeename,a.Yeardesc,a.Jifen1,a.Jifen2,a.Freetime,a.Freedesc,a.Levelname,a.Fakaurl,a.Openurl')
              ->join('left join xb_credit_yearfee b on a.YearfeeID=b.ID')
              ->where(array('a.ID'=>$id,'a.IsDel'=>'0'))
              ->find();
        M('items')->where(array('ID'=>$id))->setInc('AppNumbs');
        if($info['Quanyconts']){
            $info['Quanyconts']=unserialize($info['Quanyconts']);
        }
        $this->assign(array(
            'info'=>$info,
            ));
        $this->display();
    }
}
