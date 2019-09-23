<?php
// +----------------------------------------------------------------------
// | 版权所有: 青岛银狐信息科技有限公司
// +----------------------------------------------------------------------
// | 公司电话: 0532-58770968
// +----------------------------------------------------------------------
// | 功能说明: 
// +----------------------------------------------------------------------
// | Author: XB.ghost.42 / 吴
// +----------------------------------------------------------------------
// | Date: 2017/10/10
// +----------------------------------------------------------------------

namespace Api\Controller\Home;
use Api\Controller\Core\BaseController;

class ItemsController extends BaseController
{
    const T_BASIC    ='sys_basicinfo';
    const T_ITEMCATE ='items_category';
    const T_ITEMS    ='items';
    const T_AREAS    ='sys_areas';
    const T_MONEYTYPE='items_moneytype';
    const T_CONDITION='items_conditions';
    const T_JKTIMES='items_jktimes';
    const T_NEEDS    ='items_needs';
    const T_TRANSFER ='comp_transfer';
    const T_BANK     ='credit_bank';
    const T_KATYPE   ='credit_katype';
    const T_YEARFEE  ='credit_yearfee';
    const T_BITYPE   ='credit_bitype';


    /**
     * @功能说明: 获取 网贷平台 列表
     * @传输格式: get提交
     * @提交网址: /Home/Items/getitems
     * @提交信息：client=android&package=android.ceshi&ver=v1.1&page=0&rows=6&tid=1&cid=1&nids;
     *               page:页码数，默认从0页开始   rows:获取的列表数
     *               tid:金额类型ID    cid:贷款条件ID  nids:所需材料ID的集合
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getitems(){
        $itemModel=M(self::T_ITEMS);
        $para=I('get.');
        $page=$para['page']?$para['page']:0;
        $rows=$para['rows']?$para['rows']:6;

        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }

        if($para['jkday']){
            $jkday=$para['jkday'];
            $where[]="find_in_set($jkday,a.JktimesID)";
            $jkday_name=M('items_jktimes')->where(array('ID'=>$jkday))->getField('Name');
        }
        if($para['tid']){
            $mtype=$para['tid'];
            $where[]="find_in_set($mtype,a.MoneytypeID)";
            $mtype_name=M('items_moneytype')->where(array('ID'=>$mtype))->getField('Name');
        }
        if($para['cid']){
            $cateid=$para['cid'];
            $where[]="find_in_set($cateid,a.CateID)";
        }
        if($para['nids']){
            $where['a.NeedIDs']=array('in',explode(',',$para['nids']));
        }

        if($areid){
            $where['_string']="a.Isshow=1 OR (a.Isshow=2 AND find_in_set(".$areid.",a.Cityids))";
        }else{
            $where['a.Isshow']=array('eq','1');
        }
        
        $where['a.Itype']=1;
        $where['a.Showtype']=array('in',array('1','3'));
        $where['a.IsDel']=0;
        $where['a.Status']=array('eq','1');
        $field='a.ID,
                a.Name,
				a.Openurl,
				a.linkType,
                a.Logurl,
                a.AppNumbs,
                a.Intro,
                c.Name as Jkdays,
                a.DayfeeRate,
                b.Name as TypeName';

        $itemList=$itemModel->alias('a')
            ->join('left join xb_items_moneytype b on b.ID=a.MoneytypeID')
            ->join('left join xb_items_jktimes c on a.JktimesID=c.ID')
            ->field($field)
            ->where($where)
            ->order('a.Sort asc,a.ID desc')
            ->limit($page*$rows,$rows)
            ->select();

        if(empty($itemList)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，我是有底线的',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        foreach($itemList as $key=>$val){
            $itemList[$key]['Logurl'] = 'http://'.$_SERVER['HTTP_HOST'].$val['Logurl'];
            if($jkday){
                $itemList[$key]['Jkdays']=$jkday_name;
            }
            if($mtype){
                $itemList[$key]['eduname']=$mtype_name;
            }
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$itemList
        );
        exit(json_encode($data));
    }

    /**
     * @功能说明: 获取 我有的条件  列表
     * @传输格式: get提交
     * @提交网址: /Home/items/getcondition
     * @提交信息：client=android&package=android.ceshi&ver=v1.1
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getcate(){
        $condList=M(self::T_ITEMCATE)->field('ID,Name')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->select();
        if(empty($condList)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的贷款分类条件',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$condList
        );
        exit(json_encode($data));
    }

    /**
     * @功能说明: 获取 所需材料  列表
     * @传输格式: get提交
     * @提交网址: /Home/items/getneed
     * @提交信息：client=android&package=android.ceshi&ver=v1.1
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getneed(){
        $needList=M(self::T_NEEDS)->field('ID,Name')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->select();
        if(empty($needList)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的所需材料',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$needList
        );
        exit(json_encode($data));
    }
    /**
     * @功能说明: 获取 金额类型信息  列表
     * @传输格式: get提交
     * @提交网址: /Home/items/getmoneytype
     * @提交信息：client=android&package=android.ceshi&ver=v1.1
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getmoneytype(){
        $condList=M(self::T_MONEYTYPE)->field('ID,Name')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->select();
        if(empty($condList)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的贷款条件',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$condList
        );
        exit(json_encode($data));

    }
    /**
     * @功能说明: 获取 借款期限  列表
     * @传输格式: get提交
     * @提交网址: /Home/items/getjktimes
     * @提交信息：client=android&package=android.ceshi&ver=v1.1
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getjktimes(){
        $condList=M(self::T_JKTIMES)->field('ID,Name')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc')->select();
        if(empty($condList)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有数据',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$condList
        );
        exit(json_encode($data));
    }


    /**
     * @功能说明: 获取 网贷平台 详情
     * @传输格式: get提交
     * @提交网址: /Home/Items/getdetail
     * @提交信息：client=android&package=android.ceshi&ver=v1.1&id=1;
     *               ID:网贷平台ID
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getdetail(){
        $itemModel=M(self::T_ITEMS);
        $para=I('get.');
        $where['a.ID']=$para['id'];
        $where['a.Itype']=1;
        $field='a.Name,a.Logurl,a.AppNumbs,a.Intro,c.Name as Jkdays,a.DayfeeRate,a.PassRate,a.Downconts,a.ConditIDs,a.NeedIDs,a.Openurl,b.Name as TypeName';

        $itemInfo=$itemModel->alias('a')
            ->join('left join xb_items_moneytype b on b.ID=a.MoneytypeID')
            ->join('left join xb_items_jktimes c on a.JktimesID=c.ID')
            ->field($field)
            ->where($where)
            ->find();

        if(empty($itemInfo)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，传递的参数id有误',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        $itemInfo['Logurl'] = 'http://'.$_SERVER['HTTP_HOST'].$itemInfo['Logurl'];
        if($itemInfo['Downconts']){
            $itemInfo['Downconts']=unserialize($itemInfo['Downconts']);
        }else{
            $itemInfo['Downconts']=array("暂无下款攻略");
        }
        //申请条件
        $ConditIDs=M(self::T_CONDITION)->field('Name')->where(array('ID'=>array('in',explode(',',$itemInfo['ConditIDs']))))->select();
        $itemInfo['ConditIDs']=array_column($ConditIDs,'Name');

        //所需材料
        $NeedIDs=M(self::T_NEEDS)->field('Name')->where(array('ID'=>array('in',explode(',',$itemInfo['NeedIDs']))))->select();
        $itemInfo['NeedIDs']=array_column($NeedIDs,'Name');
        M('items')->where(array('ID'=>$para['id']))->setInc('AppNumbs');
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$itemInfo
        );
        exit(json_encode($data));
    }


    /** 以下是信用卡贷的有关接口**************************************************************/

    /**
     * @功能说明: 获取 信用卡中心页推荐卡和热门卡  列表
     * @传输格式: get提交
     * @提交网址: /Home/Items/getcredit
     * @提交信息：client=android&package=android.ceshi&ver=v1.1&isrec=1&rows=6;
     *              isrec:1推荐卡 0热门卡   rows:展示的列表数，默认值为6
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getcredit(){
        $para=I('get.');
        $rows=$para['rows']?$para['rows']:6;
        $itemModel=M(self::T_ITEMS);

        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }

        if($para['isrec']==1){
           $where=array('a.Itype'=>2,'a.IsRec'=>1,'a.IsDel'=>0);
            $text='推荐银行卡';
        }else{
            $where=array('a.Itype'=>2,'a.IsHot'=>1,'a.IsRec'=>0,'a.IsDel'=>0);
            $text='热门银行卡';
        }
        $where['a.Showtype']=array('in',array('1','3'));
        $where['a.Status']=array('eq','1');

        if($areid){
            $where['_string']="a.Isshow=1 OR (a.Isshow=2 AND find_in_set(".$areid.",a.Cityids))";
        }else{
            $where['a.Isshow']=array('eq','1');
        }

        $List=$itemModel->alias('a')
            ->field('a.ID,a.Name,a.Intro,a.Logurl,a.AppNumbs,b.BankName')
            ->join('left join xb_credit_bank b on b.ID=a.BankID')
            ->where($where)
            ->field('a.ID,a.Name,a.AppNumbs,a.Intro,a.Logurl,b.BankName')
            ->order('a.Sort asc,a.ID desc')
            ->limit($rows)
            ->select();
        if(empty($List)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的'.$text,
                'data'=>array()
            );
            exit(json_encode($data));
        }
        foreach($List as $key=>$val){
            $List[$key]['Logurl']='http://'.$_SERVER['HTTP_HOST'].$val['Logurl'];
        }
        $data=array(
           'result'=>1,
           'message'=>'success',
           'data'=>$List
        );
        exit(json_encode($data));
    }

    /**
     * @功能说明: 获取 信用卡中心页银行  列表
     * @传输格式: get提交
     * @提交网址: /Home/Items/getbank
     * @提交信息：client=android&package=android.ceshi&ver=v1.1;
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getbank(){
        $para=I('get.');
        $rows=$para['rows']?$para['rows']:9;
        $List=M(self::T_BANK)->field('ID,BankName,Logurl,Intro,Desc')->where(array('IsTui'=>1,'Status'=>1,'IsDel'=>0))->order('Sort asc,ID desc')->select();
        if(empty($List)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的银行卡',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        foreach($List as $key=>$val){
            $List[$key]['Logurl']='http://'.$_SERVER['HTTP_HOST'].$val['Logurl'];
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$List
        );
        exit(json_encode($data));
    }

    /**
     * @功能说明: 获取 信用卡贷信息  列表
     * @传输格式: get提交
     * @提交网址: /Home/Items/getclist
     * @提交信息：client=android&package=android.ceshi&ver=v1.1&page=0&rows=6&bankid=1&cardid=1&bitid=1&feeid=1;
     *               page:页码数，默认从0页开始   rows:获取的列表数,默认值为10
     *              bankid:银行卡ID    cardid:卡类型ID    bitid:币种ID     feeid:年费用ID
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getclist(){
        $para=I('get.');
        $page=$para['page']?$para['page']:0;
        $rows=$para['rows']?$para['rows']:10;

        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }

        if($para['bankid']){
            $where['a.BankID']=$para['bankid'];
        }
        if($para['cardid']){
            $where['a.KatypeID']=$para['cardid'];
        }
        if($para['bitid']){
            $where['a.BitypeID']=$para['bitid'];
        }
        if($para['feeid']){
            $where['a.YearfeeID']=$para['feeid'];
        }

        if($areid){
            $where['_string']="a.Isshow=1 OR (a.Isshow=2 AND find_in_set(".$areid.",a.Cityids))";
        }else{
            $where['a.Isshow']=array('eq','1');
        }

        $where['a.Showtype']=array('in',array('1','3'));
        $where['a.IsDel']=0;
        $where['a.Status']=array('eq','1');
        $where['a.Itype']=2;

        $List=M(self::T_ITEMS)->alias('a')
            ->join('left join xb_credit_bank b on a.BankID=b.ID')
            ->field('a.ID,a.Name,a.AppNumbs,a.Intro,a.Logurl,b.BankName')
            ->where($where)
            ->limit($page*$rows,$rows)
            ->order('a.Sort asc,a.ID desc')
            ->select();

        if(empty($List)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的卡贷列表',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        foreach($List as $key=>$val){
            $List[$key]['Logurl']='http://'.$_SERVER['HTTP_HOST'].$val['Logurl'];
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$List
        );
        exit(json_encode($data));
    }


    /**
     * @功能说明: 获取 银行类型，卡种类型，币种类型，年费用的信息  列表
     * @传输格式: get提交
     * @提交网址: /Home/items/gettype
     * @提交信息：client=android&package=android.ceshi&ver=v1.1&type=1
     *             type:1是银行类型，2是卡种类型，3是币种类型，4是年费用类型
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function gettype(){
        $para=I('get.');
        if(!in_array($para['type'],array(1,2,3,4))){
            exit(json_encode(array('result'=>0,'message'=>"请传递正确的参数值")));
        }
        if($para['type']==1){        //银行类型
            $List=M(self::T_BANK)->field('ID,BankName as Name')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->select();
            $text="银行类型";
        }elseif($para['type']==2){   //卡种类型
            $List=M(self::T_KATYPE)->field('ID,Name')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->select();
            $text="卡种类型";
        }elseif($para['type']==3){   //币种类型
            $List=M(self::T_BITYPE)->field('ID,Name')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->select();
            $text="币种类型";
        }else{                        //年费用类型
            $List=M(self::T_YEARFEE)->field('ID,Name')->where(array('IsDel'=>0,'Status'=>1))->order('Sort asc,ID desc')->select();
            $text="年费用";
        }
        if(empty($List)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的'.$text.'列表',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$List
        );
        exit(json_encode($data));
    }

    /**
     * @功能说明: 获取 卡贷的信息  详情
     * @传输格式: get提交
     * @提交网址: /Home/items/getcdetail
     * @提交信息：client=android&package=android.ceshi&ver=v1.1&id=1
     *             id:卡贷信息ID
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getcdetail(){
        $para=I('get.');
        $where['a.ID']=$para['id'];
        $where['a.Itype']=2;
        $info=M(self::T_ITEMS)->alias('a')
            ->field('a.MainPic,a.Quanyconts,b.Name as yarfeename,a.Yeardesc,a.Jifen1,a.Jifen2,a.Freetime,a.Freedesc,a.Levelname,a.Fakaurl,a.Openurl')
            ->join('left join xb_credit_yearfee b on a.YearfeeID=b.ID')
            ->where($where)
            ->find();

        if(empty($info)){
            $data=array(
                'result'=>0,
                'result'=>'抱歉，传递的参数id有误',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        $info['MainPic']='http://'.$_SERVER['HTTP_HOST'].$info['MainPic'];
        $info['Fakaurl']='http://'.$_SERVER['HTTP_HOST'].$info['Fakaurl'];
        if($info['Quanyconts']){
            $info['Quanyconts']=unserialize($info['Quanyconts']);
        }else{
            $info['Quanyconts']=array('暂无更多权益');
        }
        M('items')->where(array('ID'=>$para['id']))->setInc('AppNumbs');
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$info
        );
        exit(json_encode($data));
    }

}