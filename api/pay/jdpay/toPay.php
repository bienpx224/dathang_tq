<?php
if(!defined('InXingAo'))
{
	exit('No InXingAo');
}
//****************************************
/*
	$v_mid = '1001';
	$key   = 'test';
*/



	$v_mid = $payr['payuser'];								    // 1001是网银在线的测试商户号，商户要替换为自己的商户号。

	$v_url = $siteurl."api/pay/jdpay/Receive.php";	// 商户自定义返回接收支付结果的页面。对应Receive.php示例。
	                                                    //参照"网银在线支付B2C系统商户接口文档v4.1.doc"中2.3.3.1
	
	$key   = $payr['paykey'];								    // 参照"网银在线支付B2C系统商户接口文档v4.1.doc"中2.4.1进行设置。

	$remark2 = '[url:='.$siteurl.'api/pay/jdpay/AutoReceive.php]'; //服务器异步通知的接收地址。对应AutoReceive.php示例。必须要有[url:=]格式。
																//参照"网银在线支付B2C系统商户接口文档v4.1.doc"中2.3.3.2。
//****************************************


 	$v_oid =$ddno;                     //订单号	
	$v_amount =$money_api;                 //支付金额                 
    $v_moneytype ='CNY';       //币种,只支持CNY

	$text = $v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key;        //md5加密拼凑串,注意顺序不能变
    $v_md5info = strtoupper(md5($text));                             //md5函数加密并转化成大写字母
	$remark1 ="会员ID:".$Muserid."-会员登录账号:".$Musername;					 //备注字段1
?>

<!--以下信息为标准的 HTML 格式 + PHP 语言 拼凑而成的 网银在线 支付接口标准演示页面 无需修改-->

<form method="post" name="E_FORM" id="dopaypost" action="https://tmapi.jdpay.com/PayGate"><!--旧https://pay3.chinabank.com.cn/PayGate-->
	<input type="hidden" name="v_mid"         value="<?php echo $v_mid;?>">
	<input type="hidden" name="v_oid"         value="<?php echo $v_oid;?>">
	<input type="hidden" name="v_amount"      value="<?php echo $v_amount;?>">
	<input type="hidden" name="v_moneytype"   value="<?php echo $v_moneytype;?>">
	<input type="hidden" name="v_url"         value="<?php echo $v_url;?>">
	<input type="hidden" name="v_md5info"     value="<?php echo $v_md5info;?>">
 <!--以下几项项为网上支付完成后，随支付反馈信息一同传给信息接收页 -->	
	
	<input type="hidden" name="remark1"       value="<?php echo $remark1;?>">
	<input type="hidden" name="remark2"       value="<?php echo $remark2;?>">
</form>
<script>
document.getElementById('dopaypost').submit();
</script>
