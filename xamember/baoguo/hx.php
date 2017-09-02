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
$headtitle=$LG['baoguo.hx_1'];//合箱
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
 
//获取,处理-----------------------------------------------------------------------------------------------
$bgid=par($_GET['bgid']);//只能有一个ID

?>
<style>html{overflow-x:hidden;}</style>
<h4 class="modal-title">
<?php 
if(!$off_baoguo||!$member_per[$Mgroupid]['ON_Mbaoguo']){exit("{$LG['baoguo.add_form_2']}");}
if (!$off_hx){exit("{$LG['baoguo.hx_2']}");}
?>
<strong><?=$LG['baoguo.hx_3'];//请选择要合入的包裹：?></strong></h4>
<!----------------------------------------显示表单-开始------------------------------------------------>
<form action="hx_save.php" method="post" class="form-horizontal form-bordered" name="xingao" style="width:620px;">
<input name="lx" type="hidden" value="pay">

<table class="table table-striped table-bordered table-hover" >
	<thead>
		<tr>
			<td align="left">
	<?php
    //验证
	if(!$bgid){exit ("bgid{$LG['pptError']}");}

	$where=baoguo_fahuo(2)." and status in (2,3) and ware=0 ";//可以发货并且状态已入库的
	$min=mysqli_fetch_array($xingao->query("select bgydh,bgid,warehouse,weight,hx_requ from baoguo where  hx<>1 and hx_suo=0 and bgid='{$bgid}' {$where} {$Mmy}"));
	if (!$min['bgid']){exit("{$LG['baoguo.hx_4']}");} 
	?> 
	
	<input name="min_bgid" type="hidden" value="<?=$min['bgid']?>"/>
	<input name="min_bgydh" type="hidden" value="<?=$min['bgydh']?>" />

	<a href="show.php?bgid=<?=$min['bgid']?>" target="_blank" title="<?=$LG['baoguo.hx_12']?><?=warehouse($min['warehouse'])?>&#13;<?=$LG['baoguo.hx_13']?><?=spr($min['weight']).$XAwt?>"><strong><?=cadd($min['bgydh'])?></strong></a>
	</td>
			<td align="right"><?=$LG['baoguo.hx_14']?> </td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="2">
	<?php
	$query="select bgydh,bgid,warehouse,weight from baoguo where bgid<>'{$min[bgid]}' and warehouse='{$min[warehouse]}' {$where} {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
	?>
		<div style="width:150px; height:25px; float:left; display:block;">
		<input type="checkbox" name="bgid[]" value="<?=$rs['bgid']?>" />
		
		<a href="show.php?bgid=<?=$rs['bgid']?>" target="_blank" title="<?=$LG['baoguo.hx_13']?><?=spr($min['weight']).$XAwt?>  <?=cadd($rs['bgydh'])?>"><?=leng($rs['bgydh'],12,'..')?></a> 
		</div>
	<?php
	}
	$rc=mysqli_affected_rows($xingao);
	if (!$rc){exit("{$LG['baoguo.hx_6']}");} 
	?>
	
	</td>
		</tr>
	</tbody>
</table>

		<label class="control-label col-md-2"><?=$LG['baoguo.hx_requ'];//合箱要求:?></label>
		<div class="col-md-10">
		  <textarea name="hx_requ" class="form-control" rows="2"><?=cadd($min['hx_requ'])?></textarea>
		</div>
		
		
<!----------------------------------------显示表单-结束------------------------------------------------>
<?php 
$ts_money=LGtag($LG['baoguo.hx_7'],
	'<tag1>=='.$member_per[$Mgroupid]['Price_hxsl'].'||'.
	'<tag2>=='.$member_per[$Mgroupid]['Price_hx1'].'||'.
	'<tag3>=='.$XAmc.'||'.
	'<tag4>=='.$member_per[$Mgroupid]['Price_hx2'].'||'.
	'<tag5>=='.$XAmc
 );

$ts_hou='&raquo; '.$LG['baoguo.hx_8'].'<br>';
$ts_hou.='&raquo; '.$LG['baoguo.hx_9'].'<br>';
$ts_hou.='&raquo; '.$LG['baoguo.hx_10'].'<br>';
$ts_hou.='&raquo; '.$LG['baoguo.hx_11'].'<br>';

require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/baoguo/call/pay_show.php');?>
</form>

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');?>
