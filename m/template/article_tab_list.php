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

//http://zy/m/template/article_tab_list.php?classid=29
	
//获取,处理

$lx=par($_GET['lx']);

$classid=(int)$_GET['classid'];

//验证,查询
if(!$_SESSION['manage']['userid']&&$lx!='html'){XAts('temp');}
if(!$classid)
{
	echo ("<script>alert('classid{$LG['pptError']}');goBack('uc');</script>");  goto checked;
}

$cr=ClassData($classid);
$headtitle=$cr['seotitle'.$LT]?cadd($cr['seotitle'.$LT]):cadd($cr['name'.$LT]);
$search.="&classid={$classid}";

require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');//放查询的后面
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');//放查询的后面
?>

<!--内容开始-->
<!--图片自动缩小-->
<script type="text/javascript" src="/js/jQuery.autoIMG.min.js"></script>
<?php echo '<script type="text/javascript">$(function(){	$("#autoimg").autoIMG();});</script>';//用PHP输出不然DW里提示JS错误?>
<div class="article_ny" id="autoimg"> 
<span class="title"><?=cadd($cr['name'.$LT])?> </span>
   <div class="tabbable tabbable-custom boxless"> 
	  <!--选项卡，用程序真实调用而不是JS做在 safety_form.php-->
	  <ul class="nav nav-tabs">
		<?php 
		$i=0;
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
		<?php 
		$i=0;
		$sql_c=$xingao->query($query);
		while($rs_c=$sql_c->fetch_array())
		{
			$i+=1;
		?>
		<div class="tab-pane <?=$i==1?'active':'';?>" id="tab_<?=cadd($rs_c['id'])?>">
		  <?=caddhtml($rs_c['content'.$LT])?>
		</div>
		<?php 
		}
		if (!$i){ echo $LG['pptNot'];} 
		?>
	  </div>
	</div>
  </div>
<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/footer.php');  checked: ?>
