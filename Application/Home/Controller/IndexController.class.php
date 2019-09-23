<?php
/**
 * 版权所有: 青岛银狐信息科技有限公司
 * 公司电话: 0532-58770968
 * 作    者: 王少武
 * 修改时间: 2017/04/12 14:59
 * 功能说明: 首页控制器
 */

namespace Home\Controller;

class IndexController extends HomeController{

    public function index(){
        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }
        //banner
        $banner=M('sys_adcontent')->where(array('AdvertisingID'=>1,'Status'=>1))->order('Sort ASC,ID DESC')->limit(3)->select();
        //系统公告
        $aboutUs=M('sys_contentmanagement')->field('ID,Title')->where(array('CategoriesID'=>2,'IsDel'=>0))->order('Sort asc,ID desc')->limit(3)->select();
        //贷款类型
        $cate=M('items_category')->field('ID,Name,Imageurl')->where(array('ParentID'=>0,'IsDel'=>0,'IsRec'=>'1','Status'=>'1'))->order('Sort asc,ID desc')->limit(8)->select();
        //推荐产品 4个 平台网贷
        $twhere=array();
        $twhere['Itype']=array('eq','1');
        $twhere['IsRec']=array('eq','1');
        $twhere['IsDel']=array('eq','0');
        $twhere['Status']=array('eq','1');
        $twhere['Showtype']=array('in',array('1','3'));
        if($areid){
            $twhere['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
        }else{
            $twhere['Isshow']=array('eq','1');
        }
        $tjitems=M('items')->field('ID,Name,Logurl,AppNumbs')->where($twhere)->order('Sort asc,ID desc')->limit('4')->select();
        //热门借贷 5个 平台网贷
        $hwhere=array();
        $hwhere['a.Itype']=array('eq','1');
        $hwhere['a.IsHot']=array('eq','1');
        $hwhere['a.IsDel']=array('eq','0');
        $hwhere['a.Status']=array('eq','1');
        $hwhere['a.Showtype']=array('in',array('1','3'));
        if($areid){
            $hwhere['_string']="a.Isshow=1 OR (a.Isshow=2 AND find_in_set(".$areid.",a.Cityids))";
        }else{
            $hwhere['a.Isshow']=array('eq','1');
        }
        $hotitem=M('items')->alias('a')
                 ->field('a.ID,a.Name,a.Logurl,a.Intro,a.AppNumbs,c.Name as Jkdays,a.DayfeeRate,b.Name as eduname')
                 ->join('left join xb_items_moneytype b on a.MoneytypeID=b.ID')
                 ->join('left join xb_items_jktimes c on a.JktimesID=c.ID')
                 ->where($hwhere)
                 ->order('a.Sort asc,a.ID desc')->limit(20)->select();
        if ($hotitem){
            foreach ($hotitem as $key=>$val){
                if (mb_strlen($val['Intro'])>15){
                    $hotitem[$key]['Intro'] = mb_substr($val['Intro'],0,15).'...';
                }
            }
        }
        $noread=false;//未读标识
        if(session('loginfo')['UserID']){
            $retarr=ishavenoread(session('loginfo')['UserID']);
            if($retarr['xtmsg'] || $retarr['tzmsg']){
                $noread=true;
            }
        }
        $this->assign(array(
            "banner"=>$banner,
            "aboutUs"=>$aboutUs,
            "cate"=>$cate,
            'tjitems'=>$tjitems,
            'hotitem'=>$hotitem,
            'noread'=>$noread,
            'cityinfo'=>$cityinfo,
        ));
        $this->display();
    }
    //获取城市名称
    public function getcityname(){
        if(IS_POST){
            $cid=I('post.cid','');//行政编号
            $name=M('sys_areas')->where(array('Code'=>$cid))->getField('Name');
            if($name){
                $this->ajaxReturn(1,'获取成功!',$name);
            }else{
                $this->ajaxReturn(0,'城市获取失败!');
            }
        }else{
            $this->ajaxReturn(0,'请求方式不正确!');
        }
    }
    //-------------------------贷款分销部分----------------
    //佣金产品(贷款产品)
    public function share(){
        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }
        //广告位
        $adds=M('sys_adcontent')->field('Name,Pic,Url')->where(array('AdvertisingID'=>'5','Status'=>'1'))->order('Sort asc,ID Desc')->select();
        //新品推荐
        $twhere=array();
        $twhere['Itype']=array('eq','1');
        $twhere['IsRec']=array('eq','1');
        $twhere['IsDel']=array('eq','0');
        $twhere['Status']=array('eq','1');
        $twhere['Showtype']=array('in',array('2','3'));
        if($areid){
            $twhere['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
        }else{
            $twhere['Isshow']=array('eq','1');
        }
        $tjlist=M('items')->field('ID,Name,Logurl,Intro,Yjtype,BonusRate1,BonusRate2,BonusRate3,BonusRate4,Ymoney1,Ymoney2,Ymoney3,Ymoney4')->where($twhere)->order('Sort asc,ID desc')->limit(4)->select();

        //产品列表
        // $lwhere=array();
        // $lwhere['Itype']=array('eq','1');
        // $lwhere['IsRec']=array('eq','0');
        // $lwhere['IsDel']=array('eq','0');
        // $lwhere['Status']=array('eq','1');
        // $lwhere['Showtype']=array('in',array('2','3'));
        // if($areid){
        //     $lwhere['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
        // }else{
        //     $lwhere['Isshow']=array('eq','1');
        // }
        // $lists=M('items')->field('ID,Name,Logurl,Yjtype,BonusRate1,BonusRate2,BonusRate3,BonusRate4,Ymoney1,Ymoney2,Ymoney3,Ymoney4')->where($lwhere)->order('Sort asc')->limit(4)->select();
        $Mtypes='4';//默认显示是最高的
        if(session('loginfo')['UserID']){
            $Mtypes=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->getField('Mtype');
        }
        
        $this->assign(array(
            'adds'=>$adds,
            'tjlist'=>$tjlist,
            'Mtypes'=>$Mtypes,
            ));
        $this->display();
    }
    //佣金产品(信用卡产品)
    public function sharexy(){
        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }
        //广告位
        $adds=M('sys_adcontent')->field('Name,Pic,Url')->where(array('AdvertisingID'=>'5','Status'=>'1'))->order('Sort asc,ID desc')->select();
        //新品推荐
        $twhere=array();
        $twhere['Itype']=array('eq','2');
        $twhere['IsRec']=array('eq','1');
        $twhere['IsDel']=array('eq','0');
        $twhere['Status']=array('eq','1');
        $twhere['Showtype']=array('in',array('2','3'));
        if($areid){
            $twhere['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
        }else{
            $twhere['Isshow']=array('eq','1');
        }
        $tjlist=M('items')->field('ID,Name,Logurl,Intro,Yjtype,BonusRate1,BonusRate2,BonusRate3,BonusRate4,Ymoney1,Ymoney2,Ymoney3,Ymoney4')->where($twhere)->order('Sort asc,ID desc')->limit(4)->select();

        $Mtypes='4';//默认显示是最高的
        if(session('loginfo')['UserID']){
            $Mtypes=M('mem_info')->where(array('ID'=>session('loginfo')['UserID']))->getField('Mtype');
        }
        $this->assign(array(
            'adds'=>$adds,
            'tjlist'=>$tjlist,
            'Mtypes'=>$Mtypes,
            ));
        $this->display();
    }
    //客户列表
    public function khlist(){
        if(!session('loginfo')['UserID']){
            $http='/Login/index?back='.$_SERVER['REQUEST_URI'];
            redirect($http);
        }
        $dates=I('get.dates','');
        if(!$dates){
            $dates=date('Y-m',strtotime('-1 month'));
        }
        $dateArr=explode('-',$dates);
        //统计当月申请个数
        $where["date_format(Addtime,'%Y-%m')"]=array('eq',$dates);
        $where['UserID']=array('eq',session('loginfo')['UserID']);
        $applycount=M('apply_list')->where($where)->count();
        $this->assign(array(
            'dates'=>$dates,
            'dateArr'=>$dateArr,
            'applycount'=>$applycount,
            ));
        $this->display();
    }
    //获取数据
    public function getkhdata(){
        $dates=I('post.dates','');
        $current_page = I('post.pages',0);//当前页数
        $per_numbs = 10;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $where=array();
        //分页
        $model=M('items');
        $info=$model->field('ID,Itype,Name,GoodsNo')->where(array('Status'=>'1','IsDel'=>'0'))->limit($start_numbs,$per_numbs)->order('Sort asc,ID desc')->select();
        //统计处理
        if($info){
            foreach($info as $k=>$v){
                //申请位数 
                $where2=array();
                $where2['ItemID']=array('eq',$v['ID']);
                $where2["date_format(Addtime,'%Y-%m')"]=array('eq',$dates);
                $where2['IsDel']=array('eq','0');
                $where2['UserID']=array('eq',session('loginfo')['UserID']);
                $info[$k]['applycount']=M('apply_list')->where($where2)->count();
                //放款成功
                $where3=array();
                $where3['b.ItemID']=array('eq',$v['ID']);
                $where3['b.UserID']=array('eq',session('loginfo')['UserID']);
                $where3["date_format(b.Addtime,'%Y-%m')"]=array('eq',$dates);
                $info[$k]['fksuccess']=M('apply_listresult')->alias('a')
                                        ->join('left join xb_apply_list b on a.ListID=b.ID')
                                        ->where($where3)->count();
                //奖金
                $info[$k]['BonusAll']=M('apply_listresult')->alias('a')
                                        ->join('left join xb_apply_list b on a.ListID=b.ID')
                                        ->where($where3)->SUM('a.Bonus'); 
                if(!$info[$k]['BonusAll']){
                    $info[$k]['BonusAll']='0';
                }                     
            }
        }
        if(empty($info)){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn(1,$info);
        }
    }
    //贷款分销佣金产品更多 ----这个页面废弃
    public function sharelist(){
        $this->display();
    }
    //获取贷款分销佣金产品数据
    public function getsharedata(){
        //搜索条件
        $Itype=I('post.Itype','');

        $current_page = I('post.pages',0);//当前页数
        $per_numbs = 4;//每页显示条数
        $start_numbs=($current_page)*$per_numbs;//从哪条开始获取

        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }

        $where=array();
        if($Itype){
            $where['Itype']=array('eq',$Itype);
        }
        
        $where['Showtype']=array('in',array('2','3'));
        $where['IsDel']=array('eq','0');
        $where['Status']=array('eq','1');
        if($areid){
            $where['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
        }else{
            $where['Isshow']=array('eq','1');
        }
        //分页
        $model=M('items');
        $info=$model->field('ID,Name,Yjtype,Logurl,Intro,AppNumbs,BonusRate1,BonusRate2,BonusRate3,BonusRate4,Ymoney1,Ymoney2,Ymoney3,Ymoney4')->where($where)->limit($start_numbs,$per_numbs)->order('Sort asc,ID desc')->select();
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
    //征信详情页面(给app调用)
    public function appzdetail(){
        $id=I('get.id','');
        $infos=M('zenxin_list')->where(array('ID'=>$id,'IsDel'=>'0'))->find();
        if($infos['LoanInfos']){
            $infos['LoanInfos']=unserialize($infos['LoanInfos']);
            $infos['LoanInfos']=json_encode($infos['LoanInfos']);
            $infos['LoanInfos']=json_decode($infos['LoanInfos'],true);
        }
        $this->assign(array(
            'infos'=>$infos,
            ));
        $this->display();
    }

    /**
     * 关闭大弹窗，只弹一次
     */
    public function close(){
        session('close',array('isclose'=>1));
    }
}
