<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      胡 锐
 * @修改日期：  2018-08-08 08:08
 * @功能说明：  支付抽象类
 * @使用说明：  必须实现
 */
namespace Vendor\XBPayment;

abstract class AbstractPayer
{
    protected $debug = null;
    protected $config = array();

    public function __construct($config)
    {
        $this->config = array_merge($this->config, $config);
        $this->debug = true;
    }

    /**
     * [payurl 定义支付的操作]
     * @return [type] [description]
     */
    abstract public function payurl($compact);

    /**
     * [notifyReady 回调的必备数据]
     * @return [type] [description]
     */
    abstract protected function notifyReady();

    /**
     * [payNotify 守护回调返回]
     * @return [type] [description]
     */
    public function payNotify()
    {
        $shouldArr = array(
            'OrderNo' => null,//商户的订单号
            'TradeNo' => null,//支付方流水号
            'OrderAmt' => null,//订单金额
            'TradeAmt' => null,//支付金额
            'PayStatus' => null,//支付状态 必须 S或者F或者P
        );
        $returnArr = $this->notifyReady();

        if($returnArr && empty(array_diff($shouldArr, $returnArr))) {
            return $returnArr;
        }

        $this->halt('错误的实现->notifyReady()');

    }

    /**
     * [notifyReady 模拟回调接口与notifyReady对应]
     * @return [type] [description]
     */
    abstract protected function mockNotify($data);

    public function halt($word)
    {
        if($this->debug) {
            echo $word.PHP_EOL;die;
        } else {
            throw new Exception($word);
        }

    }
}