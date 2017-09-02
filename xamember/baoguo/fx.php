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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['baoguo.fx_1'];//分箱
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
 
//获取,处理-----------------------------------------------------------------------------------------------
$lx=par($_GET['lx']);
$bgid=par($_GET['bgid']);//只能有一个ID


?>
<style>html{overflow-x:hidden;}</style>
<h4 class="modal-title" style="margin-bottom:10px;width:620px;">
<?php 
if(!$off_baoguo||!$member_per[$Mgroupid]['ON_Mbaoguo']){exit("{$LG['baoguo.add_form_2']}");}
if (!$off_fx){exit("{$LG['baoguo.fx_28']}");}
?>

<strong><?=$LG['baoguo.fx_2'];//请选择要分成另一个包裹的物品?></strong>
<button type="button" class="btn btn-warning" style="float:right" onClick="location.href='fx_save.php?lx=del_all&bgid=<?=$bgid?>';"><i class="icon-signin"></i> <?=$LG['baoguo.fx_3'];//整合主箱物品并删除所有分箱?> </button><br>
<br>

 </h4>
<!----------------------------------------显示表单-开始------------------------------------------------>
<form action="fx_save.php" method="post" class="form-horizontal form-bordered" name="XingAoForm" style="width:620px;">
	<input name="lx" type="hidden" value="pay">
	<input name="bgid" type="hidden" value="<?=$bgid?>">
	
	<!-------------------------------------------主包裹------------------------------------------------>
	<table class="table table-striped table-bordered table-hover" >
		<thead>
			<tr>
				<td align="center"><?=$LG['baoguo.fx_4'];//类别?></td>
				<td align="center"><?=$LG['baoguo.fx_5'];//品名?></td>
				<td align="center"><?=$LG['baoguo.fx_6'];//品牌/厂商?></td>
				<td align="center" class="red"><?=$LG['baoguo.fx_7'];//重量?></td>
				<td align="center" class="red"><?=$LG['baoguo.fx_8'];//数量?></td>
				<td align="center"><?=$LG['baoguo.fx_9'];//规格?></td>
				<td align="center"><?=$LG['baoguo.fx_30'];//单价?><?="(".$XAsc.")"?></td>
				<td align="center"><?=$LG['baoguo.fx_31'];//总价?><?="(".$XAsc.")"?></td>
			</tr>
		</thead>
		<tbody>
<?php
if(!$bgid){exit ("bgid{$LG['pptError']}");}

$where=baoguo_fahuo(2)." and status in (2,3) and ware=0 ";//可以发货并且状态已入库的
$min=mysqli_fetch_array($xingao->query("select bgydh,bgid,fx_wupin,fx_requ from baoguo where fx<>1 and fx_suo=0  and bgid='{$bgid}' {$where} {$Mmy}"));

$bgid=$min['bgid'];
if (!$bgid){exit("{$LG['baoguo.fx_29']}");} 

$query_wp="select * from wupin where fromtable='baoguo' and fromid='{$bgid}' order by wupin_name desc";
$sql_wp=$xingao->query($query_wp);
while($wp=$sql_wp->fetch_array())
{
?>
	<tr class="gray2">
			<td align="center"><?=is_numeric($wp['wupin_type'])?classify($wp['wupin_type'],2):cadd($wp['wupin_type'])?>
			<input name="wupin_type[]"  type="hidden"  style="width:50px;"  class="ctt_input_txt" value="<?=cadd($wp['wupin_type'])?>" /></td>
		<td align="center">
			<?=cadd($wp['wupin_name'])?>
			<input name="wupin_name[]"  type="hidden"  style="width:50px;"  class="ctt_input_txt" value="<?=cadd($wp['wupin_name'])?>" />
			</td>
		<td align="center"><?=cadd($wp['wupin_brand'])?>
			<input name="wupin_brand[]"  type="hidden"  style="width:80px;"  class="ctt_input_txt" value="<?=cadd($wp['wupin_brand'])?>" /></td>
		<td align="center">
			<input name="wupin_weight[]"  type="text"  style="width:50px;"  class="ctt_input_txt tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['baoguo.fx_10'];//填写重量则按重量分否则按数量分?>" value=""/> <?=spr($wp['wupin_weight']).$XAwt?>
		</td>
			
		<td align="center">
			<input name="wupin_number[]"  type="text"  style="width:50px;"  class="ctt_input_txt" value="" id="productNum" onBlur="CalcTotalPrice(this);CalcDeclareValue()" />
			<?=is_numeric($wp['wupin_unit'])?classify($wp['wupin_unit'],2):cadd($wp['wupin_unit'])?>
			<input name="wupin_unit[]" type="hidden" value="<?=cadd($wp['wupin_unit'])?>" >
		</td>
		<td align="center"><?=cadd($wp['wupin_spec'])?>
			<input name="wupin_spec[]"  type="hidden"  style="width:50px;"  class="ctt_input_txt" value="<?=cadd($wp['wupin_spec'])?>" /></td>
		
		
		<td align="center">
			<?=$wp['wupin_price']?>
			<input name="wupin_price[]" id="productPrice"    type="hidden" class="ctt_input_txt" value="<?=$wp['wupin_price']?>"  />
			</td>
		<td align="center">
		<input name="wupin_total[]" id="productTotalPrice"  type="hidden" style="width:50px;"  class="ctt_input_txt"  value="<?=$wp['wupin_total']?>" />
		
		<input id="productTotalPrice"  type="text" style="width:50px;"  class="ctt_input_txt"  value="<?=$wp['wupin_total']?>"  disabled="disabled"/>
		</td>
	</tr>
	<?php
}
if(!mysqli_num_rows($sql_wp)){echo $LG['baoguo.fx_32']."<br>";}
?>


		</tbody>
	</table>
	
<div class="fx_1" title="<?=$LG['baoguo.fx_11'];//拆为新包裹?>"><?=$LG['baoguo.fx_12'];//新?></div>

<div align="center">
<button type="submit"  class="btn btn-info"
onClick="
document.XingAoForm.lx.value='fx';
"><i class="icon-sitemap"></i> <?=$LG['baoguo.fx_13'];//分 箱?> </button>

	
</div>
	<!-------------------------------------------拆分的包裹------------------------------------------------> 
<?php 
$arr=cadd($min['fx_wupin']);
if($arr)
{
	$i=0;
	if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
	foreach($arr as $arrkey=>$value)
	{
		$i+=1;
?> <br />
	<div class="fx_1" title="<?=$LG['baoguo.fx_14'];//新包裹?>"><?=$i?></div>
	<table class="table table-striped table-bordered table-hover" >
		<thead>
			<tr>
				<td align="center"><?=$LG['baoguo.fx_4'];//类别?></td>
				<td align="center"><?=$LG['baoguo.fx_5'];//品名?></td>
				<td align="center"><?=$LG['baoguo.fx_6'];//品牌/厂商?></td>
				<td align="center"><?=$LG['baoguo.fx_8'];//数量?></td>
				<td align="center"><?=$LG['baoguo.fx_7'];//重量?><?="(".$XAwt.")"?></td>
				<td align="center"><?=$LG['baoguo.fx_9'];//规格?></td>				
				<td align="center"><?=$LG['baoguo.fx_15'];//单位?></td>
				<td align="center"><?=$LG['baoguo.fx_30'];//单价?><?="(".$XAsc.")"?></td>
				<td align="center"><?=$LG['baoguo.fx_31'];//总价?><?="(".$XAsc.")"?></td>
				<td align="center">
				<a href="javascript:if(confirm('<?=$LG['baoguo.fx_16']?>'))window.location='fx_save.php?lx=del&bgid=<?=$bgid?>&fx_wupin=<?=cadd($value)?>'" class="btn btn-xs btn-danger"><?=$LG['del'];//删除?></a></td>
			</tr>
		</thead>
		<tbody>
			<?php
			$query_wp="select * from wupin where fromtable='{$value}' order by wupin_name desc";
			$sql_wp=$xingao->query($query_wp);
			while($wp=$sql_wp->fetch_array())
			{
       		 ?>
			<tr class="gray2">
				<td align="center"><?=is_numeric($wp['wupin_type'])?classify($wp['wupin_type'],2):cadd($wp['wupin_type'])?></td>
				<td align="center"><?=cadd($wp['wupin_name'])?></td>
				<td align="center"><?=cadd($wp['wupin_brand'])?></td>
				<td align="center"><?=spr($wp['wupin_number'])?></td>
				<td align="center"><?=spr($wp['wupin_weight'])?></td>
				<td align="center"><?=cadd($wp['wupin_spec'])?></td>
				<td align="center"><?=is_numeric($wp['wupin_unit'])?classify($wp['wupin_unit'],2):cadd($wp['wupin_unit'])?></td>
				<td align="center"><?=spr($wp['wupin_price'])?></td>
				<td align="center"><?=cadd($wp['wupin_total'])?></td>
				<td align="center"></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
<?php
	}//foreach($arr as $arrkey=>$value)
}//if($arr)
?> 
<!--已拆分--> 
	
	
	<!---------------------------------------------------确定提交------------------------------------------------>
<div class="fx_1" title="<?=$LG['baoguo.fx_17'];//保存分箱?>"><?=$LG['baoguo.fx_18'];//存?></div>
	<label class="control-label col-md-2"><?=$LG['baoguo.fx_requ'];//分箱要求:?></label>
	<div class="col-md-10">
		<textarea name="fx_requ" class="form-control" rows="2"><?=cadd($min['fx_requ'])?></textarea>
	</div>
	
	<!----------------------------------------显示表单-结束------------------------------------------------> 
<?php 
$num=arrcount(cadd($min['fx_wupin']));
$total_money=baoguo_fx_fee($num,$Muserid);

$ts_money=LGtag($LG['baoguo.fx_19'],
	'<tag1>=='.$num.'||'.
	'<tag2>=='.($num+1).'||'.
	'<tag3>=='.$total_money.$XAmc.'||'.
	'<tag4>=='.$member_per[$Mgroupid]['Price_fxsl'].'||'.
	'<tag5>=='.$member_per[$Mgroupid]['Price_fx1'].'||'.
	'<tag6>=='.$XAmc.'||'.
	'<tag7>=='.$member_per[$Mgroupid]['Price_fx2'].'||'.
	'<tag8>=='.$XAmc
 );


$ts_hou.='&raquo; '.$LG['baoguo.fx_21'].'<br>';
$ts_hou.='&raquo; '.$LG['baoguo.fx_23'].'<br>';
$ts_hou.='&raquo; '.$LG['baoguo.fx_25'].'><br>';
$ts_hou.='&raquo; '.$LG['baoguo.fx_27'].'<br>';

require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/baoguo/call/pay_show.php');?>
</form>
<script src="/js/AntongJQ.js" type="text/javascript"></script> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/js/baoguoJS.php');?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');?>
