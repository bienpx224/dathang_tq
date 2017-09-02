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
$pervar='yundan_sc';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="扫描称重";
$alonepage=2;//1单页形式;2框架形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?>
<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"><?php if($alonepage!=1){?>
<a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=all" class="gray" target="_parent"><?=$LG['backList']?></a> >
<?php }?>
 <a href="?" class="gray">
        重新扫描
        </a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 

  <!-- BEGIN PAGE CONTENT-->
<?php 
//--------------------------------------------------------------------------------------------------------------------
//提交操作--------------------------------------------------------------------------------
$lx=par($_REQUEST['lx']);
$ydh=par($_REQUEST['ydh']);
$calc=par($_REQUEST['calc']);

if ($lx=='sm')
{
	//获取,验证
	if (!$ydh){echo ("<script>alert('请输入或扫描单号！');goBack();</script>");exit;}	
	if (!$calc){echo ("<script>alert('请选择操作类型！');goBack();</script>");exit;}	
	$op_ydh=par($_POST['op_ydh']);
	$op_dsfydh=par($_POST['op_dsfydh']);
	$op_gwkdydh=par($_POST['op_gwkdydh']);
	$op_gnkdydh=par($_POST['op_gnkdydh']);
	$op_hscode=par($_POST['op_hscode']);

	//查询条件
	$where='';
	if($op_ydh){if($where){$where.=" or ydh='{$ydh}'";}else{$where=" ydh='{$ydh}'";}}
	if($op_dsfydh){if($where){$where.=" or dsfydh='{$ydh}'";}else{$where=" dsfydh='{$ydh}'";}}
	if($op_gwkdydh){if($where){$where.=" or gwkdydh='{$ydh}'";}else{$where=" gwkdydh='{$ydh}'";}}
	if($op_gnkdydh){if($where){$where.=" or gnkdydh='{$ydh}'";}else{$where=" gnkdydh='{$ydh}'";}}
	if($op_hscode){if($where){$where.=" or hscode='{$ydh}'";}else{$where=" hscode='{$ydh}'";}}
	
	if (!$where){echo ("<script>alert('请勾选扫描选项,至少要选一个！');goBack();</script>");exit;}
	
	$search="&lx={$lx}&ydh={$ydh}&op_ydh={$op_ydh}&op_dsfydh={$op_dsfydh}&op_gwkdydh={$op_gwkdydh}&op_gnkdydh={$op_gnkdydh}&op_hscode={$op_hscode}";

	$query="select ydid from yundan where {$where}  ".whereCS()." order by ydid desc limit 1";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$ok=1;
		
		if($calc=='fee'){$url= '/xingao/yundan/calc_fee.php?ydid='.$rs['ydid'];}
		elseif($calc=='tax'){$url= '/xingao/yundan/calc_tax.php?ydid='.$rs['ydid'];}

		echo '<script language=javascript>';
		//echo 'location.href="'.$url.'";';
		echo 'window.open("'.$url.'")';
		echo '</script>';
		echo '<div align="center"><a href="'.$url.'" target="_blank"><strong>如果没弹出请点击</strong></a></div>';
		
	}
	
	if(!mysqli_num_rows($sql)){$ok=0;$ts='<font class="red">找不到运单!</font>';}

	//播放提示声音
	if($ok){music('yes');}elseif(!$ok){music('no');}

}//if ($lx=="sm")
?>

<?php 
//--------------------------------------------------------------------------------------------------------------------
//输出各种处理的提示--------------------------------------------------------------------------------------------------
if($ts)
{
  echo '<div style="font-size: 18px; line-height:23px; padding-left:20px;">'.$ts.'</div>';
}
?>

<?php 
//--------------------------------------------------------------------------------------------------------------------
//显示扫描框--------------------------------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/scanWeighing_form.php');
?>

</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
