<?php 
$nu='XA201606170003US';//要查询的运单号

//基本配置
$userid='10000';//会员ID
$key='PYKH6dfzkes4pvvg8xec29py'; //API KEY 用md5 32位加密 (如没有请联系客服申请获取)
$url='http://xxx';//接口地址：请把xxx改为我们网址



//部分部分
$key=md5(md5($key).trim($nu)); //API KEY+运单号 md5 32位加密
$url=$url.'/api/yundan/status.php?userid='.$userid.'&key='.$key.'&nu='.$nu.'&order=asc';//接口地址
$content=file_get_contents($url); 

if($content)
{
	//查询成功
	$arr=(array)json_decode($content,true);
	
	echo '查询提示：'.$arr['message'].'<br>';
	echo '查询状态：'.$arr['status'].'<br>';//请看接口文档状态代码说明
	echo '运单状态：'.$arr['state'].'<br><br>';//请看接口文档状态代码说明
	
	//输出状态内容
	foreach($arr['data'] as $arrkey=>$value)
	{
		echo ' 时间：'.$arr['data'][$arrkey]['time'];
		echo ' 状态：'.$arr['data'][$arrkey]['context'];
		echo '<br>';
	}
}else{
	//查询失败
	echo '查询失败，无返回任何数据，可能是网址错误';
}

?>