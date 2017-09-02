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
$alonepage=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
/*
	typ=0 直接修改
	typ=1 显示文本框：修改备注
	
	调用：
	<a href="../Tool/edit.php?typ=1&table=daigou&fieldEdit=content&fieldId=dgid&id=<?=$rs['dgid']?>" target="XingAobox" class="btn btn-xs btn-default showdiv">修改备注</a>
*/

//获取,处理-----------------------------------------------------------------------------------------------
$typ=spr($_REQUEST['typ']);
$table=par($_REQUEST['table']);
$fieldEdit=par($_REQUEST['fieldEdit']);
$fieldId=par($_REQUEST['fieldId']);
$value=add($_REQUEST['value']);
$id=spr($_REQUEST['id']);
$smt=spr($_POST['smt']);
?>



<!--需要有这些HTML，否则无法看到操作提示-->
<style>html {  overflow-x:hidden;}</style>
<form action="?" method="post" class="form-horizontal form-bordered" name="xingao" style="width:620px;">

<?php 
//验证-----------------------------------------------------------------------------------------------
if(!$table||!$fieldEdit||!$id){exit($LG['pptError']);}

//允许修改的表和字段：必须验证，否则会员能随便修改所有数据
$chk=0;$ppt=$LG['Tool.ppt_2'];
if(!$chk&&$table=='baoguo' && ($fieldEdit=='content') ){$chk=1;}
if(!$chk&&$table=='yundan' && ($fieldEdit=='content') )
{
	$status=FeData($table,'status',"{$fieldId}='{$id}' {$Mmy}");
	if(!have($status,'-1,0,1',1)){exit($LG['pptOpPer']);}
	$chk=1;
}
if(!$chk){exit($LG['pptOpError']);}



//提交保存-----------------------------------------------------------------------------------------------
if($smt||!$typ)
{
 	$save="{$fieldEdit}='{$value}'";
	$xingao->query("update {$table} set {$save} where {$fieldId}='{$id}' {$Mmy}");SQLError('更新');
	if(mysqli_affected_rows($xingao)>0){exit($LG['pptEditSucceed']);}
	else{exit("<script>alert('{$LG['pptEditEmpty']}');goBack();</script>");}
}
?>













<!----------------------------------------显示表单-开始------------------------------------------------>
<input name="smt" type="hidden" value="1">
<input name="id" type="hidden" value="<?=$id?>">
<input name="typ" type="hidden" value="<?=$typ?>">
<input name="table" type="hidden" value="<?=$table?>">
<input name="fieldEdit" type="hidden" value="<?=$fieldEdit?>">
<input name="fieldId" type="hidden" value="<?=$fieldId?>">

<?php
switch($typ)
{
	case '1'://----------------------------------修改备注---------------------------------------------
		$content=FeData($table,$fieldEdit,"{$fieldId}='{$id}' {$Mmy}");
		?>
		<h4 class="modal-title"><strong><?=$LG['edit']?></strong></h4>
		<div class="form-group">
		<div class="col-md-10">
        
		<textarea name="value" rows="5" class="form-control"><?=cadd($content)?></textarea>
        <span class="help-block"><?=$ppt?></span>
        
		</div>
		</div>
		<?php
	break;
}
?>
<br>
<button type="submit"  class="btn btn-primary"> <i class="icon-ok"></i> <?=$LG['submit']?> </button>
</form>

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');?>
