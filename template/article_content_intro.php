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

// /template/article_content.php?id=13
	
//获取,处理

$lx=par($_GET['lx']);
$id=(int)$_GET['id'];

//验证,查询
if(!$_SESSION['manage']['userid']&&$lx!='html'){XAts('temp');}
if(!$id){echo ("<script>alert('id{$LG['pptError']}');goBack('uc');</script>");  goto checked; }
$rs=FeData('article','*',"id='{$id}'");

$classid=$rs['classid'];//每页必须有$classid
$cr=ClassData($classid);

$headtitle=$rs['seotitle'.$LT]?cadd($rs['seotitle'.$LT]):cadd($rs['title'.$LT]);
$headtitle.='-'.cadd($cr['name'.$LT]);

require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');//放查询的后面

if(!$rs['checked']){echo XAts('checked');  goto checked;}



require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/nav.php');
?>

<!--内容开始-->
<style>
.class_banner{ background:url(<?=ClassImg($classid)?>) no-repeat center top;}
</style>
<div class="class_banner"></div>

<div class="center">
  <div class="article_left fl">
  
    <div class="inbox">
      <ul class="inbox-nav">
        <?php SmallNav($classid,0,1);?>
      </ul>
    </div>
    <div class="clear"></div>
    
    <!--<div class="search-container">
      <form action="/e/search/index.php" method="post">
        <input type=hidden name=classid value=177>
        <input type="hidden" name="tempid" value="1" />
        <input type="hidden" name="show" value="title" />
        <input name="keyboard" type="text" size="32"  class="inputkey"  placeholder="<?=$LG['search.5']?>" required/>
        <input type="submit" class="inputSub btn btn-info"  value="<?=$LG['search']?>" />
      </form>
    </div>-->
  </div>
  <div class="article_right fr">
    <div class="add_fr"><?=Addnav($classid)?></div>
    <div class="right_tit"><i class="icon-th-large"></i>  <?=cadd($cr['name'.$LT])?> </div>
    <div class="article_content" >
      <div class="clear"></div>
	 <!--图片自动缩小-->
	 <script type="text/javascript" src="/js/jQuery.autoIMG.min.js"></script>
	 <?php echo '<script type="text/javascript">$(function(){	$("#autoimg").autoIMG();});</script>';//用PHP输出不然DW里提示JS错误?>
     <div class="article_ny" id="autoimg"> 
     	
        <div>
        <p>
		
<link rel="stylesheet" type="text/css" href="/css/temp_article.css" />
<div class="merchantlogo clearfix">
	<div class="fl">
	<a href="<?=cadd($rs['seokey'.$LT])?>" class="merchantlogo-pic" target="_blank">
	<img src="<?=ImgAdd($rs['img'.$LT])?>" class="verCenter" onload="AutoResizeImage(248,175,this);"/>
	</a>
	</div>
	<div class="merchantlogo-text fl">
		<div  class="title"><span><?=cadd($rs['title'.$LT])?></span></div>
		<ul>
			<li>
				<table>
					<tr>
						<td valign="top" style="width:75px"><?=$LG['front.149']//网址:?></td>
						<td><a href="<?=cadd($rs['seokey'.$LT])?>"  target="_blank"><?=cadd($rs['seokey'.$LT])?></a></td>
					</tr>
				</table>
			</li>
			<li>
				<table>
					<tr>
						<td valign="top" style="width:75px"><?=$LG['front.150']//简介:?></td>
						<td class="w528"><?=cadd($rs['intro'.$LT])?></td>
					</tr>
				</table>
			</li>
		</ul>
	</div>
</div>
<!--商家更多信息开始-->
<div class="merchantinfobox">
	<p><?=caddhtml($rs['content'.$LT])?></p>
</div>
<!--商家更多信息结束-->
	
		
		
		
		</p>

		<?php if($rs['dow'.$LT]){?>
        <p align="center"><a href="<?=cadd($rs['dow'.$LT])?>" target="_blank" class="btn btn-info input-medium"> <i class="icon-arrow-down"></i> <?=$LG['download']?> </a></p>
		<?php }?>
		
        </div>
        <br />
        <div align="right" class="gray_prompt2">
         <i class="icon-eye-open" title="<?=$LG['browse']?>"></i> <script src="/public/onclick.php?table=article&id=<?=cadd($rs['id'])?>"></script> <?=$LG['main.58']?>
       <i class="icon-calendar" style="margin-left:20px;"></i> <?=DateYmd($rs['edittime'],2,$rs['addtime'])?>
        </div>
        
        </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>

<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/footer.php');  checked: ?>