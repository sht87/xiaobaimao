<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2017120900481103",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEA0x065i6kgSjQf8+z47iAP+nYrVwnZEZURb8+RYdZkl0nK2AF/nc3K2NCq2aeAvb8oMKw953CJ5jepMGGt8yUQ/JQArpPrDNPKR8yjrzka8elOWn1Ey3prM8lRRs/N37NhEj4VMe7aPkDc/V0+sgdatdH5cyzl8qKto4xcuGMKQF0ZqWmLJiq5B/4mpQ+5FL5op9r33BPSBuL5Ik39HMpJfum64NjxNZo/a6c+dA6bV8wwOZrV8vjB0sqxYtHu4LD+WzC+fMygl0u+Yf7E7PQYtNKLnFLt2N9u2IkS1fMZH+PUcVtLAkRlbl9HRKp40pYUeXkTLVoZK4E8nj4JLeBUwIDAQABAoIBAQCvyHHngcTWCs747VAC9/hJv4P8bXQbSXgYDzJhoF6TwV7A/hZfmJJmXSMBJtPA8jjN/u5tb31fjSktlDqBRiXaIaQ/cTSv1JVAT0rAkUW8/KJ1mOVIT/13N4/358UOh1XGpR+pMBm7QUR/xEzgF8pu0Mx76qNLa4lukh1YY7dZ1+i60ugX2kz8KHGc5iSQ/uTM5x9SnMypjjPE24Ql3HVeX+eCg0LCmNOhLRW5GJDfdh7frEhBpxNa4QU/xR2KGzRYY1/WiA1EWa2buKbb6npS+7/GqYk4Ql5VlTVJxb1TT0gzS5QqWIWCSlt9xlLo0yo5D/znvPHGDHeV+2YweU0JAoGBAPzR8abHVZGp8S+2BNBLcFi9FL7OKDCKV9JzCJQ/fwuW21iEfAokErhx5uV9j+38fHV+i8ADdrbf2ElVhnBRH+XYb7u6mGI+yiapdosBjOfQc9sWx+WCkdED7E5T2csgcf54t6ro6jC0EsmJlWgZwYribsDz1eCrPHQQKEasQU2PAoGBANXE/0UjPoGq77OoiUBatY4ssfSPQcCwMXd1t/kzETIgZpGK4XnUbVJTZYrN8u4ABuLFNnnkiYqgvG9ekMbssXRRfQst4oPoBhKrV0sXYsq6at4oP6v59rzoFUoO1zlKCUe+xPYuZoxZgpHt8EZ37cEoPa8L1Uz8C4SEAYM1zPX9AoGAanHWZZrdqzO7CZSAWFa62ZHajy9remx053Vpckl9qxp8BbvaIcboXuIODieclt3MZQe/vTt4Gy0J+m5Y6Scu3+4NtSOuDwdSw45sC8C+W8fHT8I6raYY0MDvGFdzhnOFq1eWNQ5Vs+XNVy9nSWo2s8v8R58iOLLABDndS0wxPn8CgYB7/PMCqtqKYxb84XgaN4KuoAXnj1/X31oRq9m1VIVYyao0KJV3EFsIMQ7oX2PT33ge97wNHx0XpvskrXjTqF2U1d7tKQQE9gLvuSm8iCfo5g8uI7IqkaUnFIWkms8Cj4qqbl/XWjpolVJCSfvfI5hnPGvqp7ZkHJyhk988t2vWEQKBgD0di6ud6IOnSGel/1gn5eff8G9Bdn9WUK5aLqnrJbCB/f0h/bSSNWtUgqzKLmpB1NMID8ZByGvEiOjVjnGtJoBfeebKuTYbJurkB8842zxHF/LMjeAx3fdKdbtLqMbDvszl9fmuJMwL9WnxvA4zP0aer5YIiCjewPu8CKkH9BDd",
		
		//异步通知地址
		'notify_url' => "http://".$_SERVER['HTTP_HOST']."/alipay/query",
		//同步跳转
		'return_url' => "http://".$_SERVER['HTTP_HOST']."/index/success",

		//异步通知地址
		'zenxin_notify_url' => "http://".$_SERVER['HTTP_HOST']."/alipay/zenxinquery",
		//同步跳转
		'zenxin_return_url' => "http://".$_SERVER['HTTP_HOST']."/Zenxin/zxlist",

		//异步通知地址，贷备店征信查询方式
		'zenxin_notify_url' => "http://".$_SERVER['HTTP_HOST']."/alipay/zenxinquery",
		//同步跳转
		'zenxin_return_url' => "http://".$_SERVER['HTTP_HOST']."/Daizenxin/zdetail",

		//异步通知地址
		'weikuan_notify_url' => "http://".$_SERVER['HTTP_HOST']."/wap.php/alipay/weikuanquery",
		//同步跳转
		'weikuan_return_url' => "http://".$_SERVER['HTTP_HOST']."/wap.php/Cart/wkresult",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0x065i6kgSjQf8+z47iAP+nYrVwnZEZURb8+RYdZkl0nK2AF/nc3K2NCq2aeAvb8oMKw953CJ5jepMGGt8yUQ/JQArpPrDNPKR8yjrzka8elOWn1Ey3prM8lRRs/N37NhEj4VMe7aPkDc/V0+sgdatdH5cyzl8qKto4xcuGMKQF0ZqWmLJiq5B/4mpQ+5FL5op9r33BPSBuL5Ik39HMpJfum64NjxNZo/a6c+dA6bV8wwOZrV8vjB0sqxYtHu4LD+WzC+fMygl0u+Yf7E7PQYtNKLnFLt2N9u2IkS1fMZH+PUcVtLAkRlbl9HRKp40pYUeXkTLVoZK4E8nj4JLeBUwIDAQAB",
		
	
);