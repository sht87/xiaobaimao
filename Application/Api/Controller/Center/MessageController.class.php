<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      傅世敏
 * @修改日期：  2017-07-25
 * @继承版本:   1.1
 * @功能说明：  消息中心
 */
namespace Api\Controller\Center;
use Api\Controller\Core\BaseController;
class MessageController extends BaseController
{
    const T_MEM='mem_info';
    const T_SMS='sys_sms';

    /**
     * @功能说明: 获取用户消息列表
     * @传输格式: 私有token,明文传输，密文返回
     * @提交网址: /Message/Message/index
     * @提交信息：{"token":"b9QTd4+QWcXWv7N14TooigBmAyOT5EqnIY5aPO9FdjA=","client":"ios","ver":"1.1","page":"1","row":"20"}
     * @返回信息: {'result'=>1,'message'=>'恭喜您，获取成功！','data'}
     */
    public function index()
    {
        $para = get_json_data();

        $user = M(self::T_MEM)->where(array('ID'=>get_login_info('ID'),'Status'=>1,'IsDel'=>0))->find();
        if(!$user){
            exit(json_encode(array('result'=>-1,'message'=>'很抱歉,未查找到相关的数据!')));
        }

        //查询用户信息
        if ($para['page']) {
            $page = $para['page'] ? $para['page'] : 1;
            $row = $para['row'] = $para['row'] ? $para['row'] : 20;
            $limits = ($page - 1) * $row . ',' . $row;
        } else {
            $limits = 20;
        }

        //组装查询条件
        $where["ObjectID"] = array(array('eq', $user['UserName']), array('eq', '所有人'), 'or');
        $where["Mode"] = array(array('eq', 0), array('eq', 2), 'or');
        //查询数据和统计数据条数
        $list['count'] = M(self::T_SMS)->where($where)->count();
        $msg = M(self::T_SMS)->where($where)->limit($limits)->field('ID,SendMess,SendTime')->order("ID DESC")->select();

        $data=array(
            'result'=>1,
            'message'=>'恭喜您,获取成功!',
            'data'=>encrypt_pkcs7(json_encode($msg),$user['KEY'],$user['IV'])
        );
        exit(json_encode($data));

    }

}


