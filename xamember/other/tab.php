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
$classid=par($_GET['classid']);
$cr=ClassData($classid);
$headtitle=cadd($cr['name'.$LT]);
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a></h3>
    </div>
  </div>
  <!-- END PAGE HEADER-->
  
  <div class="tabbable tabbable-custom boxless">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab"><?=$LG['other.tab_1'];//我的价格?></a></li>
      <?php 
		$i=0+1;//之前没有<li class="active"> 时改为0
		$order=' order by istop desc,isgood desc,ishead desc,edittime desc,addtime desc';//默认排序
		$allclassid=$classid.SmallClassID($classid);
		$query="select id,url{$LT},path,title{$LT},edittime,addtime,titlecolor,content{$LT} from article where checked=1 and classid in ({$allclassid}) {$order} limit 10";
		$sql=$xingao->query($query);
		
		while($rs=$sql->fetch_array())
		{
			$i+=1;
		?>
      <li class="<?=$i==1?'active':'';?>"><a href="#tab_<?=cadd($rs['id'])?>" data-toggle="tab" style="color:<?=cadd($rs['titlecolor'])?>">
        <?=leng($rs['title'.$LT],80,"...");?>
        </a></li>
      <?php 
		}
		?>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab_1">
      <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/other/call/price.php')?>  
      </div>
      <?php 
		$i=0+1;//之前没有<div class="tab-pane active" id="tab_1"> 时改为0
		$sql_c=$xingao->query($query);
		while($rs_c=$sql_c->fetch_array())
		{
			$i+=1;
		?>
      <div class="tab-pane <?=$i==1?'active':'';?>" id="tab_<?=cadd($rs_c['id'])?>">
        <div class="article_ny">
          <?=caddhtml($rs_c['content'.$LT])?>
        </div>
      </div>
      <?php 
		}
		if (!$i){ echo $LG['pptNot'];} 
		?>
    </div>
  </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
