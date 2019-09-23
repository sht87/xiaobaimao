<?php
/**
 * @版权所有：  青岛银狐信息科技有限公司
 * @公司电话：  0532-58770968
 * @作者：      胡 锐
 * @修改日期：  2018-08-08 10:51
 * @功能说明：  支付流程
 */

namespace Vendor\XBPayzenxin\Entity;

class Order
{
    const TABLE = 'zenxin_list';
    public $dbInstance;
    private $where = [];
    private $memID;#@会员主键
    private $levelID;#@代理等级
    private $amount;#@订单金额
    private $orderNo;#@订单流水
    private $bankCardNumber;#@银行卡号
    private $holderName;#@姓名
    private $holderId;#@身份证
    private $holderPhone;#@预约人手机号
    private $Status;#@状态:1待付款 2已付款 3已取消
    private $TradeNo;#@第三方订单号
    private $BusinessParameters;#@业务参数

    public function __construct()
    {
        $this->setDbInstance(M(static::TABLE));
    }

    //根据业务订单号 查询 订单信息
    public function find($orderNo)
    {
        $orderInfo = $this->getDbInstance()->where('OrderSn="'.$orderNo.'"')->find();
        if($orderInfo) {$this->loadData($orderInfo);}
        return $orderInfo;
    }
    //根据业务订单号 查询 订单信息
    public function findByTradeNo($TradeNo)
    {
        $orderInfo = $this->getDbInstance()->where('TradeNo="'.$TradeNo.'"')->find();
        #echo $this->getDbInstance()->getLastSql();
        if($orderInfo) {$this->loadData($orderInfo);}
        return $orderInfo;
    }
    //根据业务订单号 保存 订单信息
    public function save()
    {
        #安全处理
        $where = $this->getWhere();
        if(empty($where))return false;

        $result = $this->getDbInstance()->where($where)->save($this->unloadData());

        return $result !== false;
    }

    public function loadData($data)
    {
        isset($data['UserID']) and $this->setMemID($data['UserID']);
//        isset($data['LevelID']) and $this->setLevelID($data['LevelID']);
        isset($data['OrderSn']) and $this->setOrderNo($data['OrderSn']);
        isset($data['OrderAmount']) and $this->setAmount($data['OrderAmount']);
        isset($data['Status']) and $this->setStatus($data['Status']);
        isset($data['TradeNo']) and $this->setTradeNo($data['TradeNo']);

        if(isset($data['BusinessParameters'])) {
            $BusinessParameters = json_decode($data['BusinessParameters'], true);
        }
        isset($BusinessParameters['BankCode']) and $this->setBankCardNumber($BusinessParameters['BankCode']);
        isset($BusinessParameters['RealName']) and $this->setHolderName($BusinessParameters['RealName']);
        isset($BusinessParameters['CardID']) and $this->setHolderId($BusinessParameters['CardID']);
        isset($BusinessParameters['Phone']) and $this->setHolderPhone($BusinessParameters['Phone']);
//        isset($BusinessParameters['TradeNo']) and $this->setTradeNo($BusinessParameters['TradeNo']);
    }

    public function unloadData()
    {
        $data = [];
        $BusinessParameters = [];

        is_null( $memID = $this->getMemID() ) or $data['UserID'] = $memID;
//        is_null( $levelID = $this->getLevelID() ) or $data['LevelID'] = $levelID;
        is_null( $amount = $this->getAmount() ) or $data['OrderAmount'] = $amount;
        is_null( $Status = $this->getStatus() ) or $data['Status'] = $Status;
        is_null( $TradeNo = $this->getTradeNo() ) or $data['TradeNo'] = $TradeNo;

        is_null( $bankCardNumber = $this->getBankCardNumber() ) or $BusinessParameters['BankCode'] = $bankCardNumber;
        is_null( $holderName = $this->getHolderName() ) or $BusinessParameters['RealName'] = $holderName;
        is_null( $holderId = $this->getHolderId() ) or $BusinessParameters['CardID'] = $holderId;
        is_null( $holderPhone = $this->getHolderPhone() ) or $BusinessParameters['Phone'] = $holderPhone;
//        is_null( $TradeNo = $this->getTradeNo() ) or $BusinessParameters['TradeNo'] = $TradeNo;

        $data['BusinessParameters'] = json_encode($BusinessParameters, \JSON_UNESCAPED_UNICODE);

        #$this->loadData($data);

        return $data;
    }

    public function setWhere($field, $value, $cover = false)
    {
        $where = [$field => $value];

        if($cover) {$this->where = $where;}
        else {$this->where = array_merge($this->where, $where);}
    }

    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * @param mixed $orderNo
     */
    public function setOrderNo($orderNo)
    {
        $this->setWhere('OrderSn', $orderNo);
        $this->orderNo = $orderNo;
    }

    /**
     * @return mixed
     */
    public function getBankCardNumber()
    {
        return $this->bankCardNumber;
    }

    /**
     * @param mixed $bankCardNumber
     */
    public function setBankCardNumber($bankCardNumber)
    {
        $this->bankCardNumber = $bankCardNumber;
    }

    /**
     * @return mixed
     */
    public function getHolderName()
    {
        return $this->holderName;
    }

    /**
     * @param mixed $holderName
     */
    public function setHolderName($holderName)
    {
        $this->holderName = $holderName;
    }

    /**
     * @return mixed
     */
    public function getHolderId()
    {
        return $this->holderId;
    }

    /**
     * @param mixed $holderId
     */
    public function setHolderId($holderId)
    {
        $this->holderId = $holderId;
    }

    /**
     * @return mixed
     */
    public function getDbInstance()
    {
        return $this->dbInstance;
    }

    /**
     * @param mixed $dbInstance
     */
    public function setDbInstance($dbInstance)
    {
        $this->dbInstance = $dbInstance;
    }

    /**
     * @return mixed
     */
    public function getMemID()
    {
        return $this->memID;
    }

    /**
     * @param mixed $memID
     */
    public function setMemID($memID)
    {
        $this->memID = $memID;
    }

    /**
     * @return mixed
     */
    public function getLevelID()
    {
        return $this->levelID;
    }

    /**
     * @param mixed $levelID
     */
    public function setLevelID($levelID)
    {
        $this->levelID = $levelID;
    }

    /**
     * @return mixed
     */
    public function getHolderPhone()
    {
        return $this->holderPhone;
    }

    /**
     * @param mixed $holderPhone
     */
    public function setHolderPhone($holderPhone)
    {
        $this->holderPhone = $holderPhone;
    }

    /**
     * @return mixed
     */
    public function getTradeNo()
    {
        return $this->TradeNo;
    }

    /**
     * @param mixed $TradeNo
     */
    public function setTradeNo($TradeNo)
    {
        $this->TradeNo = $TradeNo;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * 状态:1待付款 2已付款 3已取消 3处理中
     * @param mixed $Status
     */
    public function setStatus($Status)
    {
        $this->Status = $Status;
        return $this;
    }

    public function setStatusSuccess()
    {
        $this->setStatus(2);return $this;
    }
    public function setStatusFail()
    {
        $this->setStatus(3);return $this;
    }
    public function setStatusProcess()
    {
        $this->setStatus(4);return $this;
    }

}