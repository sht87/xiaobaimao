<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      胡 锐
 * @修改日期：  2018-08-08 10:51
 * @功能说明：  支付流程
 */


namespace Vendor\XBPayzenxin;

use Think\Request;
use Vendor\XBPayzenxin\Entity\Order;

class XBPaymentFactory
{
    static $namespace_scope = '\\Vendor\\XBPayzenxin\\Gateways\\';

    static $instances = null;
    static $order = null;

    static $payload = array(
        'Chanpay' => [
            'app_id' => '200002180100',
            'api_url' => 'https://pay.chanpay.com/mag-unify/gateway/receiveOrder.do',
            'app_key' => '',
            'app_secret' => '',
            'return_url' => '/Member/index',
            'notify_url' => '/api.php/Center/Payzenxin/chanpayNotify',
        ],

    );

    static function build($objectName, ...$args)
    {
        if(!isset(static::$instances[$objectName])) {

            $payerName = ucfirst(basename($objectName));
            $className = static::$namespace_scope.$payerName.'\\Payer';

            if(class_exists($className)) {
                static::$instances[$objectName] = new $className(static::$payload[$payerName], ...$args);
            } else {
                throw new \Exception('未定义的类:'.$className);
            }
        }
        return static::$instances[$objectName];
    }

    static function order()
    {
        if(is_null(static::$order)) {
            static::$order = new Order();
        }
        return static::$order;
    }

    public function __callStatic($objectName, $args)
    {
        return static::build($objectName, $args);
    }

}
