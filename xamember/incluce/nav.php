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
$member=1;require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/nav.php');


//自动弹出
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/msg/call/popup.php');
?>
<style>
/*前台模板*/
#header {
	height: 125px !important;
	margin-top: 45px;
}
</style>

<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top"> 
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="header-inner"> 
  

    <!-- END LOGO --> 
    <!--移动版自动显示导航分类按钮--> 
    <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <img src="/bootstrap/img/menu-toggler.png" alt="" /> <?=$LG['name.nav_1']?></a>  
    
      <div class="huilu" title="<?=$LG['name.nav_2'];//每3小时更新一次?>"> 
      <font><?=$LG['name.nav_3']?> </font>
      <div class="txtScroll-top">
        <div class="bd">
			<script>document.write('<script src="/public/exchangeJS.php?up=0&t='+Math.random()+'"><'+'/script>');</script>
        </div>
      </div>
      <script type="text/javascript">
      jQuery(".txtScroll-top").slide({mainCell:".bd ul",autoPage:true,effect:"topLoop",autoPlay:true,vis:1,interTime:4500});
      </script> 
  </div>

    <!-- BEGIN TOP NAVIGATION MENU -->
    
    <ul class="nav navbar-nav pull-<?=$ism?'left':'right'?>">
      <!-- BEGIN NOTIFICATION DROPDOWN -->
            <li class="dropdown" id="header_notification_bar"> <a href="javascript:;"  onClick="cache_up()" class="dropdown-toggle" data-close-others="true" style="width:35px;" title="<?=$LG['name.nav_4'];//更新菜单数量?>"> <i class="icon-refresh"></i> </a> </li>
      <li class="devider">&nbsp;</li>

<?php if($off_mall){?>
      <li class="dropdown" id="header_task_bar"> <a <?=$ism?'href="javascript:void(0)" data-toggle="dropdown"':'href="/xamember/mall_order/list.php?pay=0"'?> class="dropdown-toggle"  data-hover="dropdown"  data-close-others="true" style="padding-right:10px;" onClick="cart_update();"><!--data-toggle="dropdown" 链接失效--> <i class="icon-shopping-cart"></i> <span id="cart_number"></span></a>
        <ul class="dropdown-menu extended inbox">
          <li>
            <a href="javascript:;" onClick="cart_update();"><i class="icon-refresh"></i> <?=$LG['name.nav_5']?></a>
          </li>
          <li>
            <ul class="dropdown-menu-list scroller" style="height: 250px;">
			<span id="cart_list"></span>
            </ul>
          </li>
          <li class="external"> <a href="/xamember/mall_order/list.php?pay=0"><?=$LG['name.nav_65']?> <i class="icon-angle-right"></i></a> </li>
        </ul>
      </li>
      <li class="devider">&nbsp;</li>
<?php }?>
    
      <li class="dropdown" id="header_inbox_bar"> <a  <?=$ism?'href="javascript:void(0)" data-toggle="dropdown"':'href="/xamember/msg/list.php"'?> class="dropdown-toggle"  data-hover="dropdown"  data-close-others="true" onClick="msg_update();"><!--data-toggle="dropdown" 链接失效-->
	   <i class="icon-envelope"></i> <span id="msg_number"></span> </a>
        <ul class="dropdown-menu extended inbox">
          <li>
             <a href="javascript:;" onClick="msg_update();"><i class="icon-refresh"></i><?=$LG['name.nav_5'];//点击更新?></a>
          </li>
          <li>
            <ul class="dropdown-menu-list scroller" style="height: 250px;">
             <span id="msg_list"></span>
            </ul>
          </li>
          <li class="external"> <a href="/xamember/msg/list.php?my=1" ><?=$LG['name.nav_65']?> <i class="icon-angle-right"></i></a> </li>
        </ul>
      </li>
      <li class="devider">&nbsp;</li>
      <!-- END NOTIFICATION DROPDOWN --> 
	  

      <!-- BEGIN TODO DROPDOWN -->
      <?php if($ON_LG){?>
      <li class="dropdown" id="header_task_bar"> <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> <i class="icon-font"></i><?=$LG['language']?> </a>
        <ul class="dropdown-menu extended tasks" style="width:100px; text-align: center;">
          <li>
            <ul class="dropdown-menu-list">
<?php 
$languageList=languageType('',6);
if($languageList)
{
	foreach($languageList as $arrkey=>$value)
	{
		?>
        <li><a href="/Language/?LGType=1&language=<?=$value?>"> <font class="<?=$value==$LT?'red2':''?>"><?=languageType($value)?></font></a> </li>
		<?php 
	}
}
?>
            </ul>
          </li>
        </ul>
      </li>
      <!-- END TODO DROPDOWN -->
      <li class="devider">&nbsp;</li>
      <?php }?>
      
      <!-- BEGIN USER LOGIN DROPDOWN -->
      <li class="dropdown user">
      <a  <?=$ism?'href="javascript:void(0)" data-toggle="dropdown"':'href="/xamember/data/show.php"'?>  class="dropdown-toggle" data-hover="dropdown" data-close-others="true" title="<?=$LG['Muserid']?>：<?=$memberid_tpre?><?=$Muserid?> &#13;<?=$LG['Mgroupid']?>：<?=$member_per[$Mgroupid]['groupname'];?>"> <img  src="<?=$user_img=FeData('member','img',"userid='{$Muserid}'");?>"  width="28" height="28"/> <span class="username"><?=$Mtruename.' ('.$Musername.')'?></span> <i class="icon-angle-down"></i> </a>
      
      <ul class="dropdown-menu">
        <li><a href="/xamember/main.php"><i class="icon-user"></i> <?=$LG['name.nav_7'];//会员中心?></a> </li>
        <li><a href="/xamember/data/form.php"><i class="icon-edit"></i> <?=$LG['name.nav_8'];//修改资料?></a> </li>
		<?php if($off_tuiguang){?>
        <li><a href="/xamember/tuiguang/tuiguang_bak.php"><i class="icon-group"></i> <?=$LG['name.nav_9'];//我的推广?></a> </li>
		<?php }?>
         <li class="divider"></li>
        <li><a href="/xamember/login_save.php?lx=logout" onclick="return confirm('{$LG['name.nav_10']}');"><i class="icon-lock"></i> <?=$LG['name.nav_11'];//退出登录?></a> </li>
      </ul>
      
      </li>
      
      <!-- END USER LOGIN DROPDOWN -->
    </ul>
    <!-- END TOP NAVIGATION MENU --> 
  </div>
  <!-- END TOP NAVIGATION BAR --> 
</div>
<!-- END HEADER -->
















<div class="clearfix"></div>
<!-- BEGIN CONTAINER -->
<div class="page-container"> 
  <!-- BEGIN SIDEBAR -->
  
  <?php if($ism){?>
	<div class="page-sidebar navbar-collapse collapse"> 
  <?php }else{?>
	<div class="page-sidebar"> 
  <?php }?>
  
  <!--手机或浏览器放大200%时左菜单不见问题修改
  原(自动隐藏):<div class="page-sidebar navbar-collapse collapse">
  改(固定显示):<div class="page-sidebar"> 
   -->
   
    <!-- BEGIN SIDEBAR MENU -->
    <?php
	if(!$_SESSION['cache_member']||$_SESSION['cache_member_time']<=strtotime('-3 minutes'))//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds (数据要统计,更新时间不要太长)
	{
		ob_start();//开始缓冲
	?>
    <ul class="page-sidebar-menu" id="act_nav">
      <li> 
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="sidebar-toggler"></div>
        <div class="clearfix"></div>
        <!-- BEGIN SIDEBAR TOGGLER BUTTON --> 
      </li>
      <li class="start"> <a href="/xamember/main.php"> <i class="icon-home"></i> <span class="title"><?=$LG['name.nav_12'];//会员主页?></span> </a> </li>
<!--      <li class="active"> <a href="javascript:;"> <i class="icon-flag"></i> <span class="title"><?=$LG['name.nav_13'];//常用?></span> <span class="arrow open"></span> </a>
        <ul class="sub-menu">
-->		
         
         <?php if($off_baoguo&&$member_per[$Mgroupid]['ON_Mbaoguo']){?>
         <!--$nav_act=Act('/baoguo/,main.php')-->
		  <li <?=$nav_act=1?'class="open"':''?>><a href="javascript:;"><i class="icon-dropbox"></i> <?=$LG['parcel'];//包裹?><span class="arrow <?=$nav_act?'open':''?>"></span></a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
			
              <li><a href="/xamember/baoguo/list.php?status=<?=$CN_zhi='all'?>" > <?=$theme_member_ico?'<i class="icon-chevron-right"></i>':''?><?=$LG['name.nav_66'];//包裹管理?></a></li>


			<?php if($off_baoguo_yubao){?>
              <li><a href="/xamember/baoguo/add_form.php"><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_14'];//包裹预报?></a></li>
			<?php }?>
			<?php if($off_baoguo_zxyd){?>
              <li><a href="/xamember/baoguo/add_form.php?bg_zxyd=1" class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['name.nav_15'];//包裹入库后就扣费发货?>"><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_16'];//快速下单?></a></li>
			<?php }?>
              
            </ul>
          </li>
		<?php }?>
        
		  <!--$nav_act=Act('/yundan/,main.php')-->
          <li <?=$nav_act=1?'class="open"':''?>><a href="javascript:;"> <i class="icon-plane"></i> <?=$LG['name.nav_67'];//运单?> <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
			
              <li><a href="/xamember/yundan/list.php?status=all" ><?=$theme_member_ico?'<i class="icon-chevron-right"></i>':''?><?=$LG['name.nav_17'];//运单管理?></a></li>


			<?php if($member_per[$Mgroupid]['off_zjxd']){?>
              <li><a href="/xamember/yundan/form.php" class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['name.nav_18'];//自送或申请上门取件下单发货?>"><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_19'];//直接下单?></a></li>
              <li><a href="/xamember/yundan/excel_import.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_20'];//批量导入?></a></li>
			<?php }?>
            </ul>
          </li>

<?php if($ON_daigou&&$member_per[$Mgroupid]['daigou']){?>
         <!--$nav_act=Act('/daigou/')-->
          <li <?=$nav_act=1?'class="open"':''?>><a href="javascript:;"> <i class="icon-retweet"></i> <?=$LG['name.nav_70']?> <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
            
<li><a href="/xamember/daigou/list.php?status=all" ><?=$theme_member_ico?'<i class="icon-chevron-right"></i>':''?><?=$LG['name.nav_23']?></a></li>

<li><a href="/xamember/daigou/form.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_22'];//我要代购?></a></li>

    <li>&nbsp;</li>
  
<?php if($member_per[$Mgroupid]['off_tixian']){?>
    <li><a href="/xamember/tixian/list.php" ><?=$theme_member_ico?'<i class="icon-chevron-right"></i>':''?><?=$LG['name.nav_71'];//提现管理?></a>
    
    <li><a href="/xamember/tixian_zh/list.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_51'];//提现账户?></a></li>
    <li><a href="/xamember/data/form.php?tab=2" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_52'];//提现密码?></a></li>
<?php }?>  

<li><a href="/xamember/msg/form.php?status=11&title=代购询价" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['daigou.enquiry'];//我要询价?></a></li>

            </ul>
          </li>
<?php }?>

          
          
<?php if($off_mall){?>
			<!--直接购买商品时用到mall_order,因此不加/mall_order/-->
          <li <?=$nav_act=Act('mall_order')?'class="open"':''?>><a href="javascript:;"> <i class="icon-shopping-cart"></i> <?=$LG['name.nav_68'];//商城?> <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>

              <li><a href="/xamember/mall_order/list.php?pay=<?=$CN_zhi=0?>" ><?=$theme_member_ico?'<i class="icon-chevron-right"></i>':''?><?=CountNum($CN_table='mall_order',$CN_field='pay',$CN_zhi,$CN_where='',$CN_userid=$Muserid,$CN_color='warning');?><?=$LG['name.nav_69']?></a></li>

            <li><a href="/xamember/mall_order/list.php?so=1&pay=<?=$CN_zhi=1;?>"><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?>
              <?=$LG['name.nav_21']?></a></li>


            </ul>
          </li>
<?php }?>

<!--        </ul>
      </li>
-->  

 
<?php if($ON_gd_mosuda&&$ON_gd_mosuda_apply){?>
<li><a href="/xamember/gd_mosuda/list.php" > <i class="icon-stackexchange"></i> <span class="title"><?=$LG['gd.7'];//商品备案?></span> </a></li>
<?php }?>



<?php if($member_per[$Mgroupid]['off_qujian']){?>
<li><a href="/xamember/qujian/list.php"> <i class="icon-upload-alt"></i> <span class="title"><?=$LG['name.nav_27'];//上门取件?></span> </a></li>
<?php }?>

 

<?php if($member_per[$Mgroupid]['off_lipei']){?>
<li><a href="/xamember/lipei/list.php" > <i class="icon-money"></i> <span class="title"><?=$LG['name.nav_31'];//理赔?></span> </a></li>
<?php }?>






<?php if($off_shaidan){?>
<li><a href="/xamember/shaidan/list.php" > <i class="icon-star"></i> <span class="title"><?=$LG['name.nav_53'];//晒单?></span> </a></li>
<?php }?>



<li><a href="/xamember/address/list.php" > <i class="icon-list-alt"></i> <span class="title"><?=$LG['name.nav_57'];//地址簿?></span> </a></li>
<li><a href="/xamember/other/tab.php?classid=<?=$price_classid?>" ><i class="icon-jpy"></i> <span class="title"><?=$LG['name.nav_58'];//资费说明?></span></a></li>

<li><a href="/xamember/other/warehouse.php" ><i class="icon-archive"></i> <span class="title"><?=$LG['name.nav_59'];//仓库地址?></span></a></li>



<li <?=$nav_act=Act('/money/,/integral/','/tixian/,/tixian_zh/,form.php?tab=2')?'class="open"':''?>><a href="javascript:;"> <i class="icon-cny"></i> <span class="title"><?=$LG['name.nav_35'];//财务?></span> <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
  <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
  
    <li><a href="/xamember/money/money_cz.php" ><?=$theme_member_ico?'<i class="icon-chevron-right"></i>':''?><?=$LG['name.nav_36'];//在线充值?></a></li>
    <?php if($ON_bankAccount){?>
    <li><a href="/xamember/transfer/form.php" ><?=$theme_member_ico?'<i class="icon-chevron-right"></i>':''?><?=$LG['name.nav_37'];//转账充值?></a></li>
    <?php }?>
              
  <?php if($member_per[$Mgroupid]['off_settlement']){?>
  <li><a href="/xamember/settlement/list_yundan.php" ><?=$theme_member_ico?'<i class="icon-chevron-right"></i>':''?><?=$LG['name.nav_38'];//月结运单?></a></li>
  <li><a href="/xamember/settlement/list_other.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_39'];//月结其他?></a></li>
  <?php }?>
  
   <li>&nbsp;</li>
    
   <li><a href="/xamember/coupons/list.php" ><?=$theme_member_ico?'<i class="icon-chevron-right"></i>':''?><?=$LG['name.nav_40'];//优惠券/折扣券?></a></li>

    <li>&nbsp;</li>
    <li><a href="/xamember/money/money_bak.php" ><?=$theme_member_ico?'<i class="icon-chevron-right"></i>':''?><?=$LG['name.nav_41'];//资金流水账?></a></li>
    <li><a href="/xamember/money/money_czbak.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_42'];//充值记录?></a></li>
    <li><a href="/xamember/money/money_kfbak.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_43'];//消费记录?></a></li>
    
    <li>&nbsp;</li>
    
    <li><a href="/xamember/integral/integral_bak.php" ><?=$theme_member_ico?'<i class="icon-chevron-right"></i>':''?><?=$LG['name.nav_44'];//积分流水账?></a></li>
    <li><a href="/xamember/integral/integral_czbak.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_45'];//积分获取记录?></a></li>
    <li><a href="/xamember/integral/integral_kfbak.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_46'];//积分消费记录?></a></li>
  



   </ul>
</li>



<li <?=$nav_act=Act('/data/,/tuiguang/,/connect/,/log/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-user"></i> <span class="title"><?=$LG['name.nav_60'];//账号管理?></span> <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
  <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
    <li><a href="/xamember/data/show.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_61'];//我的资料?></a></li>
    <li><a href="/xamember/data/form.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_8'];//修改资料?></a></li>
    <li><a href="/xamember/data/up_groupid.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_62'];//会员升级?></a></li>
    <?php if($off_tuiguang){?>
    <li><a href="/xamember/tuiguang/tuiguang_bak.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_9'];//我的推广?></a></li>
    <?php }?>
    <li><a href="/xamember/connect/list.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_63'];//快捷登录?></a></li>
    <li><a href="/xamember/log/list.php" ><?=$theme_member_ico?'<i class="icon-caret-right"></i>':''?><?=$LG['name.nav_64'];//登录日志?></a></li>
  </ul>
</li>

    </ul>
    <?php
		$_SESSION['cache_member']=ob_get_contents();//得到缓冲区的数据
		$_SESSION['cache_member_time']=time();
		ob_end_clean();//结束缓存：清除并关闭缓冲区
	}
	echo $_SESSION['cache_member'];
	?>
    <!-- END SIDEBAR MENU --> 
  </div>
  <!-- END SIDEBAR --> 
  <?php $CN_table='';$CN_field='';$CN_zhi='';$CN_where='';$CN_userid='';$CN_color='';//清空,防止调用上面参数?>
  <!-- BEGIN PAGE -->
 
  <div class="page-content"> 
    
<script>
//高亮显示
var myNav = document.getElementById("act_nav").getElementsByTagName("a"); 
for(var i=0;i<myNav.length;i++) 
{ 
	var links = myNav[i].getAttribute("href"); 
	var myURL = document.location.href; 
	
	//先把非 ?headtitle= 的?后面的参数删除再判断，否则分类时有参数就不相同，就不能高亮
/*	if(links.indexOf("?")>=0&&links.indexOf("?headtitle=")<0){links = links.split("?")[0];}
	if(myURL.indexOf("?")>=0&&links.indexOf("?headtitle=")<0){myURL = myURL.split("?")[0];}
*/	
	//指定哪些是不能展开的，否则有错误
	if(myURL.indexOf(links)>0&&myURL.indexOf('main.php')<0&&myURL.indexOf('/gd_mosuda/')<0&&myURL.indexOf('/address/')<0&&myURL.indexOf('/other/')<0&&myURL.indexOf('/shaidan/')<0&&myURL.indexOf('/lipei/')<0&&myURL.indexOf('/qujian/')<0) 
	{
		//parentNode上级标签，节点
		myNav[i].parentNode.parentNode.parentNode.className = "open";
		myNav[i].parentNode.parentNode.style.display = "block";
	} 
} 


//更新缓存
function cache_up() 
{
	var cache_xmlhttp=createAjax(); 
	if (cache_xmlhttp) 
	{  
		cache_xmlhttp.open('get','/public/cache_up.php?lx=1&n='+Math.random(),true); 
		cache_xmlhttp.onreadystatechange=function() 
		{  
			if (cache_xmlhttp.readyState==4 && cache_xmlhttp.status==200) 
			{ 
				//var zhi=unescape(cache_xmlhttp.responseText);
				window.location.href=window.location.href;//更新缓存后再刷新本页
			}
		}
		cache_xmlhttp.send(null); 
	}
}
</script> 
