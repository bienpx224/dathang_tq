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

require_once 'config.php';

$payr=FeData('payapi','payid,paykey',"payid='9' and checked=1");
if(!$payr['payid']){XAts($tslx='',$color='danger',$title='该充值接口已关闭',$content='',$button='',$exit='1',$buttonClose='1');}//接口已关闭

//导步处理:主要--------------------------------------------------------------------------------------
if(!$ret)
{
/*
	返回内容:
	$_POST['id']
	$_POST['status']
	$_POST['amount']
	$_POST['currency']
	$_POST['time']
	$_POST['note']
*/
	if($_POST['verify_sign']!=sign($_POST,$payr['paykey'])){exit('签名失败');}//失败:通知接口已经收到信息
	
	if($_POST['status']=='success')
	{
		//充值单号
		$ddno=par($_POST['reference']);	if(!$ddno){exit('订单号空');}//失败:通知接口已经收到信息
		
		//支付成功
		require_once($_SERVER['DOCUMENT_ROOT'].'/api/pay/paySave.php');
		echo 'success';//成功:通知接口已经收到信息
		
	}else{
		//支付失败
		exit('充值失败');//失败:通知接口已经收到信息
	}
}


function sign($params,$token)
{
	ksort($params);
	$sign_str = "";
	foreach ($params as $key => $val) 
	{   
		if(!$val){continue;}//排除空
		if($key=='verify_sign'){continue;}            
		$sign_str .= sprintf("%s=%s&", $key, $val);
	}
	return md5($sign_str . strtolower(md5($token)));
}
	
	
//同步返回提示:不重要--------------------------------------------------------------------------------------
if($ret=='get'){
	if(is_json($_REQUEST['response'])){$json=json_decode($_REQUEST['response'], true);}

	/*支付成功 success, 支付失败failure, 支付中pending, 交易关闭closed*/
	if($json['status']=='success'){XAts($tslx='',$color='info',$title=$LG['api.pay_20'],$content='',$button='',$exit='1',$buttonClose='1');}
	elseif($json['status']=='pending'){XAts($tslx='',$color='info',$title=$LG['api.pay_22_1'],$content='',$button='',$exit='1',$buttonClose='1');}
	elseif($json['status']=='failure'){XAts($tslx='',$color='danger',$title=$LG['api.pay_19_1'],$content='',$button='',$exit='1',$buttonClose='1');}
	elseif($json['status']=='closed'){XAts($tslx='',$color='danger',$title=$LG['api.pay_21'],$content='',$button='',$exit='1',$buttonClose='1');}

}
?>