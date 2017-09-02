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

if(!defined('InXingAo')){exit('No InXingAo');}





//绑定openid:关注本站微信公众号生成的openid
function save_wx_openid($typ,$wx_binding_tmp,$openid)
{
	//不用多语种,此为接口方执行,无法识别语种
	global $ON_WX;
	if($typ=='add'){
		$name='绑定';
	}elseif($typ=='del'){
		$name='解绑';
		$where=" and  wx_openid='{$openid}'";
	}
	
	if(!$ON_WX){return $name.'失败:网站未开通该功能!';}
	if(!$wx_binding_tmp||!$openid){return $name.'失败:参数错误!';}
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$rs=FeData('member','userid,wx_openid,wx_binding_time',"wx_binding_tmp='{$wx_binding_tmp}' and checked=1 {$where}");
	if(!$rs['userid']){return $name.'失败:填写内容错误或已操作过!';}
	if($rs['wx_binding_time']<strtotime('-30 minutes')){return $name.'失败:已经超过有效期,请在网站点击[我要绑定]生成新验证码!';}
	
	if($typ=='add'){
		if($rs['wx_openid']){return $name.'失败:已经绑定过,无需要重复绑定,如要绑定请先解除旧绑定!';}
	}elseif($typ=='del'){
		if(!$rs['wx_openid']){return $name.'失败:已经解绑过,可刷新网页查看最新状态!';}
		$openid='';
	}
	
	$xingao->query("update member set wx_openid='{$openid}',wx_binding_tmp='0' where userid='{$rs['userid']}'");
	SQLError('save_wx_openid');
	
	if(mysqli_affected_rows($xingao)>0){return "恭喜您,{$name}成功!";}else{return "{$name}失败!";}
}


//获取access_token
function access_token()
{
	 require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	 global $mpWeixin_appid,$mpWeixin_appsecret;

	//每天中能获取200次,2小时(120分钟)过期,因此要保存到数据库
	$rs=FeData('config','id,value1,value2',"name='Weixin_access_token'");
	$access_token=cadd($rs['value2']);
	 
	if(!$rs['id']||DateDiff(time(),$rs['value1'],'i')>=120||!$rs['value2'])
	{
		$url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$mpWeixin_appid.'&secret='.$mpWeixin_appsecret;
		$r=send_get($url);
		$access_token=$r['access_token'];
		//print_r($r);
		
		if(!$rs['id'])
		{
			$xingao->query("insert into config (name,value1,value2) values ('Weixin_access_token','".time()."','".add($access_token)."')");
		}else{
			$xingao->query("update config set value1='".time()."',value2='".add($access_token)."' where id='{$rs['id']}'");
		}
	}

	return $access_token;
}


//发送json给指定的接口
function Weixin_post_json($url, $jsonData){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$jsonData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	if (curl_errno($ch)) {
	return curl_error($ch);
	}
	curl_close($ch);
	return $tmpInfo;
}
?>