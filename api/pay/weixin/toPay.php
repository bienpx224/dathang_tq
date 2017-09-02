<?php
if(!defined('InXingAo'))
{
	exit('No InXingAo');
}

ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "lib/WxPay.Api.php";
require_once "WxPay.NativePay.php";

$notify = new NativePay();


//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 */
$input = new WxPayUnifiedOrder();
$input->SetBody("在线支付");//内容
$input->SetAttach("会员ID:".$Muserid."-会员登录账号:".$Musername);//附加
$input->SetOut_trade_no($ddno);//网站订单号
$input->SetTotal_fee((int)($money_api*100));//金额(分)
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("充值");//商品标签
$input->SetNotify_url($siteurl."api/pay/weixin/notify.php");//返回处理
$input->SetTrade_type("NATIVE");//支付类型
$input->SetProduct_id('');//产品ID
$input->SetSpbill_create_ip($_SERVER["SERVER_ADDR"]);
$result=$notify->GetPayUrl($input);

if($result["code_url"]){
	//成功
	$url2=$result["code_url"];
}else{
	//失败
	echo "无返回二维码数据<br>";
	echo "错误代码：".$result['err_code']."错误代码描述：".$result['err_code_des']."<br>";
	echo '如果没有错误代码,一般是接口资料填写错误:请检查这3项 MCHID,KEY,APPID';
	exit;
}

?>
	<meta charset="utf-8">
	<link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<br><br><br><br>
	
	<div class="alert alert-block alert-success fade in alert_cs col-md-5" style="font-size:24px;font-weight: bolder;" align="center">
	<?=$LG['function.158']//请用手机微信扫描支付?><br>
	<?=$LG['function.159']//(支付完成后,可关闭本页面)?><br>
	
	<br/><br/>
	<img src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:150px;height:150px;"/>
	</div>
