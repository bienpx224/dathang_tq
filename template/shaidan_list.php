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

	
//获取,处理

$lx=par($_GET['lx']);
$classid=(int)$_GET['classid'];

//验证,查询
if(!$_SESSION['manage']['userid']&&$lx!='html'){XAts('temp');}
if(!$classid){echo ("<script>alert('classid{$LG['pptError']}');goBack('uc');</script>");  goto checked;}

$cr=ClassData($classid);
$headtitle=$cr['seotitle'.$LT]?cadd($cr['seotitle'.$LT]):cadd($cr['name'.$LT]);
$search.="&classid={$classid}";

require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');//放查询的后面
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/nav.php');

//图片扩大插件
require_once($_SERVER['DOCUMENT_ROOT'].'/public/enlarge/call.html');
?>
<link href="/css/shaidan.css" rel="stylesheet" type="text/css"/>

<!--内容开始-->
<style>
.class_banner{ background:url(<?=ClassImg($classid)?>) no-repeat center top;}
</style>
<div class="class_banner"></div>

<div class="center">
<div class="add_fr"><?=Addnav($classid)?></div>
<div class="right_tit"><i class="icon-th-large"></i> <?=cadd($cr['name'.$LT])?> </div>
 
  <div class="ht-mainer" >
    <div class="ht-comblock">
     
      <div class="divblock shareblock">
        <ul>
<?php 
$where="checked='1' and types='0' ";
if($ON_shaidan_language&&$LT!='CN'){$where.=" and language='{$LT}'";}
elseif($ON_shaidan_language&&$LT=='CN'){$where.=" and (language='{$LT}' or language='')";}

$order=' order by sdid desc';//默认排序
$query="select * from shaidan where {$where} {$order}";
$line=8;$page_line=10;//分页处理,不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
while($rs=$sql->fetch_array())
{
?>
          <li class="clearfix">
            <div class="col1 fl"> 
            <img src="<?=FeData('member','img',"userid='{$rs['userid']}'")?>" height="69" width="69" /> </div>
            <div class="col2">
              <div class="name clearfix">
                <h1 class="fl" title="<?=$LG['member']//会员?>"><?=substr_cut($rs['username'],$length=3)?></h1>
                <span class="fr" style="color:#bbbbbb"><?=$LG['awb']//运单号?>：<?=substr_cut($rs['ydh'],$length=3)?> &nbsp;&nbsp; <?=$LG['release']//发布?>：<?=DateYmd($rs['addtime'],2)?> &nbsp;&nbsp; <?=$LG['browse']?>：<script src="/public/onclick.php?table=shaidan&lx=show&id=<?=$rs['sdid']?>"></script> <?=$LG['main.58']?></span></div>
              <div class="title"><?=leng($rs['content'],200,"...")?>
              <a href="<?=pathLT($rs['path'])?>"  class="btn-xs btn-warning" target="_blank"><?=$LG['front.152']//查看全文?></a></div>
              
				<?php if($rs['img']){EnlargeImg($rs['img'],$rs['sdid']); }?>
				
              <div class="list_pl">
			  <a href="<?=pathLT($rs['path'])?>"  class="btn btn-info" target="_blank" >
			  <i class="icon-comments"></i>
			 <?=$LG['comments']//评论?>
			  (<font class="red" title="<?=$LG['front.151']//评论数?>"><script src="/public/onclick.php?table=shaidan&lx=show&field=plclick&id=<?=$rs['sdid']?>"></script></font>)
			  </a> 
			  </div>
			  
            </div>
          </li>
<?php 
	$empty=1;
}
if (!$empty){ echo $LG['pptNot'];} 
?>

        </ul>
      </div>

      <div class="row">
          <?=$listpage?>
        </div>
        
    </div>
  </div>
</div>

<div class="clear"></div>

<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/footer.php');  checked: ?>