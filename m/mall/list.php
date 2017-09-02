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
if(!$off_mall){	exit ("<script>alert('{$LG['front.136']}');goBack('uc');</script>");}

require_once($_SERVER['DOCUMENT_ROOT'].'/mall/call/list_sql.php');//位置要固定此处
$line=15;$page_line=3;//分页处理,不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');//放查询的后面
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');
?>
<!--商城样式开始-->
<link href="/css/mall.css" rel="stylesheet" type="text/css" />

<!--内容开始-->
<div class="article_ny">
	<span class="title"> <?=cadd($cr['name'.$LT])?> </span>
	
    <div id="fillter">
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/mall/call/list_options.php');?>
    </div>
			
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/mall/call/list_order.php');?>
	 <br>

	<div class="MallImgList">
	<ul>
	<!--商品列表-开始-->
	<?php 
	while($rs=$sql->fetch_array())
	{
		$rs['unit']=classify($rs['unit'],2);
		?> 
		<li>
		<a href="/m/mall/show.php?mlid=<?=$rs['mlid']?>" target="_blank" title="<?=cadd($rs['title'.$LT])?>">
		<img src="<?=ImgAdd($rs['titleimg'.$LT])?>"/>
		<p><?=spr($rs['price']).$XAmc?><?=$rs['unit']?'/'.$rs['unit']:''?></p>
		<b><?=cadd($rs['title'.$LT])?></b>
		</a>
		
		<span>
		<?php if($XAMcurrency!=$XAScurrency){?>
         <font class="red2"><?=spr($rs['price']*exchange($XAMcurrency,$XAScurrency)).$XAsc?></font>
        <?php }else{?>
         <?=$LG['front.115']?><?=$rs['number']?><?=$rs['unit']?>  
        <?php }?>

		<?=$rs['number_sold']?$LG['front.116'].$rs['number_sold'].$rs['unit'].' &nbsp; ':$LG['comments'].$rs['plclick']?>
        </span>
		
		</li>
	<?php 
	$noid=$rs['mlid'];
	}
	if (!$noid){ echo $LG['pptNot'];} 
	?>
	<!--商品列表-结束-->	
	</ul>
  </div>
  <div class="row"><?=$listpage?></div>
</div>

<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/footer.php');?>
