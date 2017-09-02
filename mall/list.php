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
$line=15;$page_line=10;//分页处理,不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');//放查询的后面
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/service.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/nav.php');
?>
<!--商城样式开始-->
<link href="/css/mall.css" rel="stylesheet" type="text/css" />

<!--商城样式结束-->

<!--内容开始-->
<style>
.class_banner{
background:url(<?=ClassImg($classid)?>) no-repeat center top;}
</style>
<div class="class_banner"></div>
<div class="center">
  <div class="article_left fl">
    <?php require_once($_SERVER['DOCUMENT_ROOT'].'/mall/call/left.php');?>
  </div>
  <div class="article_right fr">
    <div class="add_fr">
      <?=Addnav($classid)?>
    </div>
    <div class="right_tit"><i class="icon-th-large"></i>
      <?=cadd($cr['name'.$LT])?>
      
    </div>
    <div class="article_content" >
      <div class="clear"></div>
      <!--列表开始-->
      <div class="cat_r"> 
        <div class="clear"></div>
        <div id="fillter">
          <div class="tit"><span><?=$LG['front.147'];//商品筛选?></span></div>
		  <?php require_once($_SERVER['DOCUMENT_ROOT'].'/mall/call/list_options.php');?>
		</div>

        <div class="clear"></div>
        <div style="height:15px;"></div>

		<?php require_once($_SERVER['DOCUMENT_ROOT'].'/mall/call/list_order.php');?>

        <div id="goods_list_cat">
          <div class="ct">
            <div class="bgw p1-0">
            

<?php 
while($rs=$sql->fetch_array())
{
		$rs['unit']=classify($rs['unit'],2);
?>
              <div class="gmg"> 
                <!--<div class="tc_js"><img src="/images/tct2.gif" /></div>--> 
                <a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):'/mall/show.php?mlid='.$rs['mlid'];?>" target="_blank" class="gi" title="<?=cadd($rs['selling'.$LT])?>"> <img src="<?=ImgAdd($rs['titleimg'.$LT])?>" alt="<?=cadd($rs['title'.$LT])?>" width="0" height="0" onload="AutoResizeImage(300,300,this)"/></a>
               
                <div class="gn"><a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):'/mall/show.php?mlid='.$rs['mlid'];?>"  target="_blank" title="<?=fnCharCount(cadd($rs['title'.$LT]))>70?cadd($rs['title'.$LT]):''?>"><?=leng($rs['title'.$LT],70,"...");?></a></div>
                <div class="jiage">
                  <p class="gp"> 
                  
                  <span class="shop_prices">
				  <?=spr($rs['price']).$XAmc?><?=$rs['unit']?'/'.$rs['unit']:''?>
                  </span>
				 
                  
                  <span class="dg_price"> <font>
                  <?=$rs['number_sold']?$LG['front.116'].$rs['number_sold'].$rs['unit']:''?>
                  </font></span>
                  <div class="ggyyc">
					<?php 
					if($rs['ensure']){echo '<span class="label label-sm label-warning">'.$LG['front.100'].'</span>';}
					?>
					</div>
                  </p>
                </div>
                <div class="yc_ggy" align="center"><span>
                
                <?php if($XAMcurrency!=$XAScurrency){?>
               	 <font class="red2"><?=spr($rs['price']*exchange($XAMcurrency,$XAScurrency)).$XAsc?></font>
                <?php }else{?>
               	 <?=$LG['front.115']?><?=$rs['number']?><?=$rs['unit']?>  
                <?php }?>
               
                &nbsp; <?=$LG['comments']?><?=$rs['plclick']?>  &nbsp; <?=$LG['browse']?><?=$rs['onclick']?><?=$LG['main.58'];//次?></span></div>
              </div>
	<?php 
    $noid=$rs['mlid'];
}
if (!$noid){ echo $LG['pptNot'];} 
?>


              
              <div class="clear"></div>
            </div>
          </div>
          <div class="bt"><span></span></div>
        </div>
        <hr size="1">
        <div class="row"><?=$listpage?></div>
      </div>
      
      <!--列表结束--> 
    </div>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>

<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/footer.php');?>
