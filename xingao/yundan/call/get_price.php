<?php
/* 调用
	if (!$groupid){$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");}
	$warehouse=$rs['warehouse'];
	$country=$rs['country'];
	$channel=$rs['channel'];
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/get_price.php');//获取价格
*/

//获取会员价格
if($groupid)
{
		
	$area=GetArea($groupid,$warehouse,$country);//获取区域
	$groupname=$member_per[$groupid]['groupname'];//会员组名

	//包裹服务收费
	$Price_02		=	$member_per[$groupid]['Price_02'];
	$Price_06		=	$member_per[$groupid]['Price_06'];
	$Price_09		=	$member_per[$groupid]['Price_09'];
	$Price_10		=	$member_per[$groupid]['Price_10'];
	$Price_11		=	$member_per[$groupid]['Price_11'];
	$Price_hxsl		=	$member_per[$groupid]['Price_hxsl'];
	$Price_hx1		=	$member_per[$groupid]['Price_hx1'];
	$Price_hx2		=	$member_per[$groupid]['Price_hx2'];
	$Price_hx3		=	$member_per[$groupid]['Price_hx3'];
	$Price_fxsl		=	$member_per[$groupid]['Price_fxsl'];
	$Price_fx1		=	$member_per[$groupid]['Price_fx1'];
	$Price_fx2		=	$member_per[$groupid]['Price_fx2'];
	
	$Price_fh_hxsl			=	$member_per[$groupid]['Price_fh_hxsl'];
	$Price_fh_feesl			=	$member_per[$groupid]['Price_fh_feesl'];
	$Price_fh_hx_fee1		=	$member_per[$groupid]['Price_fh_hx_fee1'];
	$Price_fh_hx_fee2		=	$member_per[$groupid]['Price_fh_hx_fee2'];
	$Price_fh_hx_fee1_way	=	$member_per[$groupid]['Price_fh_hx_fee1_way'];
	$Price_fh_hx_fee2_way	=	$member_per[$groupid]['Price_fh_hx_fee2_way'];

	$Price_fh_wg_type		=	$member_per[$groupid]['Price_fh_wg_type'];
	$Price_fh_wg_formula	=	$member_per[$groupid]['Price_fh_wg_formula'];
	$Price_fh_wg			=	$member_per[$groupid]['Price_fh_wg'];
	$Price_fh_wg_fee		=	$member_per[$groupid]['Price_fh_wg_fee'];
	$Price_fh_wg_fee2		=	$member_per[$groupid]['Price_fh_wg_fee2'];
	
	$op_bgfee1_val_fee1		=	$member_per[$groupid]['op_bgfee1_val_fee1'];
	$op_bgfee1_val_fee2		=	$member_per[$groupid]['op_bgfee1_val_fee2'];
	$op_bgfee1_val_fee3		=	$member_per[$groupid]['op_bgfee1_val_fee3'];
	$op_bgfee1_val_fee4		=	$member_per[$groupid]['op_bgfee1_val_fee4'];
	$op_bgfee1_val_fee5		=	$member_per[$groupid]['op_bgfee1_val_fee5'];
	$op_bgfee1_val_fee6		=	$member_per[$groupid]['op_bgfee1_val_fee6'];
	$op_bgfee1_val_fee7		=	$member_per[$groupid]['op_bgfee1_val_fee7'];
	$op_bgfee1_val_fee8		=	$member_per[$groupid]['op_bgfee1_val_fee8'];
	$op_bgfee1_val_fee9		=	$member_per[$groupid]['op_bgfee1_val_fee9'];
	$op_bgfee1_val_fee10	=	$member_per[$groupid]['op_bgfee1_val_fee10'];
	$op_bgfee2_val_fee1		=	$member_per[$groupid]['op_bgfee2_val_fee1'];
	$op_bgfee2_val_fee2		=	$member_per[$groupid]['op_bgfee2_val_fee2'];
	$op_bgfee2_val_fee3		=	$member_per[$groupid]['op_bgfee2_val_fee3'];
	$op_bgfee2_val_fee4		=	$member_per[$groupid]['op_bgfee2_val_fee4'];
	$op_bgfee2_val_fee5		=	$member_per[$groupid]['op_bgfee2_val_fee5'];
	$op_bgfee2_val_fee6		=	$member_per[$groupid]['op_bgfee2_val_fee6'];
	$op_bgfee2_val_fee7		=	$member_per[$groupid]['op_bgfee2_val_fee7'];
	$op_bgfee2_val_fee8		=	$member_per[$groupid]['op_bgfee2_val_fee8'];
	$op_bgfee2_val_fee9		=	$member_per[$groupid]['op_bgfee2_val_fee9'];
	$op_bgfee2_val_fee10	=	$member_per[$groupid]['op_bgfee2_val_fee10'];
	$op_wpfee1_val_fee1		=	$member_per[$groupid]['op_wpfee1_val_fee1'];
	$op_wpfee1_val_fee2		=	$member_per[$groupid]['op_wpfee1_val_fee2'];
	$op_wpfee1_val_fee3		=	$member_per[$groupid]['op_wpfee1_val_fee3'];
	$op_wpfee1_val_fee4		=	$member_per[$groupid]['op_wpfee1_val_fee4'];
	$op_wpfee1_val_fee5		=	$member_per[$groupid]['op_wpfee1_val_fee5'];
	$op_wpfee1_val_fee6		=	$member_per[$groupid]['op_wpfee1_val_fee6'];
	$op_wpfee1_val_fee7		=	$member_per[$groupid]['op_wpfee1_val_fee7'];
	$op_wpfee1_val_fee8		=	$member_per[$groupid]['op_wpfee1_val_fee8'];
	$op_wpfee1_val_fee9		=	$member_per[$groupid]['op_wpfee1_val_fee9'];
	$op_wpfee1_val_fee10	=	$member_per[$groupid]['op_wpfee1_val_fee10'];
	$op_wpfee2_val_fee1		=	$member_per[$groupid]['op_wpfee2_val_fee1'];
	$op_wpfee2_val_fee2		=	$member_per[$groupid]['op_wpfee2_val_fee2'];
	$op_wpfee2_val_fee3		=	$member_per[$groupid]['op_wpfee2_val_fee3'];
	$op_wpfee2_val_fee4		=	$member_per[$groupid]['op_wpfee2_val_fee4'];
	$op_wpfee2_val_fee5		=	$member_per[$groupid]['op_wpfee2_val_fee5'];
	$op_wpfee2_val_fee6		=	$member_per[$groupid]['op_wpfee2_val_fee6'];
	$op_wpfee2_val_fee7		=	$member_per[$groupid]['op_wpfee2_val_fee7'];
	$op_wpfee2_val_fee8		=	$member_per[$groupid]['op_wpfee2_val_fee8'];
	$op_wpfee2_val_fee9		=	$member_per[$groupid]['op_wpfee2_val_fee9'];
	$op_wpfee2_val_fee10	=	$member_per[$groupid]['op_wpfee2_val_fee10'];
	$op_ydfee1_val_fee1		=	$member_per[$groupid]['op_ydfee1_val_fee1'];
	$op_ydfee1_val_fee2		=	$member_per[$groupid]['op_ydfee1_val_fee2'];
	$op_ydfee1_val_fee3		=	$member_per[$groupid]['op_ydfee1_val_fee3'];
	$op_ydfee1_val_fee4		=	$member_per[$groupid]['op_ydfee1_val_fee4'];
	$op_ydfee1_val_fee5		=	$member_per[$groupid]['op_ydfee1_val_fee5'];
	$op_ydfee1_val_fee6		=	$member_per[$groupid]['op_ydfee1_val_fee6'];
	$op_ydfee1_val_fee7		=	$member_per[$groupid]['op_ydfee1_val_fee7'];
	$op_ydfee1_val_fee8		=	$member_per[$groupid]['op_ydfee1_val_fee8'];
	$op_ydfee1_val_fee9		=	$member_per[$groupid]['op_ydfee1_val_fee9'];
	$op_ydfee1_val_fee10	=	$member_per[$groupid]['op_ydfee1_val_fee10'];
	$op_ydfee2_val_fee1		=	$member_per[$groupid]['op_ydfee2_val_fee1'];
	$op_ydfee2_val_fee2		=	$member_per[$groupid]['op_ydfee2_val_fee2'];
	$op_ydfee2_val_fee3		=	$member_per[$groupid]['op_ydfee2_val_fee3'];
	$op_ydfee2_val_fee4		=	$member_per[$groupid]['op_ydfee2_val_fee4'];
	$op_ydfee2_val_fee5		=	$member_per[$groupid]['op_ydfee2_val_fee5'];
	$op_ydfee2_val_fee6		=	$member_per[$groupid]['op_ydfee2_val_fee6'];
	$op_ydfee2_val_fee7		=	$member_per[$groupid]['op_ydfee2_val_fee7'];
	$op_ydfee2_val_fee8		=	$member_per[$groupid]['op_ydfee2_val_fee8'];
	$op_ydfee2_val_fee9		=	$member_per[$groupid]['op_ydfee2_val_fee9'];
	$op_ydfee2_val_fee10	=	$member_per[$groupid]['op_ydfee2_val_fee10'];
	
	//包裹仓储
	$bg_ware_freeDays		=	$member_per[$groupid]['bg_ware_freeDays'];
	$bg_ware_price			=	$member_per[$groupid]['bg_ware_price'];
	
	//代码仓储
	$bg_ware_freeDays		=	$member_per[$groupid]['bg_ware_freeDays'];
	$dg_ware_volumePrice	=	$member_per[$groupid]['dg_ware_volumePrice'];
	$dg_ware_volumeLimit	=	$member_per[$groupid]['dg_ware_volumeLimit'];
	$dg_ware_weightPrice	=	$member_per[$groupid]['dg_ware_weightPrice'];
	$dg_ware_weightLimit	=	$member_per[$groupid]['dg_ware_weightLimit'];
	$dg_ware_numberPrice	=	$member_per[$groupid]['dg_ware_numberPrice'];
	
	//渠道数据
	$channel_name		=	channel_name($groupid,$warehouse,$country,$channel);
	$channel_checked	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_checked'];
	$channel_formula	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_formula'];
	$channel_sz_weight	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_sz_weight'];
	$channel_sz_price	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_sz_price'];
	$channel_xz_weight	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_xz_weight'];
	$channel_xz_price	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_xz_price'];
	$channel_fee_other	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_fee_other'];
	$channel_fee_other_currency	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_fee_other_currency'];
	$channel_fee_other_weight	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_fee_other_weight'];
	$channel_discount	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_discount'];
	$channel_tax		=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_tax'];
	$channel_cc_exceed	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_cc_exceed'];
	$channel_cc_formula	=	$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_cc_formula'];
}

?>