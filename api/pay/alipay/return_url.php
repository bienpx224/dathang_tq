<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

	//商户订单号

	$out_trade_no = $_GET['out_trade_no'];

	//支付宝交易号

	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];




	if($trade_status == 'WAIT_BUYER_PAY') {
	//该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
	
			
        //echo "success";		//请不要修改或删除
		XAts('pay_failure','没有付款');
		

    }
	else if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
	//该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货
	
		
        //echo "success";		//请不要修改或删除
		
		
		//——请根据您的业务逻辑来编写程序——
		XAts('pay_success');

    }
	else if($trade_status == 'WAIT_BUYER_CONFIRM_GOODS') {
	//该判断表示卖家已经发了货，但买家还没有做确认收货的操作
	
			
        //echo "success";		//请不要修改或删除
		XAts('pay_failure','已充值过');
		
		//——请根据您的业务逻辑来编写程序——
    }
	else if($trade_status == 'TRADE_FINISHED') {//即时到账和担保接口返回,上面几个是担保交易返回
	//该判断表示买家已经确认收货，这笔交易完成
			
        //echo "success";		//请不要修改或删除
		
		//——请根据您的业务逻辑来编写程序——
		XAts('pay_success');
    }
 	else if($trade_status == 'TRADE_SUCCESS') {//即时到账接口返回,上面几个是担保交易返回
	//即时到账接口，这笔交易完成
			
        //echo "success";		//请不要修改或删除
		//——请根据您的业务逻辑来编写程序——
		XAts('pay_success');

    }
    else {
		//其他状态判断
        //echo "success";
		//——请根据您的业务逻辑来编写程序——
		//不成功的交易,如关闭交易
		XAts('pay_failure','关闭交易');
			

    }
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
    echo "验证失败";
	XAts('pay_failure','验证失败');
}
?>
