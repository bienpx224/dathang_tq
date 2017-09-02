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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

$ret=par($_REQUEST['ret']);

//日志
/*
$data=$ret.':'.date('Y-m-d H:i:s',time()).'';
$fileName = $_SERVER['DOCUMENT_ROOT'].'/api/pay/update_time.php';
file_put_contents($fileName, $data);
*/


//导步处理:主要--------------------------------------------------------------------------------------
if($ret=='result'||$ret=='success')
{
	if(strtoupper($_POST['res_result'])=='OK')
	{
		//充值单号
		$ddno=par($_POST['order_id']);	if(!$ddno){exit('NG');}//失败:通知接口已经收到信息
		
		
		//安全验证:验证KEY
		/*以下参数是接口返回,不可乱变更*/
		$str=	
			$_POST['pay_method'].
			$_POST['merchant_id'].
			$_POST['service_id'].
			$_POST['cust_code'].
			$_POST['sps_cust_no'].
			$_POST['sps_payment_no'].
			$_POST['order_id'].
			$_POST['item_id'].
			$_POST['pay_item_id'].
			$_POST['item_name'].
			$_POST['tax'].
			$_POST['amount'].
			$_POST['pay_type'].
			$_POST['auto_charge_type'].
			$_POST['service_type'].
			$_POST['div_settele'].
			$_POST['last_charge_month'].
			$_POST['camp_type'].
			$_POST['tracking_id'].
			$_POST['terminal_type'].
			$_POST['free1'].
			$_POST['free2'].
			$_POST['free3'].
			$_POST['request_date'].
			$_POST['res_pay_method'].
			$_POST['res_result'].
			$_POST['res_tracking_id'].
			$_POST['res_sps_cust_no'].
			$_POST['res_sps_payment_no'].
			$_POST['res_payinfo_key'].
			$_POST['res_payment_date'].
			$_POST['res_err_code'].
			$_POST['res_date'].
			$_POST['limit_second'];		

		$key=FeData('payapi','paykey',"payid='8' and checked=1");
		$key=sha1($str.$key);
		
/*$data=
'接口返回的参数:'.$str.'
接口返回的密码:'.strtoupper($_POST['sps_hashcode']).'
我们按参数生成的密码:'.strtoupper($key).'
返回时间:'.date('Y-m-d H:i:s',time()).'';
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/api/pay/update_time.php', $data);
*/
		if(strtoupper($_POST['sps_hashcode'])!=strtoupper($key)){exit('NG');}//失败:通知接口已经收到信息
		/*这里返回NG,是接口返回的还有其他参数没附加到sha1中生成*/
		
		

		//支付成功
		require_once($_SERVER['DOCUMENT_ROOT'].'/api/pay/paySave.php');
		echo 'OK';//成功:通知接口已经收到信息
		
	}else{
		//支付失败
		echo 'NG';//失败:通知接口已经收到信息
	}
}







//同步返回提示:不重要--------------------------------------------------------------------------------------
if($ret=='success'){XAts($tslx='',$color='info',$title=$LG['api.pay_20'],$content='',$button='',$exit='1',$buttonClose='1');}
elseif($ret=='cancel'){XAts($tslx='',$color='danger',$title=$LG['api.pay_21'],$content='',$button='',$exit='1',$buttonClose='1');}
elseif($ret=='error'){XAts($tslx='',$color='danger',$title=$LG['api.pay_22'],$content='',$button='',$exit='1',$buttonClose='1');}
?>