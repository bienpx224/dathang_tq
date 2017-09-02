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
//http://zy/m/template/
$index=1;
$headtitle=$LG['name.nav_0'];
$rs['seokey']=$sitekey;
$rs['intro'.$LT]=$sitetext;
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');//放查询的后面
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');//放查询的后面
//验证,查询

$lx=par($_GET['lx']);
if(!$_SESSION['manage']['userid']&&$lx!='html'){XAts('temp');}
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/nav.php');
?>
<!--各种验证代码放此处-->
<meta property="qc:admins" content="157011404545576016717746375" />
<link rel="stylesheet" type="text/css" href="/m/css/index.css">

<!-- 幻灯开始 (可以触摸) -->
<div id="banner" class="swiper-container" style="top:-10px;">
  <div class="swiper-wrapper">
<?php 
$classid='38';//幻灯片
$field=",hdimg{$LT}";//特别字段(以,开头)
$where="and isgood=0 and hdimg{$LT}<>''";//特别条件(以and开头) 不设为推荐表示是电脑版
$limit=6;
$sqllx='article';require($_SERVER['DOCUMENT_ROOT'].'/template/call/sql.php');
while($rs=$sql->fetch_array())
{
?>
    <div class="swiper-slide">
      <a href="javascript:void(0);"><img src="<?=ImgAdd($rs['hdimg'.$LT])?>"></a>
    </div>
<?php 
}
?>
  </div>
  <div class="pagination"></div>
  
  <script type="text/javascript" src="/m/js/swiper.js"></script>
  <script>
  var detailSwiper = new Swiper('#banner',{
      autoplay : 5000,//可选选项，自动滑动
      loop : true,//可选选项，开启循环
      pagination : '.pagination',
      calculateHeight : true,
      updateOnImagesReady : true,
  });
  </script>
</div>
<!--  幻灯 结束 --> 

<div style="padding:10px;">
	<?php $m='/m';require_once($_SERVER['DOCUMENT_ROOT'].'/yundan/call/status_form.php');?>
	 <br>
	<?php $m='/m';require_once($_SERVER['DOCUMENT_ROOT'].'/yundan/call/price_form.php');?>
</div> 


<!---->
<?php 
$classid=22;//转运公告
$crs=ClassData($classid);
?>		
<div class="clear">
  <div class="xa_lmtit"><?=cadd($crs['name'.$LT])?><span><a href="<?=pathLT($crs['path'])?>">More></a></span></div>
  <ul class="NewsList">
<?php 
$field='';//特别字段(以,开头)
$where='';//特别条件(以and开头)
$limit=5;
$sqllx='article';require($_SERVER['DOCUMENT_ROOT'].'/m/template/call/sql.php');
while($rs=$sql->fetch_array())
{
$temlx='news';require($_SERVER['DOCUMENT_ROOT'].'/m/template/call/show.php');
}
?>
  </ul>
</div> 
<!---->

	
<div class="xa_lmtit"><?=$LG['index.1'];//转运流程?></div>
<div class="clear" align="center"><img src="<?=HaveFile('/m/images/i1'.$LT.'.gif')?'/m/images/i1'.$LT.'.gif':'/m/images/i1.gif'?>" style="width:100%"/></div> 

<div class="xa_lmtit"><?=$LG['index.2'];//我们的优势?></div>
<div class="clear" align="center"><img src="<?=HaveFile('/m/images/i2'.$LT.'.gif')?'/m/images/i2'.$LT.'.gif':'/m/images/i2.gif'?>" style="width:100%"/></div> 

<!---->
<?php if($off_mall){
$classid=5;//在线商城
$crs=ClassData($classid);
?>
<div class="clear">
  <div class="xa_lmtit"><?=cadd($crs['name'])?><span><a href="/m/mall/list.php">More></a></span></div>
   <div class="IndexImgList">
	<ul>
	<!--商品列表-开始-->
	<?php 
	$field='';//特别字段(以,开头)
	$where='';//特别条件(以and开头)
	$limit=6;
	$sqllx='mall';require($_SERVER['DOCUMENT_ROOT'].'/m/template/call/sql.php');
	while($rs=$sql->fetch_array())
	{
		$rs['unit']=classify($rs['unit'],2);
		?> 
		<li>
		<a href="/m/mall/show.php?mlid=<?=$rs['mlid']?>" target="_blank" title="<?=cadd($rs['title'.$LT])?>">
		<img src="<?=ImgAdd($rs['titleimg'.$LT])?>"/>
		<p><?=spr($rs['price'])?> <?=$XAmc?><?=$rs['unit']?'/'.$rs['unit']:''?></p>
		<?=leng($rs['title'.$LT],25);?>
		</a>
		</li>
		<?php
	}
	?>
	<!--商品列表-结束-->	
	</ul>
  </div>
</div>
<?php }?>
<!---->


<?php 
if(1==2){//页面太长,不启用
	$classid='29';//使用帮助
	$crs=ClassData($classid);
	?>		
	<div class="clear">
	   <div class="xa_lmtit"><?=cadd($crs['name'.$LT])?><span><a href="<?=pathLT($crs['path'])?>">More></a></span></div>
	<?php 
	$field='';//特别字段(以,开头)
	$where='';//特别条件(以and开头)
	$limit=4;
	$sqllx='article';require($_SERVER['DOCUMENT_ROOT'].'/m/template/call/sql.php');
	while($rs=$sql->fetch_array())
	{
	$temlx='help';require($_SERVER['DOCUMENT_ROOT'].'/m/template/call/show.php');
	}
	?>
	</div> 
<?php }?>

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/footer.php');?>
