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

//通用设置
$ppt='';$ppt_api='';set_time_limit(0);//批量处理时,速度慢,设为永不超时

//内部自动更新--------------------------------------------------------------------------
$update_auto=update_time('yundan_status','-30 minutes');//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
if ($update_auto&&$off_statusauto)
{
	//从 待支付/出库>状态5 开始是类似的，所以用自动循环
	$update_status=4;
	for ($i=5; $i<=29; $i++) 
	{
		
		$zhi='status_on_'.$i;$status_on=spr($$zhi); //该状态是否启用
		if ($status_on)
		{
		
			$status=spr($update_status);//上一个启用的状态
			$update_status=$i;//要更新的状态
			$zhi='statustime_update'.$i;$statustime_update=spr($$zhi);//从上一次更新至这状态的时间
			$zhi='whether'.$i;$whether=spr($$zhi);//周六，周日是否也算时间
			
			//更新-开始
			if ($status>0&&$update_status>0&&$statustime_update>0)
			{
				$query="select * from yundan where statusauto='1' and status='{$status}' and statustime<='".strtotime('-'.$statustime_update.' hours')."'";
				$sql=$xingao->query($query);
				while($rs=$sql->fetch_array())
				{ 
			/*		if ($i>6){ //测试
					echo "状态".$i."值".date("Y-n-d H:i:s",$rs[statustime]);
					exit ();
					} 
			*/
					$ApartTime=intval(workDays($rs['statustime'],time(),'H',$whether)-$statustime_update);
					if ($ApartTime>=0)
					{
						//防止不自动运行此文件更新,所以计算保存时间
						$time=strtotime("-".(int)$ApartTime." hours");//$ApartTime要整数
						yundan_updateStatus($rs,$update_status,$time,1);
						
						$ppt.=$rs['ydh'].',';
						$ppt_i+=1;
					}
				}//while($rs=$sql->fetch_array())
			}
			//更新-结束
		}
	}
	
	//echo '<strong>状态自动更新-正常更新单号:</strong>'.DelStr($ppt);
	$ppt_update.= "有".spr($ppt_i)."个运单已更新！";
}







//调用API自动更新为完成--------------------------------------------------------------------------
$update_auto=update_time('yundan_status_api','-3 hours');//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
if ($update_auto&&$off_statusauto&&$status_api_ok)
{
	//type值不同,不能直接用上面的$url
	$query="select * from yundan where statusauto='1' and status>='13' and status<'30' and gnkd<>''  and statustime<='".strtotime('-24 hours')."'";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{ 
		//用接口查询，不用读取数据库，因为在查询页面里查询时，如果是已签收已经自行更新运单为签收
		$sign=0;
		$com=par($rs['gnkd']);//com快递公司代码
		$nu=par($rs['gnkdydh']);//nu快递单号
		if(!$nu){$nu=cadd($rs['ydh']);}

		//发送查询
		$post_data = array('com'=>$com,'nu'=>$nu,'key'=>$license_key,'APIcustomer'=>$APIcustomer,'APIkey'=>$APIkey,'source'=>$_SERVER['HTTP_HOST'],'order'=>$order);  
		$url='http://api.xingaowl.com/';//接口地址
		$send=send_post($url,$post_data); 
		if(!is_array($send)&&$send){$send=explode('|||',$send);}
		
		if ($send[0]==1&&$send[2]==1)
		{
			yundan_updateStatus($rs,$update_status=30,0,1);
			$ppt_api.=$rs['ydh'].',';
		}
		//sleep(1);//秒，暂停多久执行，防止被禁用
		
	}//while($rs=$sql->fetch_array())
	$ppt_update.=  '<br><br><strong>状态自动更新-API更新完成单号:</strong>'.DelStr($ppt_api);
}


$ppt_update.=  '<br><br>如无提示说明无任何更新!';
echo  '<script src="/public/kindeditor/php/JS.php" type="text/javascript"></script>';


if (update_time('yundan_del','-1 days'))//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
{
	//删除过期运单-----------------------------------------------------------------------------------
	if(!$off_delbak)//不要放上面update_time里,因为如果后台暂时关闭$off_delbak时,会马上自动执行这里
	{
		$yundan_del_time=spr($yundan_del_time);
		if($yundan_del_time>0)
		{
			$where="status=30  and statustime<=".strtotime('-'.$yundan_del_time.' month')."";
			//删除文件,查询包裹ID
			$query="select s_shenfenimg_z,s_shenfenimg_b,tax_img,bgid,ydid from yundan where {$where}";
			$sql=$xingao->query($query);
			while($rs=$sql->fetch_array())
			{
				DelFile($rs['tax_img']);
				DelFile($rs['s_shenfenimg_z']);
				DelFile($rs['s_shenfenimg_b']);
				
				//删除包裹
				if($rs['bgid'])
				{
					$query_bg="select op_06_img,bgid from baoguo where bgid in ({$rs[bgid]}) and status in (4,9,10)";
					$sql_bg=$xingao->query($query_bg);
					while($bg=$sql_bg->fetch_array())
					{
						$num=mysqli_num_rows($xingao->query("select * from yundan where find_in_set('{$bg[bgid]}',bgid) and status<>30 "));
						if(!$num)
						{
							DelFile($bg['op_06_img']);//删除文件
							$xingao->query("delete from baoguo where bgid='{$bg[bgid]}'");SQLError('删除包裹');
							wupin_del('baoguo',$bg['bgid']);
						}
					}
				}
				//删除状态记录
				$xingao->query("delete from yundan_bak where ydid='{$rs[ydid]}'");SQLError('删除状态记录表');
				//删除主信息
				$xingao->query("delete from yundan where ydid='{$rs[ydid]}'");SQLError('删除主信息');
				wupin_del('yundan',$rs['ydid']);
				
			}
		}
	}
}
?>