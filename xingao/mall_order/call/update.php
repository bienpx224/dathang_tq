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

if (update_time('mall_order','-1 hours'))//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
{
	//超时订单设为失效，更新清关资料存---------------------------------------------------------------------------
	$mall_order_time=spr($mall_order_time);
	if($mall_order_time>0)
	{

		$query_up="select number,mlid,odid from mall_order where pay='0' and status<>'3' and addtime<=".strtotime('-'.$mall_order_time.' hours')."";
		$sql_up=$xingao->query($query_up);
		while($rs_up=$sql_up->fetch_array())
		{
			//更新商城商品数量
			$xingao->query("update mall set number=number+$rs_up[number] where mlid='{$rs_up[mlid]}'");
			SQLError('更新商城商品数量');
			
			//更新订单表
			$xingao->query("update mall_order set status='3' where odid='{$rs_up[odid]}' "); 
			SQLError('更新订单表');
		}
	}
	
	//无库存,自动下架-----------------------------------------------------------------------------------
	if($off_mall_checked)
	{
		$xingao->query("update mall set checked='0' where number<='0' ");
		SQLError('无库存,自动下架');
	}

}
?>