<?php

/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");


//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/api/pay/update_time.php','3 '.date('Y-m-d H:i:s',time()));//记录


//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号

	$ddno = $_POST['out_trade_no'];

	//支付宝交易号

	$trade_no = $_POST['trade_no'];

	//交易状态
	$trade_status = $_POST['trade_status'];


	if($trade_status == 'WAIT_BUYER_PAY') {
	//该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
	
			
        echo "success";		//请不要修改或删除

    }
	else if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
	//该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货
	
			
        echo "success";		//请不要修改或删除
		
		//——请根据您的业务逻辑来编写程序——
		require_once($_SERVER['DOCUMENT_ROOT'].'/api/pay/paySave.php');

    }
	else if($trade_status == 'WAIT_BUYER_CONFIRM_GOODS') {
	//该判断表示卖家已经发了货，但买家还没有做确认收货的操作
	
			
        echo "success";		//请不要修改或删除
		
		//——请根据您的业务逻辑来编写程序——
		require_once($_SERVER['DOCUMENT_ROOT'].'/api/pay/paySave.php');
    }
	else if($trade_status == 'TRADE_FINISHED') {
	//该判断表示买家已经确认收货，这笔交易完成
	
			
        echo "success";		//请不要修改或删除

		//——请根据您的业务逻辑来编写程序——
		require_once($_SERVER['DOCUMENT_ROOT'].'/api/pay/paySave.php');
    }
 	else if($trade_status == 'TRADE_SUCCESS') {//即时到账接口返回,上面几个是担保交易返回
	//即时到账接口，这笔交易完成
			
        echo "success";		//请不要修改或删除
		//——请根据您的业务逻辑来编写程序——
		require_once($_SERVER['DOCUMENT_ROOT'].'/api/pay/paySave.php');

    }
    else {
		//其他状态判断
        echo "success";
		//——请根据您的业务逻辑来编写程序——
		//不成功的交易,如关闭交易
			

    }

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";
	
}


?>