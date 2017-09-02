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
$so=(int)$_GET['so'];
$key=par($_GET['key']);
$classid=(int)$_GET['classid'];


$cr=ClassData($classid);
$headtitle=$cr['seotitle'.$LT]?cadd($cr['seotitle'.$LT]):cadd($cr['name'.$LT]);
if(!$headtitle){$headtitle=$LG['search.1'];}

require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');//放查询的后面
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/service.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/nav.php');

//搜索
$where="1=1";
/*if($classid)
{
	$where.=" and classid='{$classid}'";
}
*/
if($so)
{
	if ($_SESSION['so']>strtotime('-5 seconds')){exit ("<script>alert('{$LG['search.2']}');goBack();</script>");}

	if(!$key)
	{
		exit ("<script>alert('{$LG['search.3']}');goBack();</script>");
	}else{
		if(fnCharCount($key)<4){exit ("<script>alert('{$LG['search.4']}');goBack();</script>");}
	}
	
	if($key){$where.=" and (title{$LT} like '%{$key}%')";}
	$search.="&so={$so}&key={$key}&lx={$lx}&classid={$classid}";
	$_SESSION['so']=time();
}
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
        <?php SmallNav($classid);?>
      </ul>
    </div>
    <div class="clear"></div>
    
    <div class="search-container">
      <form action="/search/article.php" method="get">
      <input name="so" type="hidden" value="1">
        <input name="key" value="<?=$key?>" type="text" size="32" class="inputkey"  placeholder="<?=$LG['search.5'];//输入关键词搜索信息?>" required/>
        <input type="submit" class="inputSub btn btn-info"  value="<?=$LG['search']?>" />
      </form>
    </div>
    
  </div>
  <div class="article_right fr">
  <?php if($classid){?><div class="add_fr"><?=Addnav($classid)?></div><?php }?>
  
    <div class="right_tit"><i class="icon-th-large"> </i><?=cadd($cr['name'.$LT])?> <?=$LG['search.7']?> <?=$key?></div>
    <div class="article_content" >
      <div class="clear"></div>
      <!---->
      <div id="article" class="yh">
        <div class="cl"></div>
        <div class="article_c">
          <div class="atc_con">
            <div class="atc_cc">
              <div class="atcc_c">
                <ul class="atl_list">
<?php 
if($so)
{
	$order=' order by istop desc,isgood desc,ishead desc,edittime desc,addtime desc';//默认排序
	$query="select id,url{$LT},path,title{$LT},edittime,addtime,titlecolor from article where {$where} and checked=1 {$order}";
	$line=15;$page_line=10;//分页处理,不设置则默认
	include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
	while($rs=$sql->fetch_array())
	{
	?>
        <li class="atl_list_li">
        <span style="float:left"><a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):pathLT($rs['path'])?>" target="_blank"  style="color:<?=cadd($rs['titlecolor'])?>"><?=leng($rs['title'.$LT],80,"...");?></a></span>
        <span style="float:right; padding-top:10px;" class="gray_prompt2">[<?=DateYmd($rs['edittime'],2,$rs['addtime'])?>]</span>
        </li>
	<?php 
	}
	if (!mysqli_num_rows($sql)){ echo $LG['pptNot'];} 
}else{ echo $LG['search.6'];} 
?>
                </ul>
                <div class="cl"></div>
                <br />
         <div class="row">
          <?=$listpage?>
        </div>
              </div>
            </div>
          </div>
        </div>
        <div class="cl"></div>
      </div>
      <!---->
    </div>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>

<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/footer.php');?>