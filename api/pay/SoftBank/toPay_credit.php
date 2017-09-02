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

/*
	正式时需要用ssl链接
*/

//正式环境-通用参数:
$url = 'https://api.sps-system.com/api/xmlapi.do';//正式网址
$merchant_id              = cadd(substr($payr['payuser'],0,5));
$service_id               = cadd(substr($payr['payuser'],-3));
$hashkey                  = cadd($payr['paykey']);

$cc_number                = par($_POST['cc_number']);//信用卡资料:卡号
$cc_expiration            = par($_POST['cc_expiration']);//信用卡资料:有效期
$security_code            = par($_POST['security_code']);//信用卡资料:信用卡背面的3位码




//测试环境-测试资料:
/*
	$url = 'https://stbfep.sps-system.com/api/xmlapi.do';//测试网址
	$merchant_id              = "62022";
	$service_id               = "001";
	$hashkey                  = "d7b6640cc5d286cbceab99b53bf74870cf6bce9c";
	$cc_number                = "5250729026209007";//信用卡资料:卡号
	$cc_expiration            = "201103";//信用卡资料:有效期
	$security_code            = "798";//信用卡资料:信用卡背面的3位码
*/

if(!$cc_number||!$cc_expiration||!$security_code){exit("<script>alert('{$LG['api.pay_16']}(CR0003)');goBack('c');</script>");}











//第一步:发送查询信用卡是否有效------------------------------------------------------------------

//支付参数:
$cust_code                = "memberID{$Muserid}";
$order_id                 = $ddno;//充值单号
$item_id                  = "OnlineTopup";//商品单号
$item_name                = "memberID-{$Muserid} memberName-{$Musername}";//特殊符号只支持:*-
$request_date             = date('YmdHis',strtotime('+1 hours'));//20170306153345:必须是日本时间,因此在中国时间上加1小时整
$amount                   = (int)($money_api);//只支持日币
$tax                      = "";//税
$free1                    = "";
$free2                    = "";
$free3                    = "";
$order_rowno              = "";
$sps_cust_info_return_flg = "1";
$cust_manage_flg          = "0";
$encrypted_flg            = "0";
$limit_second             = '600';//超时设定(秒)






// 变换Shift_JIS
$merchant_id              = mb_convert_encoding($merchant_id, 'Shift_JIS', 'UTF-8');
$service_id               = mb_convert_encoding($service_id, 'Shift_JIS', 'UTF-8');
$cust_code                = mb_convert_encoding($cust_code, 'Shift_JIS', 'UTF-8');
$order_id                 = mb_convert_encoding($order_id, 'Shift_JIS', 'UTF-8');
$item_id                  = mb_convert_encoding($item_id, 'Shift_JIS', 'UTF-8');
$item_name                = mb_convert_encoding($item_name, 'Shift_JIS', 'UTF-8');
$tax                      = mb_convert_encoding($tax, 'Shift_JIS', 'UTF-8');
$amount                   = mb_convert_encoding($amount, 'Shift_JIS', 'UTF-8');
$free1                    = mb_convert_encoding($free1, 'Shift_JIS', 'UTF-8');
$free2                    = mb_convert_encoding($free2, 'Shift_JIS', 'UTF-8');
$free3                    = mb_convert_encoding($free3, 'Shift_JIS', 'UTF-8');
$order_rowno              = mb_convert_encoding($order_rowno, 'Shift_JIS', 'UTF-8');
$sps_cust_info_return_flg = mb_convert_encoding($sps_cust_info_return_flg, 'Shift_JIS', 'UTF-8');
$cc_number                = mb_convert_encoding($cc_number, 'Shift_JIS', 'UTF-8');
$cc_expiration            = mb_convert_encoding($cc_expiration, 'Shift_JIS', 'UTF-8');
$security_code            = mb_convert_encoding($security_code, 'Shift_JIS', 'UTF-8');
$cust_manage_flg          = mb_convert_encoding($cust_manage_flg, 'Shift_JIS', 'UTF-8');
$encrypted_flg            = mb_convert_encoding($encrypted_flg, 'Shift_JIS', 'UTF-8');
$request_date             = mb_convert_encoding($request_date, 'Shift_JIS', 'UTF-8');
$limit_second             = mb_convert_encoding($limit_second, 'Shift_JIS', 'UTF-8');
$hashkey                  = mb_convert_encoding($hashkey, 'Shift_JIS', 'UTF-8');


// 链接送信信息数据
$result =$merchant_id .$service_id .$cust_code .$order_id .$item_id .$item_name .$tax .$amount .$free1 .$free2 .$free3 .$order_rowno .$sps_cust_info_return_flg .$cc_number .$cc_expiration .$security_code .$cust_manage_flg .$encrypted_flg .$request_date .$limit_second .$hashkey;

// 变换SHA1
$sps_hashcode = sha1( $result );

// 生成POST数据
$postdata =
    "<?xml version=\"1.0\" encoding=\"Shift_JIS\"?>" .
    "<sps-api-request id=\"ST01-00101-101\">" .
        "<merchant_id>"                 . $merchant_id              . "</merchant_id>" .
        "<service_id>"                  . $service_id               . "</service_id>" .
        "<cust_code>"                   . $cust_code                . "</cust_code>" .
        "<order_id>"                    . $order_id                 . "</order_id>" .
        "<item_id>"                     . $item_id                  . "</item_id>" .
        "<item_name>"                   . base64_encode($item_name) . "</item_name>" .
        "<tax>"                         . $tax                      . "</tax>" .
        "<amount>"                      . $amount                   . "</amount>" .
        "<free1>"                       . base64_encode($free1)     . "</free1>" .
        "<free2>"                       . base64_encode($free2)     . "</free2>" .
        "<free3>"                       . base64_encode($free3)     . "</free3>" .
        "<order_rowno>"                 . $order_rowno              . "</order_rowno>" .
        "<sps_cust_info_return_flg>"    . $sps_cust_info_return_flg . "</sps_cust_info_return_flg>" .
        "<dtls>" .
        "</dtls>" .
        "<pay_method_info>" .
            "<cc_number>"               . $cc_number                . "</cc_number>" .
            "<cc_expiration>"           . $cc_expiration            . "</cc_expiration>" .
            "<security_code>"           . $security_code            . "</security_code>" .
            "<cust_manage_flg>"         . $cust_manage_flg          . "</cust_manage_flg>" .
        "</pay_method_info>" .
        "<pay_option_manage>" .
        "</pay_option_manage>" .
        "<encrypted_flg>"               . $encrypted_flg            . "</encrypted_flg>" .
        "<request_date>"                . $request_date             . "</request_date>" .
        "<limit_second>"                . $limit_second             . "</limit_second>" .
        "<sps_hashcode>"                . $sps_hashcode             . "</sps_hashcode>" .
    "</sps-api-request>";


//发送数据
$data=creditSend($url,$pwd="{$merchant_id}{$service_id}:{$hashkey}",$postdata);


//echo $data;exit;
/*
正确:
<?xml version='1.0' encoding='utf-8' ?>
  <sps-api-response id="ST01-00101-101">
    <res_result>OK</res_result>
    <res_sps_transaction_id>B62022001ST010010110100208372157</res_sps_transaction_id>
    <res_tracking_id>00000608800180</res_tracking_id>
    <res_pay_method_info>
      <cc_company_code>99665</cc_company_code>
      
      <recognized_no>123456</recognized_no>
      
    </res_pay_method_info>
    <res_sps_info>
      <res_sps_cust_no/>
      <res_sps_payment_no/>
    </res_sps_info>
    <res_process_date>20170306163108</res_process_date>
    <res_err_code/>
    <res_date>20170306163109</res_date>
</sps-api-response>
 
 
错误如:
<?xml version='1.0' encoding='Shift_JIS' ?>
  <sps-api-response>
    <res_result>NG</res_result>
    <res_err_code>10110999</res_err_code>//表示错误代码
    <res_date>20170306163921</res_date>
  </sps-api-response>
*/













//第二步:按返回数据发送扣费信息------------------------------------------------------------------

//获取第一步的数据
$xml = simplexml_load_string($data);//XML 字符串载入对象中
$res_result=trim($xml->res_result);
if($res_result!='OK'){exit("<script>alert('{$LG['api.pay_17']}(CR0001)');goBack('c');</script>");}//第一步:失败

// API送信数据
$sps_transaction_id       = trim($xml->res_sps_transaction_id);
$tracking_id              = trim($xml->res_tracking_id);
$request_date             = date('YmdHis',strtotime('+1 hours'));//20170306153345:必须是日本时间,因此在中国时间上加1小时整
$processing_datetime      = "";
$limit_second             = "";

// 变换Shift_JIS
$merchant_id              = mb_convert_encoding($merchant_id, 'Shift_JIS', 'UTF-8');
$service_id               = mb_convert_encoding($service_id, 'Shift_JIS', 'UTF-8');
$sps_transaction_id       = mb_convert_encoding($sps_transaction_id, 'Shift_JIS', 'UTF-8');
$tracking_id              = mb_convert_encoding($tracking_id, 'Shift_JIS', 'UTF-8');
$processing_datetime      = mb_convert_encoding($processing_datetime, 'Shift_JIS', 'UTF-8');
$request_date             = mb_convert_encoding($request_date, 'Shift_JIS', 'UTF-8');
$limit_second             = mb_convert_encoding($limit_second, 'Shift_JIS', 'UTF-8');
$hashkey                  = mb_convert_encoding($hashkey, 'Shift_JIS', 'UTF-8');


// 送信数据信息链接
$result =$merchant_id .$service_id .$sps_transaction_id .$tracking_id .$processing_datetime .$request_date .$limit_second .$hashkey;

// 变化SHA1
$sps_hashcode = sha1( $result );

// 生成POST数据
$postdata =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<sps-api-request id=\"ST02-00101-101\">" .
		"<merchant_id>"                 . $merchant_id              . "</merchant_id>" .
		"<service_id>"                  . $service_id               . "</service_id>" .
		"<sps_transaction_id>"          . $sps_transaction_id       . "</sps_transaction_id>" .
		"<tracking_id>"                 . $tracking_id              . "</tracking_id>" .
		"<processing_datetime>"         . $processing_datetime      . "</processing_datetime>" .
		"<request_date>"                . $request_date             . "</request_date>" .
		"<limit_second>"                . $limit_second             . "</limit_second>" .
		"<sps_hashcode>"                . $sps_hashcode             . "</sps_hashcode>" .
	"</sps-api-request>";



//发送数据
$data=creditSend($url,$pwd="{$merchant_id}{$service_id}:{$hashkey}",$postdata);

//获取第二步的数据
$xml= simplexml_load_string($data);//XML 字符串载入对象中
$res_result=trim($xml->res_result);
$transaction_id=trim($xml->res_sps_transaction_id);
if($res_result!='OK'||!$transaction_id){exit("<script>alert('{$LG['api.pay_18']}(CR0002)');goBack('c');</script>");}//第二步:失败


//echo $data;exit;
/*
正确返回:
<?xml version='1.0' encoding='Shift_JIS' ?>
  <sps-api-response id="ST02-00101-101">
	<res_result>OK</res_result>
	<res_sps_transaction_id>B62022001ST020010110100208386318</res_sps_transaction_id>
	<res_process_date>20170306165430</res_process_date>
	<res_err_code/>
	<res_date>20170306165430</res_date>
  </sps-api-response>
*/	











//支付成功:处理充值---------------------------------------------------------------------------------
/*
	如果支付失败不会执行到此处,反之执行到此步表示支付成功
*/
//$ddno=$ddno;//该接口没有跳转外站支付,也没有返回$ddno,因此变量不变 
require_once($_SERVER['DOCUMENT_ROOT'].'/api/pay/paySave.php');



exit ("<script>alert('{$LG['api.pay_19']}');window.opener.location.reload();location='/xamember/money/money_czbak.php';</script>");











//通用发送程序----------------------
function creditSend($url,$pwd,$postdata)
{
	$curl = curl_init(); 
	curl_setopt($curl, CURLOPT_URL, $url); //网址
	curl_setopt($curl, CURLOPT_USERPWD,$pwd); //密码
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata ); //发送数据
	curl_setopt($curl, CURLOPT_HTTPHEADER,array( 'Content-Type: application/xml' ));//类型
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($curl, CURLOPT_HEADER, false); 
	curl_setopt($curl, CURLOPT_POST, true); 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)"); 
	$data =curl_exec($curl); 
	curl_close($curl); 
	
	return $data;
}

