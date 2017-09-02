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
    
    <div class="search-container">
      <form action="/search/article.php" method="get">
      <input name="so" type="hidden" value="1">
        <input name="key" value="" type="text" size="32" class="inputkey"  placeholder="<?=$LG['search.5']?>" required/>
        <input type="submit" class="inputSub btn btn-info"  value="<?=$LG['search']?>" />
      </form>
    </div>

  </div>
  <div class="article_right fr">
    <div class="add_fr"><?=Addnav($classid)?></div>
    <div class="right_tit"><i class="icon-th-large"></i> <?=cadd($cr['name'.$LT])?> </div>
    <div class="article_content">
       <div class="blog-page">
      <div class="article-block">
                 
 <?php 
$order=' order by istop desc,isgood desc,ishead desc,edittime desc,addtime desc';//默认排序
$allclassid=$classid.SmallClassID($classid);
$query="select id,url{$LT},path,title{$LT},edittime,addtime,titlecolor,intro{$LT} from article where checked=1 and classid in ({$allclassid}) {$order}";
$line=5;$page_line=10;//分页处理,不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
while($rs=$sql->fetch_array())
{
?>
              
                  <div class="row">
                        
                        <div class="blog-article">
                           <h4><a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):pathLT($rs['path'])?>" target="_blank"  style="color:<?=cadd($rs['titlecolor'])?>" title="<?=fnCharCount(cadd($rs['title'.$LT]))>70?cadd($rs['title'.$LT]):''?>"><i class="icon-file-alt"> </i><?=leng($rs['title'.$LT],70,"...");?></a></h4>
                            <li class="gray2"><i class="icon-calendar"></i> <?=DateYmd($rs['edittime'],2,$rs['addtime'])?></li>
                           <p><?=leng($rs['intro'.$LT],500,"...")?></p>
                           <a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):pathLT($rs['path'])?>" target="_blank"  class="btn btn-info fr" >查看全文 </a>
                        </div>
                     </div>
<?php 
}
if (!mysqli_num_rows($sql)){ echo $LG['pptNot'];} 
?>
                  </div>
                  </div>
                  
                  
         <div class="row">
          <?=$listpage?>
        </div>
    </div>
    
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>

<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/footer.php');  checked: ?>