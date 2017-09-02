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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$noper=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');

/*
实时更新调用：
<script>
document.write('<script src="/template/incluce/navTop.php?t='+Math.random()+'"><'+'/script>');
</script>

不实时更新调用：<script src="/template/incluce/navTop.php"></script>
*/

//没登录-----------------------------------------------------------------
if(!$Mgroupid){
?>
    document.write("<a href=\"/xamember/\"><?=$LG['data.show_36'];//登录?></a>|");
    
    <?php if($off_connect_qq){?>
    document.write("<a href=\"/api/login/qq/\" target=\"_blank\"><?=$LG['front.54'];//QQ登录?></a>|");
    <?php }?>
    
    <?php if($off_connect_weixin){?>
    document.write("<a href=\"/api/login/weixin/\" target=\"_blank\"><?=$LG['front.55'];//微信登录?></a>|");
    <?php }?>
    
    <?php if($off_connect_alipay){?>
    document.write("<a href=\"/api/login/alipay/\" target=\"_blank\"><?=$LG['front.56'];//支付宝登录?></a>|");
    <?php }?>
    
    document.write("<a href=\"/xamember/reg.php\"><?=$LG['front.57'];//注册?></a>");
    
    <?php if($ON_daigou){?>
    document.write("|<a href=\"/xamember/daigou/form.php\"><?=$LG['function_types.134'];//代购商品?></a>");
    <?php }?>
    
    <?php if($off_upload_cert){?>
    document.write("|<a href=\"/yundan/upload.php\" style=\"color:#F5B100\"><?=$LG['front.2'];//上传证件?></a>");
    <?php }?>
    
<?php if($ON_LG){?>
    document.write("<div class=\"yuyan\">|<a href=\"javascript:void(0)\"><?=$LG['language'];//语言?></a><span>");
	<?php 
    $languageList=languageType('',6);
    if($languageList)
    {
        foreach($languageList as $arrkey=>$value)
        {
            ?>
            document.write("<a href=\"/Language/?LGType=1&language=<?=$value?>\" class=\"<?=$value==$LT?'hover':''?>\"><?=languageType($value)?></a>");
            <?php 
        }
    }
    ?>
    document.write("<\/span><\/div>");
<?php }?>

<? }

//登录后-----------------------------------------------------------------
else{ ?>
	<?php
	if($off_connect_qq_checked&&$_SESSION['connect']['img'])
	{
		echo 'document.write("'.'<img src=\"'.$_SESSION['connect']['img'].'\" width=\"30\"> '.$_SESSION['connect']['nickname'].'");';
	}
	?>			
	
    document.write("<a href=\"/xamember/\" title=\"<?=$Musername?>\"><strong><?=$LG['name.nav_7'];//会员中心?></strong></a>");
       
	<?php 
    $num=mysqli_num_rows($xingao->query("select id from msg where new='1' {$Mmy}"));
    if($num){
    ?>
    document.write("<a href=\"/xamember/?url=msg/list.php\" title=\"<?=LGtag($LG['front.59'],'<tag1>=='.$num)?>\">(<font class=\"red\"><?=$num?></font><?=$LG['front.60']?>)</a>|");
    <?php }?>
       
    <?php if($off_mall){
	$num=mysqli_num_rows($xingao->query("select odid from mall_order where pay='0' and status<>'3' {$Mmy}"));
	?>
    document.write("<a href=\"/xamember/mall_order/list.php?pay=0\" style=\"color:#F7891A\"><?=$LG['front.61']?>(<font class=\"red\"><?=$num?></font>)</a>|");
    <?php }?>
       
    document.write("<a href=\"/xamember/baoguo/list.php?status=ruku\"><?=$LG['front.62']?></a>|");
    document.write("<a href=\"/xamember/yundan/list.php\"><?=$LG['front.63']?></a>|");
       
	<?php if($ON_daigou&&$member_per[$Mgroupid]['daigou']){?>
    document.write("<a href=\"/xamember/daigou/form.php\"><?=$LG['function_types.134'];//代购商品?></a>|");
    <?php }?>
    
    <?php if($off_upload_cert){?>
    document.write("<a href=\"/yundan/upload.php\"><?=$LG['front.2'];//上传证件?></a>|");
    <?php }?>
     
<?php if($ON_LG){?>
    document.write("<div class=\"yuyan\">|<a href=\"javascript:void(0)\"><?=$LG['language'];//语言?></a><span>");
	<?php 
    $languageList=languageType('',6);
    if($languageList)
    {
        foreach($languageList as $arrkey=>$value)
        {
            ?>
            document.write("<a href=\"/Language/?LGType=1&language=<?=$value?>\" class=\"<?=$value==$LT?'hover':''?>\"><?=languageType($value)?></a>");
            <?php 
        }
    }
    ?>
    document.write("<\/span><\/div>");
<?php }?>

      
    document.write("<a href=\"/xamember/login_save.php?lx=logout\" onclick=\"return confirm(\'<?=$LG['front.64']?>\');\"><?=$LG['front.58'];//退出?></a>");
<?php }?>