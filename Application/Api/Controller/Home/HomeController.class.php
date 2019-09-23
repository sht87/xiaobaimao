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

class HomeController extends BaseController
{
    const T_BASIC    ='sys_basicinfo';
    const T_ADS      ='sys_adcontent';
    const T_ITEMCATE ='items_category';
    const T_ITEMS    ='items';
    const T_NOTICE   ='sys_contentmanagement';
    const T_APPLY    ='apply_list';
    const T_RESULT   ='apply_listresult';

    /**
     * @功能说明: 获取广告轮播图
     * @传输格式: get提交
     * @提交网址: /Home/home/ads
     * @提交信息：client=android&package=android.ceshi&ver=v1.1&aid=1&num=2
     *            aid 是广告ID  1是首页板块头部两广告  2是其他板块的广告  num是显示数量
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function ads(){
        $para = I('get.');
        $num = $para['num'] ? $para['num'] : 3;
        $aid = $para['aid'] ? $para['aid'] : 1;

        $ads = M(self::T_ADS)->field("Name,Pic,Url,UrlType,SystemClass,SystemClassVal,productID")->where(array("AdvertisingID"=>$aid,"Status"=>1))->order('Sort asc,ID desc')->limit($num)->select();
        if(empty($ads)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可用的轮播图信息！',
                'data'=>array()
            );
            exit(json_encode($data));
        }

        $list = array();
        foreach($ads as $val){
            $val['Pic'] = 'http://'.$_SERVER['HTTP_HOST'].$val['Pic'];
            $list[] = $val;
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$list
        );
        exit(json_encode($data));
    }

    /**
     * @功能说明: 获取弹窗
     * @传输格式: get提交
     * @提交网址: /Home/home/tanimg
     * @提交信息：client=android&package=android.ceshi&ver=v1.1
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function tanimg(){
        $BasicInfo=M('sys_basicinfo')->find();
        if($BasicInfo['Ytstatus']!=1){
            $list['YouTan']=array(
                'YtanImg'=>'http://'.$_SERVER['HTTP_HOST'].$BasicInfo['YtanImg'],
                'YtanUrl'=>$BasicInfo['YtanUrl'],
				'productID_r'=>$BasicInfo['productID_r'],
				'productID'=>$BasicInfo['productID_r'],
            );
        }else{
            $list['YouTan']=array(
                'YtanImg'=>'',
                'YtanUrl'=>'',
				'productID_r'=>'0',
				'productID'=>'0',
            );
        }
        if($BasicInfo['Tcstatus']=='3' || $BasicInfo['Tcstatus']=='4'){
            $list['BigTan'] = array(
                'TanImg'=>'http://'.$_SERVER['HTTP_HOST'].$BasicInfo['TanImg'],
                'TanUrl'=>$BasicInfo['TanUrl'],
				'Tcstatus'=>$BasicInfo['Tcstatus'],
				'productID'=>$BasicInfo['productID'],
                );
        }else{
            $list['BigTan']=array(
                'TanImg'=>'',
                'TanUrl'=>'',
				'productID'=>'0',
            );
        }
        if($list){
            $data=array(
                'result'=>1,
                'message'=>'success',
                'data'=>$list
            );
            exit(json_encode($data));
        }else{
            $data=array(
                'result'=>1,
                'message'=>'success',
                'data'=>$list
            );
            exit(json_encode($data));
        }
    }

    /**
     * @功能说明: 获取 系统公告 轮播列表
     * @传输格式: get提交
     * @提交网址: /Home/home/getnotice
     * @提交信息：client=android&package=android.ceshi&ver=v1.1
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getnotice(){
        $ads = M(self::T_NOTICE)->field("Title")->where(array("CategoriesID"=>2))->order('Sort asc,ID desc')->limit(3)->select();
        if(empty($ads)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的系统公告！',
                'data'=>array()
            );
            exit(json_encode($data));
        }

        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$ads
        );
        exit(json_encode($data));
    }

    /**
     * @功能说明: 获取 贷款类型 列表
     * @传输格式: get提交
     * @提交网址: /Home/home/getcate
     * @提交信息：client=android&package=android.ceshi&ver=v1.1&rows=8
     *              rows:获取的列表数
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getcate(){
        $para=I('get.');
        $rows=$para['rows']?$para['rows']:8;
        $cateList=M(self::T_ITEMCATE)->field('ID,Name,Imageurl')->where(array('ParentID'=>0,'IsRec'=>'1','Status'=>1,'IsDel'=>0))->order('Sort asc,ID desc')->limit($rows)->select();
        if(empty($cateList)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的贷款类型！',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        foreach($cateList as $key=>$val){
            $cateList[$key]['Imageurl'] = 'http://'.$_SERVER['HTTP_HOST'].$val['Imageurl'];
        }
        $rest=array();
        $cityinfo=get_cityinfo();//获取城市信息
        $rest=array(
            'cateList'=>$cateList,
            'cityname'=>$cityinfo['cityname'],
            );
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$rest
        );
        exit(json_encode($data));
    }


    /**
     * @功能说明: 获取 首页板块 列表
     * @传输格式: get提交
     * @提交网址: /Home/home/getplate
     * @提交信息：client=android&package=android.ceshi&ver=v1.1
     *              rows:获取的列表数
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getplate(){
        $cateList=M('sys_simplepage')->field('ID,Contents,Title,Imageurl')->where(array('ID'=>array('between','8,15')))->order('Sort asc')->select();
        if(empty($cateList)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的板块！',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        foreach($cateList as $key=>$val){
            $cateList[$key]['Imageurl'] = 'http://'.$_SERVER['HTTP_HOST'].$val['Imageurl'];
            if($val['ID']!=12 && $val['ID']!=13 && $val['ID']!=14 && $val['ID']!=15){
                $val['Contents']=htmlspecialchars_decode($val['Contents']);
                $cateList[$key]['Contents']=str_replace('src="/Upload/','src="http://'.$_SERVER['HTTP_HOST'].'/Upload/',$val['Contents']);
                $cateList[$key]['Contents']=str_replace('src="/upload/','src="http://'.$_SERVER['HTTP_HOST'].'/Upload/',$val['Contents']);
            }else{
                $cateList[$key]['Contents']='';
            }
        }
        $cityinfo=get_cityinfo();//获取城市信息
        $rest=array(
            'cityname'=>$cityinfo['cityname'],
        );
        $rest['cateList']=$cateList;

        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$rest
        );
        exit(json_encode($data));
    }


    /**
     * @功能说明: 获取 推荐网贷、热门网贷 列表
     * @传输格式: get提交
     * @提交网址: /Home/home/getitems
     * @提交信息：client=android&package=android.ceshi&ver=v1.1&isrec=1&rows=4
     *              isrec:1是智能推荐 0是热门推荐  rows:获取的列表数
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function getitems(){
        $para=I('get.');
        $rows=$para['rows']?$para['rows']:4;
        $itemModel=M(self::T_ITEMS);

        $cityinfo=get_cityinfo();//获取城市信息
        if($cityinfo['code']){
            $areid=getareaid($cityinfo['code']);//获取城市id
        }
        // 1是智能推荐，否则是热门推荐
        if($para['isrec']==1){
            $field='ID,Name,Logurl,AppNumbs,linkType,Openurl';
            $where=array('IsRec'=>1,'Itype'=>1,'IsDel'=>0);
            $where['Status']=array('eq','1');
            $where['Showtype']=array('in',array('1','3'));
            if($areid){
                $where['_string']="Isshow=1 OR (Isshow=2 AND find_in_set(".$areid.",Cityids))";
            }else{
                $where['Isshow']=array('eq','1');
            }
            $itemList=$itemModel->field($field)
                ->where($where)
                ->order('Sort asc,ID desc')
                ->limit($rows)
                ->select();
            $text="智能推荐贷款";
        }else{

            $field='a.ID,a.Name,a.Logurl,a.AppNumbs,a.Intro,c.Name as Jkdays,a.DayfeeRate,b.Name as TypeName,a.linkType,a.Openurl';
            $where=array('a.IsHot'=>1,'a.Itype'=>1,'a.IsDel'=>0);
            $where['a.Status']=array('eq','1');
            $where['a.Showtype']=array('in',array('1','3'));
            if($areid){
                $where['_string']="a.Isshow=1 OR (a.Isshow=2 AND find_in_set(".$areid.",a.Cityids))";
            }else{
                $where['a.Isshow']=array('eq','1');
            }
            $itemList=$itemModel->alias('a')
                ->join('left join xb_items_moneytype b on b.ID=a.MoneytypeID')
                ->join('left join xb_items_jktimes c on a.JktimesID=c.ID')
                ->field($field)
                ->where($where)
                ->order('a.Sort asc,a.ID desc')
                ->limit(20)
                ->select();
            $text="热门借款";
        }

        if(empty($itemList)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂没有可展示的'.$text,
                'data'=>array()
            );
            exit(json_encode($data));
        }
        foreach($itemList as $key=>$val){
            $itemList[$key]['Logurl'] = 'http://'.$_SERVER['HTTP_HOST'].$val['Logurl'];
        }
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$itemList
        );
        exit(json_encode($data));
    }



    
    /**
     * @功能说明: 获取 我的钱包 信息
     * @提交网址: /Home/Home/getclient
     * @传输方式: 私有token,明文提交，密文返回
     * @提交信息：{"token":"3182F49D44F72708AA00041EF2B0E5E1DEDD50FEC4E36FB12C85FFDA80DD","client":"ios","ver":"1.1","page":"0","rows":"10","dates":"2018-03"}
     *              "page":"当前页码数，默认0","rows":"每页展示数据数量，默认10"
     * @返回信息: {'result'=>1,'message'=>'获取成功!'}
     */
    public function getclient(){
        //获取数据流
        $para = get_json_data();
        $UserID=get_login_info('ID');

        $page=$para['page']?$para['page']:0;
        $rows=$para['rows']?$para['rows']:10;
        $dates=$para['dates']?$para['dates']:date('Y-m');
        //总的申请人数
        $where["date_format(Addtime,'%Y-%m')"]=array('eq',$dates);
        $where['UserID']=array('eq',$UserID);
        $applytotal=M(self::T_APPLY)->where($where)->count();

        //借贷项目
        $info=M(self::T_ITEMS)->field('ID,Itype,Name,GoodsNo')->where(array('Status'=>'1','IsDel'=>'0'))->limit($page*$rows,$rows)->order('Sort desc,ID desc')->select();
        if(empty($info)){
            $data=array(
                'result'=>0,
                'message'=>'抱歉，暂时没有可展示的放款项目',
                'data'=>array()
            );
            exit(json_encode($data));
        }
        //统计处理
        if($info){
            foreach($info as $k=>$v){
                //申请位数
                $where2=array();
                $where2['ItemID']=array('eq',$v['ID']);
                $where2["date_format(Addtime,'%Y-%m')"]=array('eq',$dates);
                $where2['IsDel']=array('eq','0');
                $where2['UserID']=array('eq',$UserID);
                $info[$k]['applycount']=M(self::T_APPLY)->where($where2)->count();
                //放款成功
                $where3=array();
                $where3['b.ItemID']=array('eq',$v['ID']);
                $where3['b.UserID']=array('eq',$UserID);
                $where3["date_format(b.Addtime,'%Y-%m')"]=array('eq',$dates);
                $info[$k]['fksuccess']=M(self::T_RESULT)->alias('a')
                    ->join('left join xb_apply_list b on a.ListID=b.ID')
                    ->where($where3)->count();
                //奖金
                $info[$k]['BonusAll']=M(self::T_RESULT)->alias('a')
                    ->join('left join xb_apply_list b on a.ListID=b.ID')
                    ->where($where3)->SUM('a.Bonus');
                if(!$info[$k]['BonusAll']){
                    $info[$k]['BonusAll']='0';
                }
            }
        }
        $List['info']=$info;
        $List['applytotal']=$applytotal;
        $data=array(
            'result'=>1,
            'message'=>'success',
            'data'=>$List,
        );
        exit(json_encode($data));
    }
    /**
     * @功能说明: 根据客户端和版本号与后台设置的匹配版本号，提示更新下载
     * @传输格式:  get
     * @提交网址: /Home/home/version
     * @提交信息：client=android&package=ceshi.app&ver=1.1
     *               Ver 版本号  isForced  是否强制更新 1是 2不是  Url下载地址
     * @返回信息: {'result'=>1,'message'=>'success',data=>array()}
     */
    public function version(){
        $para = I("get.");
        $find = M('version')->field('ID,Ver,isForced,Url,Updates')->where(array("Client"=>$para['client'],"IsDefault"=>1))->find();

        if($find){
            $find['Updates']=htmlspecialchars_decode($find['Updates']);
            $data=array(
                'result'=>1,
                'message'=>'success',
                'data'=>$find
            );
            exit(json_encode($data));
        }else{
            $data=array(
                'result'=>1,
                'message'=>'success',
                'data'=>array('id'=>'0')
            );
            exit(json_encode($data));
        }
    }

	public function point(){
		$para=I('get.');
		$time = date('Y-m-d');
		$pointName = $para['pointName'];
		$find = M('point_home')->where(array("createDate"=>$time))->find();
		$data = array();
		if($pointName=='banner'){
			if($find){
				$data = $find;
				$data['banner'] = $data['banner'] + 1;
			}
			else{
				$data['banner'] = 1;
				$data['createDate'] = $time;
			}
		}
		if($pointName=='cate1'){
			if($find){
				$data = $find;
				$data['cate1'] = $data['cate1'] + 1;
			}
			else{
				$data['cate1'] = 1;
				$data['createDate'] = $time;
			}
		}
		if($pointName=='cate2'){
			if($find){
				$data = $find;
				$data['cate2'] = $data['cate2'] + 1;
			}
			else{
				$data['cate2'] = 1;
				$data['createDate'] = $time;
			}
		}
		if($pointName=='cate3'){
			if($find){
				$data = $find;
				$data['cate3'] = $data['cate3'] + 1;
			}
			else{
				$data['cate3'] = 1;
				$data['createDate'] = $time;
			}
		}
		if($pointName=='cate4'){
			if($find){
				$data = $find;
				$data['cate4'] = $data['cate4'] + 1;
			}
			else{
				$data['cate4'] = 1;
				$data['createDate'] = $time;
			}
		}
		if($pointName=='cate5'){
			if($find){
				$data = $find;
				$data['cate5'] = $data['cate5'] + 1;
			}
			else{
				$data['cate5'] = 1;
				$data['createDate'] = $time;
			}
		}
		if($pointName=='cate6'){
			if($find){
				$data = $find;
				$data['cate6'] = $data['cate6'] + 1;
			}
			else{
				$data['cate6'] = 1;
				$data['createDate'] = $time;
			}
		}
		if($pointName=='cate7'){
			if($find){
				$data = $find;
				$data['cate7'] = $data['cate7'] + 1;
			}
			else{
				$data['cate7'] = 1;
				$data['createDate'] = $time;
			}
		}
		if($pointName=='cate8'){
			if($find){
				$data = $find;
				$data['cate8'] = $data['cate8'] + 1;
			}
			else{
				$data['cate8'] = 1;
				$data['createDate'] = $time;
			}
		}
		if($find){
			M('point_home')->save($data);
		}
		else{
			M('point_home')->add($data);
		}

		 $data=array(
                'result'=>1,
                'message'=>'success'
            );
            exit(json_encode($data));
	}
}