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

if (update_time('baoguo','-1 days'))//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
{
	//超过时间后自动更新为仓储－开始
	if($ON_ware)
	{
		$where=baoguo_fahuo(2)." and status in (2,3) and ware=0 ";//可以发货并且状态已入库的
		
		//不是今天取出的
		$start =strtotime(date('Y-m-d')." 00:00:00");
		$where.="and ware_out_time<'{$start}'";

		$query_up="select * from baoguo where userid>0 {$where}";//非待领包裹才仓储,待领包裹时没有会员组,因此没有设置免费仓储时间
		$sql_up=$xingao->query($query_up);
		while($rs_up=$sql_up->fetch_array())
		{
			//其他不能转仓储的原因：
			//如果已有下过运单，则不再仓储
			$YDnum=mysqli_num_rows($xingao->query("select ydid from yundan where find_in_set('{$rs_up[bgid]}',bgid) "));
			if($YDnum>0){continue;}
			
			//end---------
			

			//检查是否过期
			$free_days_have=bg_ware_days(1,$rs_up);//还有免费仓储天数
			
			if($free_days_have<0)
			{
				$xingao->query("update baoguo set ware='1',ware_time='".time()."' where bgid='{$rs_up[bgid]}' ");
				SQLError('自动更新为仓储');

				//更新后发站内信息
				if ($bg_ware_msg)
				{
					$send_title=LGtag($LG['baoguo.Xcall_update_1'],'<tag1>=='.$rs_up['bgydh']);
					$send_content_msg=$LG['baoguo.Xcall_update_2'];
					$send_file='';
	
					SendMsg($rs_up['userid'],$rs_up['username'],$send_title,$send_content_msg,$send_file,$from_userid='',$from_username='',$new=1,$status=0,$issys=1,$xs=0);
				}	
			}
	
		}
	}
	//超过时间后自动更新为仓储－结束
}
?>
