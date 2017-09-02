<?php 
//获取汇率
function exchangeAPI($from,$to)
{	
	global $license_key,$APIcustomer,$APIkey;
	
	//发送查询
	$post_data = array('from'=>$from,'to'=>$to,'key'=>$license_key,'APIcustomer'=>$APIcustomer,'APIkey'=>$APIkey,'source'=>$_SERVER['HTTP_HOST']);
	$url='http://api.xingaowl.com/Exchange/';//接口地址
	$var=send_post($url,$post_data);
	if(!is_numeric($var)){return $var;}
	return $var;
}
?>