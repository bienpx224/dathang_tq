<?php
/*
    请求报文说明: https://docs.nihaopay.com/api/v1.2/cn/#创建安全支付
	 https://docs.nihaopay.com/api/v1.2/cn/#接口规则
	 可以支持人民币,见上面网址:3.人民币金额
*/

require_once 'config.php';


//生成报文--------------------------------------------------------------------------------------
//付款币种:网站使用人民币就使用rmb_amount，外币使用amount

if($money_rmb)
{
	$data=array(
		'rmb_amount'=> (int)($money_rmb*100),//单位(分)
	 );
}else{
	$data=array(
		'amount'=> $payr['currency']=='JPY'?(int)($money_api):(int)($money_api*100),//单位(分),日币没有分
	 );
}


$data=array_merge($data,
	array(
		'currency'      => $payr['currency'],//USD 美元, JPY 日元, HKD 港币, GBP 英镑, EUR 欧元 和CAD 加拿大元
		'vendor'        => $vendor,//支持支付宝 alipay，微信支付 wechatpay和银联 unionpay
		'reference'     => str_ireplace('_','-',$ddno),//商户订单号，1-30位(支持字母，数字和"-",不支持"_"符号
		'ipn_url'       => RETURN_URL,//支付通知接收的URL (CALLBACK_URL不管是否返回过,return_url都会返回)
		'callback_url'  => CALLBACK_URL,//支付成功后浏览器返回商户网站的URL
		'description'   => "memberID-{$Muserid} memberName-{$Musername}",//订单描述，可以订单商品信息摘要等，会显示在支付页面和客户付款收据中。
		'note'          => '',//供商家使用的备注字段，NihaoPay不做处理，在返回时，原数据将返回给商户。
		'terminal'      => $terminal,//应用场景:ONLINE=PC浏览器	WAP=手机浏览器或微信浏览器
		'timeout'       => 120 //订单超时时间(分)
	)
);







//生成加密--------------------------------------------------------------------------------------
class NihaopayApi {
    public static $bearerToken;
    //const API_URL = "http://api.test.nihaopay.com/v1.1";//for sandbox environment
    //const API_URL = "http://localhost:8080/HazePGW/v1.1";//for sandbox environment
    //const API_URL = "https://api.nihaopay.com/v1.1";//正式用

    public function __construct($token) {
        self::$bearerToken = "Bearer " . $token;
    }
    
    public function getToken() {
        return self::$bearerToken;
    }

}

$nihaopayApi = new NihaopayApi(TOKEN);
$accessToken = $nihaopayApi->getToken();
$headers = array(
	"authorization: $accessToken",
);


//发送--------------------------------------------------------------------------------------
$securePayURL = API_URL . "/transactions/securepay";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $securePayURL);
curl_setopt($ch, CURLOPT_POST,true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
if (!$response) {
    var_dump($securePayURL);
    var_dump($data);
	die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
}
curl_close($ch);
echo $response;