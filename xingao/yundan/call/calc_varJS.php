<?php 
//获取物品资料
if(!$wp_number){
	$fromtable='yundan';$fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' and wupin_number>0";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		//获取备案相关资料
		if($wp['gdid']){
			$tax_gdid.=$wp['gdid'].',';
			$go_number.=$wp['wupin_number'].',';
		}
		
		if($wp['goid']){
			$goid.=$wp['goid'].',';
			$go_number.=$wp['wupin_number'].',';
		}
		
		$wp_number_total+=$wp['wupin_number'];
	}
}
?>
	var gdid='<?=DelStr($tax_gdid)?>';
	var go_number='<?=DelStr($go_number)?>';
    
	var goid='<?=DelStr($goid)?>';
	var go_number='<?=DelStr($go_number)?>';
    
	var wp_number='<?=$wp_number_total?>';


	var warehouse='<?=$rs['warehouse']?>';
	var channel='<?=$rs['channel']?>';
	var userid='<?=$rs['userid']?>';
	var op_bgfee1='<?=$rs['op_bgfee1']?>';
	var op_bgfee2='<?=$rs['op_bgfee2']?>';
	var op_wpfee1='<?=$rs['op_wpfee1']?>';
	var op_wpfee2='<?=$rs['op_wpfee2']?>';
	var op_ydfee1='<?=$rs['op_ydfee1']?>';
	var op_ydfee2='<?=$rs['op_ydfee2']?>';
	var country='<?=$rs['country']?>';
	var bg_number='<?=$bg_number?>';
	var insurevalue='<?=$rs['insurevalue']?>';
	var baoguo_hx_fee='<?=$baoguo_hx_fee?>';
    
	var tax_number=0;    if($('[name="tax_number"]').length>0){tax_number=document.getElementsByName('tax_number')[0].value;}
	var fee_tax=0;    if($('[name="fee_tax"]').length>0){fee_tax=document.getElementsByName('fee_tax')[0].value;}

	var weight=document.getElementsByName('weight')[0].value;
	var fee_transport=document.getElementsByName('fee_transport')[0].value;
	var fee_service=document.getElementsByName('fee_service')[0].value;
	var fee_other=document.getElementsByName('fee_other')[0].value;
	var discount=document.getElementsByName('discount')[0].value;
    
	var fee_ware=document.getElementsByName('fee_ware')[0].value;
	var fee_cc=document.getElementsByName('fee_cc')[0].value;
	var cc_chang=document.getElementsByName('cc_chang')[0].value;
	var cc_kuan=document.getElementsByName('cc_kuan')[0].value;
	var cc_gao=document.getElementsByName('cc_gao')[0].value;
