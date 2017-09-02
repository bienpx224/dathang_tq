<?php
/*
商户号：1900000113
商户名称：自助商户测试帐户
密钥：e82573dc7e6136ba414f2e2affbe39fa
*/
if(!$payr['payid'])
{
	$payr=mysqli_fetch_array($xingao->query("select * from payapi where payid='1' and checked=1"));
}

$spname="财付通双接口";
$partner = $payr['payuser'];                                	//财付通商户号
$key =  $payr['paykey'];											//财付通密钥

$return_url = $siteurl."api/pay/tenpay/payReturnUrl.php";		//显示支付结果页面,*替换成payReturnUrl.php所在路径
$notify_url = $siteurl."api/pay/tenpay/payNotifyUrl.php";		//支付完成后的回调处理页面,*替换成payNotifyUrl.php所在路径
?>