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
//显示表单-----------------------------------------------------------------------------------------------------
$headtitle='扫描物品';$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

if(!$ON_gd_japan){exit ("<script>alert('日本清关资料系统已关闭！');goBack();</script>");}
?>

<div class="page_ny" style="min-height:800px;"> 
  <!-- BEGIN PAGE HEADER-->
	<div><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"><?php if($alonepage!=1){?>
<a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=all" class="gray" target="_parent"><?=$LG['backList']?></a> > <?php }?>

        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
        </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <!---->
  <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/scanGoodsdata_save.php');?>
  <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/scanGoodsdata_form.php');?>
  <?php if($yd_number){require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/scanGoodsdata_list.php');}?>
  <!---->

</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
