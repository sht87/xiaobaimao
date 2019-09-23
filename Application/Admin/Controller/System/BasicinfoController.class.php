<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      刁洪强
 * @修改日期：  2017-05-19
 * @继承版本:   1.1
 * @功能说明：  后台首页控制类
 */
namespace Admin\Controller\System;
use XBCommon;
use XBCommon\CacheData;

class BasicinfoController extends BaseController {

    const T_TABLE = 'sys_basicinfo';
    const T_MEMLEVELS='mem_levels';  //代理级别管理

    /**
     * 加载基本信息页
     */
    public function index(){
      $dailidata=M(self::T_MEMLEVELS)->field('ID,Name')->select();
      $dailiArr=array();
      foreach($dailidata as $k=>$v){
          $dailiArr[$v['ID']]=$v['Name'];
      }
      $this->assign(array(
        'dailiArr'=>$dailiArr,
        ));
    	$this->display();
    } 

    public function home(){

        $LoginInfo['Admin']=$_SESSION['AdminInfo']['Admin'];
        $LoginInfo['RoleName']=$_SESSION['AdminInfo']['RoleName'];

        $this->assign("LoginInfo",$LoginInfo);

        $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
        $VERSION = $Model->query("select VERSION()");
        //print_r($VERSION[0]);

        $this->assign("VERSION",$VERSION);
        //var_dump($VERSION);

        $this->display();
    }

   /**
     * 获取系统基础配置信息
     */
    public function shows(){
        $where="ID=1";
        $model=M(self::T_TABLE);
        $result=$model->where($where)->find();
        $this->ajaxReturn($result);
    }
	
    /**
     * 保存系统配置信息
     */
    public function Save(){
        if(IS_POST){
            //数据保存前的验证规则
            $rules = array(
                array('SystemName','require','系统名称必须填写！'), //默认情况下用正则进行验证
                array('SystemDomain','require','系统域名必须填写！'), //默认情况下用正则进行验证
            );
            //处理表单接收的数据
            $model=D(self::T_TABLE);

            $data=$model->validate($rules)->create();

            if(!isset($data['IP'])){
                $data['IP'] = 0;
            }

            if(!isset($data['MAC'])){
                $data['MAC'] = 0;
            }

            if(!$data){
                //验证不通过,提示保存失败的JSON信息
                $this->ajaxReturn(0,$model->getError());
            }else{
                //判断是否有修改的权限，此处暂时只判断是否为管理员操作
                if($_SESSION['AdminInfo']['AdminID']==null){
                    $this->ajaxReturn(0, '对不起,您没有操作的权限！');
                }else{
                    if($data['TanUrl']){
                        if(!is_url($data['TanUrl'])){
                            $data['TanUrl']='http://'.$data['TanUrl'];
                        }
                    }
                    if($data['YtanUrl']){
                        if(!is_url($data['YtanUrl'])){
                            $data['YtanUrl']='http://'.$data['YtanUrl'];
                        }
                    }
                    $data['Androidurl']=htmlspecialchars_decode($data['Androidurl']);
                    $data['IOSurl']=htmlspecialchars_decode($data['IOSurl']);
                    $data['TanUrl']=htmlspecialchars_decode($data['TanUrl']);
                    $data['YtanUrl']=htmlspecialchars_decode($data['YtanUrl']);

					if($data['productNo']){
						$item = M('items')->where(array('GoodsNo'=>$data['productNo'],'Status'=>1,'IsDel'=>0))->find();
						if($item){
							$data['productID'] = $item['ID'];
							$data['productNo'] = $item['GoodsNo'];
							$data['TanUrl'] = $item['Openurl'];
						}else{
							 $this->ajaxReturn(0, '首页弹窗产品编码错误');
						}
					}
					if($data['productNo_r']){
						$item = M('items')->where(array('GoodsNo'=>$data['productNo_r'],'Status'=>1,'IsDel'=>0))->find();
						if($item){
							$data['productID_r'] = $item['ID'];
							$data['productNo_r'] = $item['GoodsNo'];
							$data['YtanUrl'] = $item['Openurl'];
						}else{
							$this->ajaxReturn(0, '右侧弹窗产品编码错误');

						}
					}
                    //操作权限校验通过,执行后续保存动作
                    $data['OperatorID']=$_SESSION['AdminInfo']['AdminID'];
                    $data['UpdateTime']=date("Y-m-d H:i:s");
                    //保存或更新数据
                    $result=$model->where(array('ID'=>1))->save($data);

                    if($result>0){

                        //清除缓存
                        $cache=new XBCommon\XBCache();
                        $cache->Remove('BasicInfo');
                        $CacheData = new CacheData();
                        $CacheData->BasicInfo();
                        $this->ajaxReturn(1, '恭喜您,修改成功！');

                    }else{
                        $this->ajaxReturn(0, '修改失败，请检查数据库的更新记录！');
                    }
                }
            }
        }else{
            $this->ajaxReturn(0, '数据提交方式不对，必须为POST方式！');
        }
    }

    /**
     * 功能说明:更新所有缓存
     */
    public function RefreshCache(){
        $cache=new XBCommon\CacheData();
        $cache->UpdateCache();
        echo '<font style="font-size:20px;">缓存更新成功！</font>';
    }
    //重新生成宣传图片 
    public function outimgs(){
        $url=THINK_PATH."../Upload/qrcode/";
        //删除所有生成的二维码图片
        if(is_dir($url)){
           $this->deldir($url);
           echo '<font style="font-size:20px;">宣传图片更新成功！</font>';
        }
        
    }
      //清空文件夹函数和清空文件夹后删除空文件夹函数的处理
      public function deldir($path){
           //如果是目录则继续
           if(is_dir($path)){
                //扫描一个文件夹内的所有文件夹和文件并返回数组
               $p = scandir($path);
               foreach($p as $val){
                    //排除目录中的.和..
                    if($val !="." && $val !=".."){
                         //如果是目录则递归子目录，继续操作
                         if(is_dir($path.$val)){
                              //子目录中操作删除文件夹和文件
                              $this->deldir($path.$val.'/');
                              //目录清空后删除空文件夹
                              @rmdir($path.$val.'/');
                         }else{
                              //如果是文件直接删除
                              unlink($path.$val);
                         }
                    }
               }
          }
      }
      
}