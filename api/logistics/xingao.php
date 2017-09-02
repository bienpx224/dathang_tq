<?php 
//@set_time_limit(10);//执行超时(秒)
//$rs['api_time']=0;//测试

//如果已签收完成,不调用API
if($rs['api_time']&&$rs['api_status']&&$rs['api_comnu']==($com.$nu)&&spr($rs['status'])==30){$closeApi=1;}

//开始调用API
$data='';$succeed=0;$uptime=60;//(分)API多久更新一次 （接口限制最短30分钟，所以不能小于30分钟）
if(!$closeApi && ($rs['api_time']<=strtotime('-'.$uptime.' minutes')||!$rs['api_status']||$rs['api_comnu']!=($com.$nu)) )
{
	
	//发送查询
	$post_data = array('com'=>$com,'nu'=>$nu,'key'=>$license_key,'APIcustomer'=>$APIcustomer,'APIkey'=>$APIkey,'source'=>$_SERVER['HTTP_HOST'],'order'=>$order);
	$url='http://api.xingaowl.com/';//接口地址
	$send=@send_post($url,$post_data);
	
	
	if(!is_array($send)&&$send){$send=explode('|||',$send);}
	if($send[0]==1)
	{
		//查询成功
		$data=$send[1];
		
		//把查询更新到数据库
		$xingao->query("update yundan set api_status='".add($data)."',api_time='".time()."',api_comnu='".$com.$nu."' where ydid='{$rs[ydid]}'"); 
		
		//快递状态已签收时更新运单为已签收
		if ($send[2]==1)//if($send[2]==1){echo '已签收';}
		{
			yundan_updateStatus($rs,$update_status=30,0,0);
		}
		$succeed=1;$rs['api_time']=time();
	}elseif(!$APImember){
		//查询失败
		echo '兴奥API接口查询失败：'.$send[1];
		$succeed=0;
	}
}


//失败时从数据库获取
if(!$succeed&&$rs['api_status']&&$rs['api_comnu']==($com.$nu))
{
	$data=$rs['api_status'];//不能用任何cadd,编码会无法转换
	$succeed=1;
}
?>