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

$class=(int)$_GET['class'];
if(!$class)
{
	echo ("<script>alert('class{$LG['pptError']}');goBack('uc');</script>");  goto checked;
}
$search.="&class={$class}";
$headtitle=LinkClass($class);
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
        <?php LinkClass($class,2);?>
      </ul>
    </div>
    <div class="clear"></div>
    
    

  </div>
  <div class="article_right fr">
    <div class="add_fr"><?=LinkClass($class)?></div>
    <div class="right_tit"><i class="icon-th-large"></i> <?=LinkClass($class)?></div>
    <div class="article_content" >
      <div class="clear"></div>
      <!---->
      <div id="article" class="yh">
        <div class="cl"></div>
        <div class="article_c">
          <div class="atc_con">
            <div class="atc_cc">
              <div class="atcc_c">
                
                <div class="multipleColumn">
      
          <div class="bd">
            <div class="ulWrap">
    <ul>

<?php 
$order=' order by myorder desc,id desc';//默认排序
$query="select * from link where checked=1 and class='{$class}' {$order}";
$line=15;$page_line=10;//分页处理,不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
while($rs=$sql->fetch_array())
{
?>
                   <li>
                  <div class="pic"><a href="<?=cadd($rs['url'])?>" target="_blank"><img src="<?=ImgAdd($rs['img'])?>" /></a></div>
                  <div class="title"><a href="<?=cadd($rs['url'])?>" target="_blank" title="<?=cadd($rs['name'])?>"><?=leng($rs['name'],20,"...");?></a></div>
                </li>
       
<?php 
}
if (!mysqli_num_rows($sql)){ echo $LG['pptNot'];} 
?>

       
                          </div>
          </div>
        </div>
        
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
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/footer.php');  checked: ?>