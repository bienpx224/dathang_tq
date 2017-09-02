<?php 
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/

//发送参数----------------------------------------------------------------------------------
$url			= 'https://fep.sps-system.com/f01/FepBuyInfoReceive.do';//正式网址
$merchant_id	= cadd(substr($payr['payuser'],0,5));
$service_id		= cadd(substr($payr['payuser'],-3));
$hashkey		= cadd($payr['paykey']);
$siteurl		= str_ireplace('http://','https://',$siteurl);//本接口需要SSL


//测试环境-测试资料:
//测试可不用ssl,正式时需要用ssl链接
/*	$url = 'https://stbfep.sps-system.com/Extra/BuyRequestAction.do';// 开发链接测试用URL
	//$url = 'https://stbfep.sps-system.com/f01/FepBuyInfoReceive.do';// 充值测试环境URL
	$merchant_id              ="19788";
	$service_id               ="001";
	$hashkey                  ="398a58952baf329cac5efbae97ea84ba17028d02";
	$siteurl='http://test3.xingaowl.com/';
*/



$cust_code			= "memberID{$Muserid}";
$sps_cust_no		= '';
$sps_payment_no		= '';
$order_id			= $ddno;//充值单号
$item_id			= 'OnlineTopup';//商品单号
$pay_item_id		= '';
$item_name			= "memberID-{$Muserid} memberName-{$Musername}";//必须有一个空格,特殊符号只支持:*-
$tax				= '';
$amount				= (int)($money_api);//只支持日币
$pay_type			= '0';
$auto_charge_type	= '';
$service_type		= '0';
$div_settele		= '';
$last_charge_month	= '';
$camp_type			= '';
$tracking_id		= '';
$terminal_type		= '';
$success_url		= "{$siteurl}api/pay/SoftBank/result_general.php?ret=success";
$cancel_url			= "{$siteurl}api/pay/SoftBank/result_general.php?ret=cancel";
$error_url			= "{$siteurl}api/pay/SoftBank/result_general.php?ret=error";
$pagecon_url		= "{$siteurl}api/pay/SoftBank/result_general.php?ret=result";//支付通知返回,正式需要用https (以上3个都是返回提示,不重要)
$free1 				= '' ;
$free2 				= '' ;
$free3 				= '' ;
$free_csv 			= '';
$request_date		= date('YmdHis',strtotime('+1 hours'));
$limit_second		= '';//超时设定(秒)


//链接送信信息数据(顺序不可变更,要完全按form里顺序一致,并且input要一致)
$result=$pay_method.$merchant_id.$service_id.$cust_code.$sps_cust_no.$sps_payment_no.$order_id.$item_id.$pay_item_id.$item_name.$tax.$amount.$pay_type.$auto_charge_type.$service_type.$div_settele.$last_charge_month.$camp_type.$tracking_id.$terminal_type.$success_url.$cancel_url.$error_url.$pagecon_url.$free1.$free2.$free3.$free_csv.$request_date.$limit_second.$hashkey;

//变换SHA1
//$result=mb_convert_encoding($result, 'Shift_JIS', 'UTF-8');
$sps_hashcode =sha1( $result );
?>



<form action="<?=$url?>" method="post" id="formSubmit"> 
  <input type="hidden" name="pay_method" value="<?=$pay_method?>">
  <input type="hidden" name="merchant_id" value="<?=$merchant_id?>">
  <input type="hidden" name="service_id" value="<?=$service_id?>">
  <input type="hidden" name="cust_code" value="<?=$cust_code?>">
  <input type="hidden" name="order_id" value="<?=$order_id?>">
  <input type="hidden" name="item_id" value="<?=$item_id?>" >
  <input type="hidden" name="pay_item_id" value="<?=$pay_item_id?>" >
  <input type="hidden" name="item_name" value="<?=$item_name?>">
  <input type="hidden" name="tax" value="<?=$tax?>">
  <input type="hidden" name="amount" value="<?=$amount?>">
  
  <input type="hidden" name="pay_type" value="<?=$pay_type?>">
  <input type="hidden" name="auto_charge_type" value="<?=$auto_charge_type?>"><!--需要空,不能0-->
  <input type="hidden" name="service_type" value="<?=$service_type?>">
  
  <input type="hidden" name="div_settele" value="<?=$div_settele?>">
  <input type="hidden" name="last_charge_month" value="<?=$last_charge_month?>">
  <input type="hidden" name="camp_type" value="<?=$camp_type?>">
  <input type="hidden" name="terminal_type" value="<?=$terminal_type?>">
  <input type="hidden" name="success_url" value="<?=$success_url?>"><!--成功-->
  <input type="hidden" name="cancel_url" value="<?=$cancel_url?>"><!--取消-->
  <input type="hidden" name="error_url" value="<?=$error_url?>"><!--错误-->
  <input type="hidden" name="pagecon_url" value="<?=$pagecon_url?>"><!--返回-->
  <input type="hidden" name="free1" value="<?=$free1?>">
  <input type="hidden" name="free2" value="<?=$free2?>">
  <input type="hidden" name="free3" value="<?=$free3?>">
  <input type="hidden" name="free_csv" value="<?=$free_csv?>">
  <input type="hidden" name="request_date" value="<?=$request_date?>">
  <input type="hidden" name="limit_second" value="<?=$limit_second?>">
  <input type="hidden" name="sps_hashcode" value="<?=$sps_hashcode?>">

  <!--<input type="submit" value="支付">-->
</form>
<script>document.getElementById('formSubmit').submit();</script>