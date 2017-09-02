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

//测试：http://zy/api/yundan/status.php?key=NZCuhBnKncVrwGhZZEcYRGbzXsWhZNEH&nu=XA10034161209006USA

//获取
$userid=spr($_GET['userid']);
$key=par($_GET['key']);
$nu=par($_GET['nu']);
$order=par($_GET['order']);

//如果非会员接口,则共公查询类型
if(!$userid){$publicTyp=1;}

//验证
$APIstatus=0;$APImessage='ok';
if($APIstatus!=2&&!$off_api){$APIstatus=2;$APImessage='网站总接口已关闭';}
if($APIstatus!=2&&!$key){$APIstatus=2;$APImessage='key参数不能为空';}
if($APIstatus!=2&&!$nu){$APIstatus=2;$APImessage='nu参数不能为空';}

if($APIstatus!=2&&$publicTyp)
{
	//公共KEY
	if($APIstatus!=2&&!$ON_api_yundanStatus){$APIstatus=2;$APImessage='共公查询接口已关闭';}
	if($APIstatus!=2&&$api_yundanStatus_key!=$key){$APIstatus=2;$APImessage='key参数错误';}

}elseif($APIstatus!=2&&!$publicTyp){
	//会员KEY
	$user=FeData('member','userid,username,api,key,api_yd_query,api_yd_add',"userid='{$userid}'");
	if($APIstatus!=2&&!$user['api']){$APIstatus=2;$APImessage='您的接口已被关闭';}
	if($APIstatus!=2&&md5(md5($user['key']).$nu)!=$key){$APIstatus=2;$APImessage='您的会员ID或KEY错误';}
	if($APIstatus!=2&&!$user['api_yd_query']){$APIstatus=2;$APImessage='您的接口权限不足';}
}


//处理
$json='';
if($APIstatus!=2)
{
	$sqlydh=str_replace(',',"','",$nu);
	$query="select ydid,ydh,status,statustime,api_status,api_time,api_comnu,gnkd,gnkdydh from yundan where ydh in ('".$sqlydh."') order by ydh desc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$i+=1;
		//主表------------------------------------------------------
		if(spr($rs['status'])<=1)
		{
			$json.='{"time":"'.DateYmd($rs['statustime'],1).'","context":"'.status_name(spr($rs['status'])).'"},';
		}
		
		//记录表------------------------------------------------------
		if(spr($rs['status'])>1)
		{
			$query_bak="select * from yundan_bak where ydid={$rs[ydid]} order by statustime {$order}";
			$sql_bak=$xingao->query($query_bak);
			while($bak=$sql_bak->fetch_array())
			{
				$json.='{"time":"'.DateYmd($bak['statustime'],1).'","context":"'.status_name($bak['status']).'"},';
			}
		}
		
		//快递查询------------------------------------------------------
		if((spr($rs['status'])>=20&&$rs['gnkd']&&$rs['gnkdydh'])||(spr($rs['status'])>=13&&$rs['gnkd']))
		{
			$com=par($rs['gnkd']);//com快递公司代码
			$nu=par($rs['gnkdydh']);//nu快递单号
			if(!$nu){$nu=$rs['ydh'];}
			
			//API查询
			if($order!='desc'&&$order!='asc'){$order='asc';}
			$APImember=1;
			require($_SERVER['DOCUMENT_ROOT'].'/api/logistics/xingao.php');//返回$succeed，$data
			if(is_json($data))
			{
				$arr=(array)json_decode($data,true);
				$status=$arr['status'];
				foreach($arr['data'] as $arrkey=>$value)
				{
					$json.='{"time":"'.$arr['data'][$arrkey]['time'].'","context":"'.$arr['data'][$arrkey]['context'].'"},';
				}
			}
		}
		
		$APIstatus=1;//查询状态
		$APIstate=0;//运单状态
		if(spr($rs['status'])==30){$APIstate=3;}//3已签收
		
		$json=DelStr($json);
		$json=',"data":['.$json.']';
	}
}

$json='{"message":"'.$APImessage.'","status":"'.$APIstatus.'","state":"'.$APIstate.'"'.$json.'}';
echo $json;//返回结果
