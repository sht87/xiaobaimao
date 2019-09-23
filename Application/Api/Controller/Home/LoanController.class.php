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
use Think\Controller;

class LoanController extends Controller
{
    const T_BASIC    ='sys_basicinfo';
    const T_ITEMCATE ='items_category';
    const T_ITEMS    ='items';
    const T_AREAS    ='sys_areas';
    const T_MONEYTYPE='items_moneytype';
    const T_CONDITION='items_conditions';
    const T_NEEDS    ='items_needs';
    const T_TRANSFER ='comp_transfer';
    const T_BANK     ='credit_bank';
    const T_KATYPE   ='credit_katype';
    const T_YEARFEE  ='credit_yearfee';
    const T_BITYPE   ='credit_bitype';
    const T_RESULT   ='apply_listresult';
    const T_APPLY    ='apply_list';
    const T_BALANCE  ='mem_balances';
    const T_MONEY    ='mem_money';


    public function _initialize(){
        $para=get_json_data();
        if(empty($para['client'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写客户端名称，如Android、IOS、PC')));
        }
        if(empty($para['package'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写客户端包名')));
        }
        if(empty($para['ver'])){
            exit(json_encode(array('result'=>0,'message'=>'很抱歉，请填写当前软件版本号')));
        }

        $common_package = common_package($para['client'],$para['package'],$para['ver']);
        if($common_package['result'] == 0){
            exit(json_encode(array('result'=>0,'message'=>$common_package['msg'])));
        }

        if(!empty($para['Token'])){
            $AppInfo=XBCache::GetCache($para['Token']);
            //判断是否登录
            if(empty($AppInfo)){
                exit(json_encode(array('result'=>-1,'message'=>'登录已失效,请点击确定后重新登录!')));
            }
            //判断token的有效期
            $last_time=strtotime($AppInfo['TimeOut']);
            $current_time=strtotime(date("Y-m-d H:i:s"));
            $active_time=get_basic_info('Session'); //单位:分钟
            if(($current_time-$last_time)/60>$active_time){
                //已过期重新登录
                XBCache::Remove($para['Token']);
                exit(json_encode(array('result'=>-1,'message'=>'登录已失效,点击确定后重新登录!')));
            }
            //未过期更新过期时间
            $AppInfo['TimeOut']=date('Y-m-d H:m:s');
            XBCache::Insert($para['Token'],$AppInfo);
        }
    }

    /**
     * @功能说明: 获取 佣金产品返点 列表
     * @传输格式: post
     * @提交网址: /Home/Loan/getproduct
     * @提交信息：非josn form 表单 post方式提交
     *          array("client"=>"ios","package"=>"ios.ceshi","ver"=>"1.1","page":"当前页码数，默认值为0","rows":"每页展示数量，默认值6") FILES  Multipart/form-data
     * @返回信息: {'result'=>1,'message'=>'请求成功！'}
     */
    public function getproduct(){
        $itemModel=M(self::T_ITEMS);
        $para=get_json_data();
        if($para['token']){
            $AppInfo=\XBCommon\XBCache::GetCache($para['token']);
        }else{
            $AppInfo['Mtype']=1;
            $AppInfo['ID']=0;
        }

        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }

        $page=$para['page']?$para['page']:0;
        $rows=$para['rows']?$para['rows']:6;

        if($para['Itype']){
            $where['Itype']=array('eq',$para['Itype']);
        }
        $where['IsDel']=0;
        $where['Status']=array('eq','1');
        //$where['Itype']=1;
        $where['Showtype']=array('in',array('2','3'));
        //$where['Mtype']=$AppInfo['Mtype'];
        if($areid){
            $where['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
        }else{
            $where['Isshow']=array('eq','1');
        }
        $field='ID,
                Name,
                Logurl,
                Intro,
                AppNumbs,
                Yjtype,
                BonusRate1,
                BonusRate2,
                BonusRate3,
                BonusRate4,
                Ymoney1,
                Ymoney2,
                Ymoney3,
                Ymoney4,
                Smoney1,
                Smoney2,
                Smoney3,
                Smoney4';

        $itemList=$itemModel
            ->field($field)
            ->where($where)
            ->order('Sort asc,ID desc')
            ->limit($page*$rows,$rows)
            ->select();
        if(empty($itemList)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的产品返点列表',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        foreach($itemList as $key=>$val){
            $itemList[$key]['Logurl'] = 'http://'.$_SERVER['HTTP_HOST'].$val['Logurl'];
            if($val['Yjtype']=='1'){
                //按比例
                switch ($AppInfo['Mtype']){
                    case 1:$itemList[$key]['BonusRate']=$val['BonusRate1'];break;
                    case 2:$itemList[$key]['BonusRate']=$val['BonusRate2'];break;
                    case 3:$itemList[$key]['BonusRate']=$val['BonusRate3'];break;
                    case 4:$itemList[$key]['BonusRate']=$val['BonusRate4'];break;
                    default:$itemList[$key]['BonusRate']=$val['BonusRate4'];break;
                }
                $itemList[$key]['Ymoney']='0';
            }elseif($val['Yjtype']=='2'){
                //按金额
                switch ($AppInfo['Mtype']){
                    case 1:$itemList[$key]['Ymoney']=$val['Ymoney1'];break;
                    case 2:$itemList[$key]['Ymoney']=$val['Ymoney2'];break;
                    case 3:$itemList[$key]['Ymoney']=$val['Ymoney3'];break;
                    case 4:$itemList[$key]['Ymoney']=$val['Ymoney4'];break;
                    default:$itemList[$key]['Ymoney']=$val['Ymoney4'];break;
                }
                $itemList[$key]['BonusRate']='0';
            }
            switch ($AppInfo['Mtype']){
                case 1:$itemList[$key]['Smoney']=$val['Smoney1'];break;
                case 2:$itemList[$key]['Smoney']=$val['Smoney2'];break;
                case 3:$itemList[$key]['Smoney']=$val['Smoney3'];break;
                case 4:$itemList[$key]['Smoney']=$val['Smoney4'];break;
                default:$itemList[$key]['Smoney']=$val['Smoney4'];break;
            }
            unset($itemList[$key]['BonusRate1']);
            unset($itemList[$key]['BonusRate2']);
            unset($itemList[$key]['BonusRate3']);
            unset($itemList[$key]['BonusRate4']);

            unset($itemList[$key]['Ymoney1']);
            unset($itemList[$key]['Ymoney2']);
            unset($itemList[$key]['Ymoney3']);
            unset($itemList[$key]['Ymoney4']);

        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$itemList
        );
        exit(json_encode($data));
    }

    /**
     * @功能说明: 获取新品推荐商品
     * @传输格式: post
     * @提交网址: /Home/Loan/getrec
     * @提交信息：非josn form 表单 post方式提交
     *          array("client"=>"ios","package"=>"ios.ceshi","ver"=>"1.1") FILES  Multipart/form-data
     * @返回信息: {'result'=>1,'message'=>'请求成功！'}
     */
    public function getrec(){
        $itemModel=M(self::T_ITEMS);
        $para=get_json_data();
        if($para['token']){
            $AppInfo=\XBCommon\XBCache::GetCache($para['token']);
        }else{
            $AppInfo['Mtype']=1;
            $AppInfo['ID']=0;
        }

        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }

        $field='ID,
                Name,
                Logurl,
                Intro,
                Yjtype,
                IsRec,
                BonusRate1,
                BonusRate2,
                BonusRate3,
                BonusRate4,
                Ymoney1,
                Ymoney2,
                Ymoney3,
                Ymoney4,
                Smoney1,
                Smoney2,
                Smoney3,
                Smoney4';
        $recwhere=array();
        if($para['Itype']){
            $recwhere['Itype']=array('eq',$para['Itype']);
        }
        
        $recwhere['IsDel']=array('eq','0');
        $recwhere['IsRec']=array('eq','1');
        $recwhere['Status']=array('eq','1');
        $recwhere['Showtype']=array('in',array('2','3'));
        if($areid){
            $recwhere['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
        }else{
            $recwhere['Isshow']=array('eq','1');
        }
        $recList=$itemModel
            ->field($field)
            ->where($recwhere)
            ->order('Sort asc,ID desc')
            ->limit(4)
            ->select();
        if(empty($recList)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有推荐的返点产品列表',
                'data'=>array()
            );
            exit(json_encode($data));
        }

        foreach($recList as $key=>$val){
            $recList[$key]['Logurl'] = 'http://'.$_SERVER['HTTP_HOST'].$val['Logurl'];
            if($val['Yjtype']=='1'){
                //按比例
                switch ($AppInfo['Mtype']){
                    case 1:$recList[$key]['BonusRate']=$val['BonusRate1'];break;
                    case 2:$recList[$key]['BonusRate']=$val['BonusRate2'];break;
                    case 3:$recList[$key]['BonusRate']=$val['BonusRate3'];break;
                    case 4:$recList[$key]['BonusRate']=$val['BonusRate4'];break;
                    default:$recList[$key]['BonusRate']=$val['BonusRate4'];break;
                }
                $recList[$key]['Ymoney']='0';
            }elseif($val['Yjtype']=='2'){
                //按金额
                switch ($AppInfo['Mtype']){
                    case 1:$recList[$key]['Ymoney']=$val['Ymoney1'];break;
                    case 2:$recList[$key]['Ymoney']=$val['Ymoney2'];break;
                    case 3:$recList[$key]['Ymoney']=$val['Ymoney3'];break;
                    case 4:$recList[$key]['Ymoney']=$val['Ymoney4'];break;
                    default:$recList[$key]['Ymoney']=$val['Ymoney4'];break;
                }
                $recList[$key]['BonusRate']='0';
            }
            switch ($AppInfo['Mtype']){
                case 1:$recList[$key]['Smoney']=$val['Smoney1'];break;
                case 2:$recList[$key]['Smoney']=$val['Smoney2'];break;
                case 3:$recList[$key]['Smoney']=$val['Smoney3'];break;
                case 4:$recList[$key]['Smoney']=$val['Smoney4'];break;
                default:$recList[$key]['Smoney']=$val['Smoney4'];break;
            }
            unset($recList[$key]['BonusRate1']);
            unset($recList[$key]['BonusRate2']);
            unset($recList[$key]['BonusRate3']);
            unset($recList[$key]['BonusRate4']);

            unset($recList[$key]['Ymoney1']);
            unset($recList[$key]['Ymoney2']);
            unset($recList[$key]['Ymoney3']);
            unset($recList[$key]['Ymoney4']);
        }

        // $itewhere=array();
        // $itewhere['Itype']=array('eq','1');
        // $itewhere['IsDel']=array('eq','0');
        // $itewhere['IsRec']=array('eq','0');
        // $itewhere['Status']=array('eq','1');
        // $itewhere['Showtype']=array('in',array('2','3'));
        // if($areid){
        //     $itewhere['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
        // }else{
        //     $itewhere['Isshow']=array('eq','1');
        // }
        // $itemList=$itemModel
        //     ->field($field)
        //     ->where($itewhere)
        //     ->order('Sort asc,ID desc')
        //     ->limit(6)
        //     ->select();
        // if(empty($itemList)){
        //     $data=array(
        //         'result'=>0,
        //         'message'=>'抱歉，暂没有非推荐的返点产品列表',
        //         'data'=>array()
        //     );
        //     exit(json_encode($data));
        // }

        // foreach($itemList as $key=>$val){
        //     $itemList[$key]['Logurl'] = 'http://'.$_SERVER['HTTP_HOST'].$val['Logurl'];
        //     if($val['Yjtype']=='1'){
        //         //按比例
        //         switch ($AppInfo['Mtype']){
        //             case 1:$itemList[$key]['BonusRate']=$val['BonusRate1'];break;
        //             case 2:$itemList[$key]['BonusRate']=$val['BonusRate2'];break;
        //             case 3:$itemList[$key]['BonusRate']=$val['BonusRate3'];break;
        //             case 4:$itemList[$key]['BonusRate']=$val['BonusRate4'];break;
        //         }
        //         $itemList[$key]['Ymoney']='0';
        //     }elseif($val['Yjtype']=='2'){
        //         //按金额
        //         switch ($AppInfo['Mtype']){
        //             case 1:$itemList[$key]['Ymoney']=$val['Ymoney1'];break;
        //             case 2:$itemList[$key]['Ymoney']=$val['Ymoney2'];break;
        //             case 3:$itemList[$key]['Ymoney']=$val['Ymoney3'];break;
        //             case 4:$itemList[$key]['Ymoney']=$val['Ymoney4'];break;
        //         }
        //         $itemList[$key]['BonusRate']='0';
        //     }
        //     unset($itemList[$key]['BonusRate1']);
        //     unset($itemList[$key]['BonusRate2']);
        //     unset($itemList[$key]['BonusRate3']);
        //     unset($itemList[$key]['BonusRate4']);

        //     unset($itemList[$key]['Ymoney1']);
        //     unset($itemList[$key]['Ymoney2']);
        //     unset($itemList[$key]['Ymoney3']);
        //     unset($itemList[$key]['Ymoney4']);
        // }

        // $List=array();
        // array_push($List,$recList,$itemList);
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$recList
        );
        exit(json_encode($data));
    }
}