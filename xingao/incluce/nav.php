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

//自动弹出
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/msg/call/popup.php');
?>

<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top"> 
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="header-inner"> 
    <!-- BEGIN LOGO --> 
    
    <!-- END LOGO --> 
    <!-- BEGIN RESPONSIVE MENU TOGGLER --> 
    <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <img src="/bootstrap/img/menu-toggler.png" /> </a> 
    <!-- END RESPONSIVE MENU TOGGLER --> 
    <!-- BEGIN TOP NAVIGATION MENU -->
    <ul class="nav navbar-nav pull-right">
      <!-- BEGIN NOTIFICATION DROPDOWN -->
      <li class="dropdown" id="header_notification_bar"> <a href="javascript:;"  onClick="cache_up()" class="dropdown-toggle" data-close-others="true" style="width:35px;" title="更新菜单数量"> <i class="icon-refresh"></i> </a> </li>
      <li class="devider">&nbsp;</li>
      <li class="dropdown" id="header_notification_bar"> <a href="/" class="dropdown-toggle" data-close-others="true" style="width:35px;" title="网站首页"  target="_blank"> <i class="icon-home"></i> </a> </li>
      <li class="devider">&nbsp;</li>
      
       <li class="dropdown" id="header_notification_bar"> <a href="/xamember/" class="dropdown-toggle" data-close-others="true" style="width:35px;" title="会员中心"  target="_blank"> <i class="icon-user"></i> </a> </li>
      <li class="devider">&nbsp;</li>

     <!-- END NOTIFICATION DROPDOWN -->
      
<?php if(permissions('member_le','','manage',1)){?>
      <!-- BEGIN INBOX DROPDOWN 留言-->
      <li class="dropdown" id="header_inbox_bar"> <a href="/xingao/msg/list.php"   class="dropdown-toggle"  data-hover="dropdown"  data-close-others="true" onClick="msg_update();"><!--data-toggle="dropdown" 链接失效-->  <i class="icon-envelope"></i> <span id="msg_number"></span> </a>
        <ul class="dropdown-menu extended inbox">
          <li>
		    <a href="javascript:;" onClick="msg_update();"><i class="icon-refresh"></i>点击更新</a>
          </li>
          <li>
            <ul class="dropdown-menu-list scroller" style="height: 250px;">
			 <span id="msg_list"></span>
            </ul>
          </li>
          <li class="external"> <a href="/xingao/msg/list.php?my=1" >查看全部 <i class="icon-angle-right"></i></a> </li>
        </ul>
      </li>
      <!-- END INBOX DROPDOWN -->
       <li class="devider">&nbsp;</li>
       <?php }?>
  
  
  
  
      <!-- BEGIN TODO DROPDOWN -->
      <?php if($ON_LG){?>
      <li class="dropdown">
       <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> <i class="icon-font"></i></a>
            <ul class="dropdown-menu" style="text-align: center;">
<?php 
$languageList=languageType('',3);
if($languageList)
{
	foreach($languageList as $arrkey=>$value)
	{
		?>
        <li><a href="/Language/?LGType=1&language=<?=$value?>"><font class="<?=$value==$LT?'red2':''?>"><?=languageType($value)?></font></a> </li>
		<?php 
	}
}
?>
            </ul>
      </li>
      <!-- END TODO DROPDOWN -->
      <li class="devider">&nbsp;</li>
      <?php }?>
       
     
      <!-- BEGIN USER LOGIN DROPDOWN -->
      <li class="dropdown user">
      <a href="/xingao/manage/form.php?my=1"  class="dropdown-toggle" data-hover="dropdown" data-close-others="true" title="用户ID：<?=$Xuserid?> &#13;用户组：<?=$manage_per[$Xgroupid]['groupname'];?>
	<?php 
    if($WHPerShow){echo '&#13;可管理：'.$WHPerShow.'';}
    ?>
      "> <img  src="/images/manage_tx.jpg" width="28" height="28"/> <span class="username" ><?=$Xtruename.'('.$Xusername.')'?></span> <i class="icon-angle-down"></i> </a>
      <ul class="dropdown-menu" style="text-align: center;">
		<?php if(permissions('notice','','manage',1) ){?>
        <li><a href="/xingao/notice/list.php" ><i class="icon-paste"></i> 内部公告</a></li>
        <?php }?>
        <li><a href="/xingao/log/list.php?my=1" ><i class="icon-list-alt"></i> 登录日志</a> </li>
        <li><a href="/xingao/login_save.php?lx=logout" onclick="return confirm('确认要退出吗?');"><i class="icon-lock"></i> 退出登录</a> </li>
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
  <div class="page-sidebar"> <!--手机或浏览器放大200%时左菜单不见问题修改,原:<div class="page-sidebar navbar-collapse collapse">  -->
    <!-- BEGIN SIDEBAR MENU -->
    <?php
	if(!$_SESSION['cache_manage']||$_SESSION['cache_manage_time']<=strtotime('-3 minutes'))//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds (数据要统计,更新时间不要太长)
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
      <li class="start"> <a href="/xingao/main.php"> <i class="icon-home"></i> <span class="title">后台主页</span> </a> </li>
      <li class="active"> <a href="javascript:;"> <i class="icon-flag"></i> <span class="title">常用</span> <span class="arrow open"></span> </a>
        <ul class="sub-menu">
          
          
		<?php if(permissions('baoguo_ad','','manage',1) ){?>
             <li><a href="/xingao/iframe.php?typ=Scan_incoming" ><?=$theme_manage_ico?'<i class=" icon-download-alt"></i>':''?> 扫描入库</a></li>
             
			 <?php if($bg_shelves){?>
             <li><a href="/xingao/iframe.php?typ=Scan_shelves" ><?=$theme_manage_ico?'<i class="icon-tags"></i>':''?> 扫描上架</a></li>
             <?php }?>
        <?php }?>

          <!---->
          <?php if($off_baoguo){?>
          <?php if(permissions('baoguo_ed,baoguo_se,baoguo_ad,baoguo_ot','','manage',1)){?>
          <li <?=$nav_act=Act('/baoguo/')?'class="open"':''?>><a href="javascript:;"><i class="icon-dropbox"></i> 包裹<span class="arrow <?=$nav_act?'open':''?>"></span></a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
              
			  <?php if(permissions('baoguo_ed,baoguo_se','','manage',1) ){?>
				  <li><a href="/xingao/baoguo/list.php?status=<?=$CN_zhi='all'?>" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>包裹管理</a></li>
              <?php }?>
			  
	         <li style="height:10px;"></li>
		  
			  
              <?php if(permissions('baoguo_ad','','manage',1) ){?>
              <li><a href="/xingao/iframe.php?typ=baoguo_kuaisu" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>手工入库</a></li>

              <li><a href="/xingao/iframe.php?typ=baoguo_excel_import" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>导入入库</a></li>
              <?php }?>
              
	         <li style="height:10px;"></li>
			  
              <?php if(permissions('baoguo_ed','','manage',1) ){?>
              <li><a href="/xingao/baoguo/scan.php" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>搜索</a></li>
              <?php }?>

              <?php if(permissions('baoguo_ot','','manage',1) ){?>
              <li><a href="/xingao/baoguo/print.php" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>打印</a></li>
              <?php }?>
            </ul>
          </li>
          <?php }?>
          <?php }?>
          <!---->
          <?php if(permissions('yundan_ed,yundan_se,yundan_fe,yundan_ta,yundan_st,yundan_ot,yundan_pr,yundan_im,yundan_ex','','manage',1)){?>
          <li <?=$nav_act=Act('/yundan/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-plane"></i> 运单 <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
			
              <?php if(permissions('yundan_ed,yundan_se','','manage',1) ){?>
				  <li><a href="/xingao/yundan/list.php?status=all" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>运单管理</a></li>
				  <?php //require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/nav_num.php');?> 
              <?php }?>
              
	         <li style="height:10px;"></li>

              <?php if(permissions('yundan_ad','','manage',1) ){?>
              <li><a href="/xingao/yundan/form.php" target="_blank"><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>增加运单</a></li>
              <li><a href="/xingao/iframe.php?typ=yundan_excel_import" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>运单导入</a></li>
              <?php }?>
              
	         <li style="height:10px;"></li>

			  
              <?php if(permissions('yundan_im','','manage',1) ){?>
 			  <li><a href="/xingao/iframe.php?typ=yundan_excel_import_other" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>快递/其他导入</a></li>
             <?php }?>
			  
              <li style="height:10px;"></li>
              
              <?php if(permissions('yundan_sc','','manage',1) ){?>
			  <li><a href="/xingao/iframe.php?typ=yundan_scanWeighing" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>扫描称重</a></li>
              
			  <?php if($ON_gd_japan){?>
              <li><a href="/xingao/iframe.php?typ=yundan_scanGoodsdata" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>扫描物品</a></li>
              <?php }?>

              <li><a href="/xingao/iframe.php?typ=yundan_scanLotno" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>扫描预出库</a></li>
              
              <li><a href="/xingao/iframe.php?typ=yundan_scan" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>扫描修改/出库</a></li>
             
              <?php }?>
              
              <?php if(permissions('yundan_st','','manage',1) ){?>
              <li><a href="/xingao/yundan/batch.php" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>批量修改/出库</a></li>
              <?php }?>
              
              
              <li style="height:10px;"></li>
              
              <?php if(permissions('yundan_pr','','manage',1) ){?>
              <li><a href="/xingao/yundan/print.php" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>打印</a></li>
              <?php }?>
              <?php if(permissions('yundan_ex','','manage',1) ){?>
              <li><a href="/xingao/yundan/excel_export.php" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>导出</a></li>
              <li style="display:none"><a href="/xingao/yundan/excel_export_sm.php" ><!--隐藏:为展开栏目--></a></li>
             <?php }?>
   
              <li style="height:10px;"></li>

           
              <?php if(permissions('yundan_se','','manage',1) ){?>
              <li><a href="/xingao/yundan/view.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>查看数据</a></li>
              <?php }?>
              
              <?php if(permissions('yundan_ot','','manage',1) ){?>
              <li><a href="/xingao/hscode/list.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>HS/HG/批号/快递</a></li>
              <?php }?>
            </ul>
          </li>
          <?php }?>
          
          <!---->
          <?php 
		  if(permissions('daigou_ad,daigou_ed,daigou_se,daigou_cg,daigou_th,daigou_zg,daigou_hh,daigou_ch,daigou_ck,daigou_ex,daigou_ot','','manage',1) &&$ON_daigou){
		  ?>
          <li <?=$nav_act=Act('/daigou/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-retweet"></i> 代购 <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
            
              <li><a href="/xingao/daigou/list.php?so=1&status=all"><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>代购管理</a></li>
              
              <?php if(permissions('daigou_ed','','manage',1) ){?>
              <li><a href="/xingao/daigou/batch.php" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>批量修改</a></li>
              <?php }?>
              
              <?php if(permissions('daigou_ex','','manage',1) ){?>
              <li><a href="/xingao/daigou/excel_export.php" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>导出</a></li>
             <?php }?>
             
             <?php if(permissions('daigou_ad','','manage',1) ){?>
             <li><a href="/xingao/daigou/user.php" target="_blank"><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>添加代购单</a></li>
             <?php }?>

            </ul>
          </li>
          <?php }?>
          <!---->

         <?php if( permissions('mall_order','','manage',1)  && $off_mall){?>
          <li <?=$nav_act=Act('/mall_order/,/mall/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-shopping-cart"></i> 商城 <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
            
              <?php if( permissions('mall','','manage',1)){?>
			  <li><a href="/xingao/mall/list.php?so=1&classid=0" class="tooltips" data-container="body" data-placement="top" data-original-title="查看栏目分类下商品请从“商品|信息|栏目”进入"><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>所有商品</a></li>
              <?php }?>
				
              <li><a href="/xingao/mall_order/list.php?pay=<?=$CN_zhi=1?>" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?><?=CountNum($CN_table='mall_order',$CN_field='pay',$CN_zhi,$CN_where=$Xwh." and status=0",$CN_userid='',$CN_color='warning');?>
              订单</a></li>

			  <?php if(permissions('baoguo_ed,baoguo_se','','manage',1) ){?>
				  <!--<li><a href="/xingao/iframe.php?typ=baoguo_ruku" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>扫描入库</a></li>-->
				  <li><a href="/xingao/baoguo/list.php?so=1&status=kuwai&addSource=3" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>未入库包裹<?=CountNum($CN_table='baoguo','','',$CN_where=$Xwh." and status in (0,1) and ware=0 and addSource=3",$CN_userid='',$CN_color='warning');?></a></li>
                  
				  <li><a href="/xingao/baoguo/list.php?so=1&status=ruku&addSource=3" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>已入库包裹<?=CountNum($CN_table='baoguo','','',$CN_where=$Xwh." and status in (2,3) and ware=0 and addSource=3",$CN_userid='',$CN_color='default');?></a></li>
              <?php }?>
              
              <!--<li><a href="/xingao/mall_order/list.php?pay=<?=$CN_zhi=0?>" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>购物车</a></li>-->
              
            </ul>
          </li>
          <?php }?>
          <!---->
          <?php if(permissions('qujian','','manage',1)){?>
          <li <?=$nav_act=Act('/qujian/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-upload-alt"></i> 送件 <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
            
              <li><a href="/xingao/qujian/list.php?so=1&status=<?=$CN_zhi=0?>" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>              <?=$qjnum_status_0=CountNum($CN_table='qujian',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='warning');?>
              待处理</a></li>
              
              <li><a href="/xingao/qujian/list.php?so=1&status=<?=$CN_zhi=1?>" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>
			  <?=$qjnum_status_1=CountNum($CN_table='qujian',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='success');?>处理中</a></li>
              <li><a href="/xingao/qujian/list.php?so=1&status=<?=$CN_zhi=3?>" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>
			  拒绝</a></li>
              <li><a href="/xingao/qujian/list.php?so=1&status=<?=$CN_zhi=2?>" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>
			  完成</a></li>
              
            </ul>
          </li>
          <?php }?>
          <!---->
          <?php if(permissions('lipei','','manage',1) ){?>
          <li <?=$nav_act=Act('/lipei/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-money"></i> 理赔 <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>

    <li><a href="/xingao/lipei/list.php?so=1&status=<?=$CN_zhi=0?>" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>
	<?=$lpnum_status_0=CountNum($CN_table='lipei',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='warning');?>待处理</a></li>
   
    <li><a href="/xingao/lipei/list.php?so=1&status=<?=$CN_zhi=1?>" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>
	<?=$lpnum_status_1=CountNum($CN_table='lipei',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='success');?>处理中</a></li>
    
    <li><a href="/xingao/lipei/list.php?so=1&status=<?=$CN_zhi=3?>" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>
	拒绝</a></li>
   
    <li><a href="/xingao/lipei/list.php?so=1&status=<?=$CN_zhi=2?>" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>
	完成</a></li>

            </ul>
          </li>
          <?php }?>
          <!---->
          <?php if(permissions('tixian','','manage',1) ){?>
          <li <?=$nav_act=Act('/tixian/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-credit-card"></i> 代付 <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
              <li><a href="/xingao/tixian/list.php?so=1&status=<?=$CN_zhi=1?>" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>
			  <?=$txnum_status_0=CountNum($CN_table='tixian',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='warning');?>待处理</a></li>
              
              <li><a href="/xingao/tixian/list.php?so=1&status=<?=$CN_zhi=3?>" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>
			  拒绝</a></li>
              <li><a href="/xingao/tixian/list.php?so=1&status=<?=$CN_zhi=2?>" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>
			  完成</a></li>
            </ul>
          </li>
          <?php }?>
          <!---->
          <?php if(permissions('member_le','','manage',1) ){?>
          <li <?=$nav_act=Act('/msg/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-comments-alt"></i> 留言 <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
            
              <li><a href="/xingao/msg/list.php?so=1&status=<?=$CN_zhi=11?>" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>  
			  <?=$msgnum_status_11=CountNum($CN_table='msg',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='warning');?>需回复</a></li>

              <li><a href="/xingao/msg/list.php?so=1&status=<?=$CN_zhi=12?>" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>  
			  <?=$msgnum_status_12=CountNum($CN_table='msg',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='important');?>急需回复</a></li>
              
              
              <li><a href="/xingao/msg/list.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?> 
			  全部</a></li>
            </ul>
          </li>
          <?php }?>
          <!---->
          <?php if(permissions('shaidan','','manage',1) &&$off_shaidan){?>
          <li <?=$nav_act=Act('/shaidan/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-star"></i> 晒单 <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
              <li><a href="/xingao/shaidan/list.php?so=1&checked=<?=$CN_zhi=0?>" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>
			  <?=$sdnum_status_0=CountNum($CN_table='shaidan',$CN_field='checked',$CN_zhi,$CN_where='',$CN_userid='',$CN_color='warning');?>未审核</a></li>
              
              <li><a href="/xingao/shaidan/list.php?so=1&checked=<?=$CN_zhi=1?>" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>
			  已审核</a></li>
            </ul>
          </li>
          <?php }?>
          <!---->
          <?php if(permissions('pinglun','','manage',1) ){?>
          <li <?=$nav_act=Act('/comments/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-thumbs-up"></i> 评论 <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
              <li><a href="/xingao/comments/list.php?so=1&checked=<?=$CN_zhi=0?>" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>
			  <?=$cmnum_status_0=CountNum($CN_table='comments',$CN_field='checked',$CN_zhi,$CN_where="and reply_userid='0'",$CN_userid='',$CN_color='warning');?>未审核</a></li>
              <li><a href="/xingao/comments/list.php?so=1&checked=<?=$CN_zhi=1?>" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>
			  已审核</a></li>
            </ul>
          </li>
          <?php }?>
          <!---->
          
          <?php if(permissions('yundan_ot,notice,classify','','manage',1) ){?>
          <li <?=$nav_act=Act('/hscode/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-signout"></i> 其他 <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
            <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
              <?php if(permissions('notice','','manage',1) ){?>
              <li><a href="/xingao/notice/list.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>内部公告</a></li>
              <?php }?>
              
              <?php if(permissions('settlement_se,settlement_ed','','manage',1) ){?>
              <li><a href="/xingao/settlement/list_yundan.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>月结管理</a></li>
              <?php }?>
              
              
			  <?php if(permissions('classify','','manage',1) ){?>
              <li><a href="/xingao/classify/list.php?so=1&classtype=3" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>航班/船运/托盘</a></li>
              <?php }?>

          
		  <?php if($ON_gd_japan&&permissions('goodsdata','','manage',1) ){?>
          <li><a href="/xingao/gd_japan/list.php" ><?=$theme_manage_ico?'<i class="icon-stackexchange"></i>':''?>日本清关资料</a></li>
          <li style="display:none"><a href="/xingao/iframe.php?typ=gd_japan_excel_import" ><!--隐藏:为展开栏目--></a></li>
          <?php }?>

		  <?php if($ON_gd_mosuda&&permissions('goodsdata','','manage',1) ){?>
          <li><a href="/xingao/gd_mosuda/list.php" ><?=$theme_manage_ico?'<i class="icon-stackexchange"></i>':''?>跨境翼清关资料</a></li>
          <li style="display:none"><a href="/xingao/iframe.php?typ=gd_mosuda_excel_import" ><!--隐藏:为展开栏目--></a></li>
          <?php }?>

            </ul>
          </li>
          <?php }?>
        </ul>
      </li>
    
      <!---->
      <?php if(permissions('mall,qita','','manage',1) ){
			 $lm=1;?>
      <li <?=$nav_act=Act('/class/,/link/,/update/,/mall/,/article/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-columns"></i> <span class="title">商品|信息|栏目</span> <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
        <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
          <?php if($lm){?>
          <li><a href="/xingao/class/list.php"  class="tooltips" data-container="body" data-placement="top" data-original-title="商品/信息/栏目/单页" ><i class="icon-th-list"></i> 栏目列表</a> </li>
          <li><a href="/xingao/article/list.php" ><i class="icon-file-text"></i> 全部信息</a> </li>
          <li><a href="/xingao/link/list.php" ><i class="icon-link"></i> 友情管理</a> </li>
          <li><a href="/xingao/update/list.php" ><i class="icon-refresh"></i> 刷新数据</a> </li>
          <?php }?>
        </ul>
      </li>
      <?php }?>
      <!---->
      <?php if(permissions('member_ed,member_se,member_re,member_de,member_in,member_le','','manage',1)){?>
      <li <?=$nav_act=Act('/member/,/member_group/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-group"></i> <span class="title">会员</span> <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
        <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
          <?php if(permissions('member_ed,member_se','','manage',1) ){?>
          <li><a href="/xingao/member/list.php" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>会员管理</a></li>
          <?php }?>
 
         <li style="display:none"><a href="/xingao/iframe.php?typ=member_excel_import" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>批量导入</a></li>

        
          <?php if(permissions('manage_ma','','manage',1)){?>
          <li><a href="/xingao/member_group/list.php" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>会员组&amp;费用</a></li>
          <?php }?>
          
		  <?php if(permissions('coupons','','manage',1) ){?>
          <li><a href="/xingao/coupons/list.php" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>优惠券/折扣券</a></li>
          <?php }?>
 	     <li style="height:10px;"></li>
         
          <?php if(permissions('member_le','','manage',1)){?>
          <li><a href="/xingao/member/send.php" target="_blank" style="width:50%; float:left;"><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>发信</a></li>
          <li><a href="/xingao/msg/list.php?my=1" style="width:50%;float:left;"><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>管理</a></li>
          <?php }?>
          
          <li>&nbsp;</li>
          
          <?php if(permissions('member_re','','manage',1)){?>
          	<?php if($ON_bankAccount){?>
            <li><a href="/xingao/transfer/list.php"><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>转账充值管理</a></li>
			<?php }?>
            
          <li>
          <a href="/xingao/member/money_cz.php" target="_blank" style="width:50%; float:left;"><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>充值</a>
          <a href="/xingao/member/money_czbak.php" style="width:50%;float:left;"><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>记录</a>
          </li>
          <?php }?>

          <?php if(permissions('member_de','','manage',1)){?>
          <li>
          <a href="/xingao/member/money_kf.php" target="_blank" style="width:50%; float:left;"><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>扣费</a>
          <a href="/xingao/member/money_kfbak.php" style="width:50%;float:left;"><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>记录</a>
          </li>
         <?php }?>
         
         <?php if(permissions('member_de,member_re','','manage',1)){?>
            <li style="height:70px;"></li>
        <li><a href="/xingao/member/money_bak.php" ><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>资金流水账</a></li>
         <?php }?>
         
         <li>&nbsp;</li>
          
          
          <?php if(permissions('member_in','','manage',1)){?>
          <li>
          <a href="/xingao/member/integral_cz.php" target="_blank" style="width:50%;float:left;"><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>加分</a>
          <a href="/xingao/member/integral_czbak.php" style="width:50%;float:left;"><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>记录</a>
          </li>
          
          <li>
          <a href="/xingao/member/integral_kf.php" target="_blank" style="width:50%;float:left;"><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>扣分</a>
          <a href="/xingao/member/integral_kfbak.php" style="width:50%;float:left;"><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>记录</a>
          </li>
          
           <li style="height:70px;"></li>
          
          <li><a href="/xingao/member/integral_bak.php"><?=$theme_manage_ico?'<i class="icon-chevron-right"></i>':''?>积分流水账</a></li>
          <?php }?>
          
          
          
          
          
         
        </ul>
      </li>
      <?php }?>
      
      <!---->
      <?php if(permissions('manage_ma','','manage',1)){?>
      <li <?=$nav_act=Act('/manage/,/manage_group/,/log/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-user-md"></i> <span class="title">后台用户</span> <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
        <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
          <li><a href="/xingao/manage/list.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>用户管理</a></li>
          <li><a href="/xingao/manage_group/list.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>用户组</a></li>
          <li><a href="/xingao/log/list.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>登陆日志</a></li>
        </ul>
      </li>
      <?php }?>
      
      <!---->
      <?php if( permissions('count_yd,count_bg,count_hy_sl,count_hy_hx,count_ot','','manage',1) ){?>
      <li <?=$nav_act=Act('/count/')?'class="open"':''?>> <a href="javascript:;"> <i class="icon-bar-chart"></i> <span class="title">数据统计</span> <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
        <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
		
          <?php if($off_baoguo){?>
		  <?php if(permissions('count_bg','','manage',1)){?>
          <li><a href="/xingao/count/baoguo.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>包裹</a></li>
          <?php }?>
          <?php }?>
		  
          <?php if(permissions('count_yd','','manage',1)){?>
          <li><a href="/xingao/count/yundan.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>运单</a></li>
          <?php }?>
		  
          <?php if(permissions('count_ot','','manage',1)){?>
         	  <li><a href="/xingao/count/mall_order.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>订单</a></li>
			  <?php  if($ON_daigou){ ?>
              <li><a href="/xingao/count/daigou.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>代购</a></li>
              <?php }?>
		  <?php }?>
		  
          <?php if(permissions('count_hy_sl','','manage',1)){?>
          <li><a href="/xingao/count/member_sl.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>会员数量</a></li>
          <?php }?>
          
 	     <li style="height:10px;"></li>
		  
          <?php if(permissions('count_hy_hx','','manage',1)){?>
          <li><a href="/xingao/count/member_hx.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>财务核销</a></li>
          <li><a href="/xingao/count/member_tj.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>财务统计-概况</a></li>
          <li><a href="/xingao/count/MemberDS.php" ><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>财务统计-具体</a></li>
          <?php }?>
          
 	     <li style="height:10px;"></li>
		  
          <?php if(permissions('count_ot','','manage',1)&&cadd($traffic)){?>
		  <li><a href="<?=cadd($traffic)?>" target="_blank"><?=$theme_manage_ico?'<i class="icon-caret-right"></i>':''?>流量统计</a></li>
		  <?php }?>
        </ul>
      </li>
      <?php }?>
      <!---->
      <?php if(permissions('manage_sy,manage_ma,manage_db,classify,goodsdata','','manage',1)){?>
      <li <?=$nav_act=Act('/config/,/payapi/,/phpMyAdmin.php,/warehouse/,/classify/,/gd_japan/')?'class="open"':''?>><a href="javascript:;"> <i class="icon-tasks"></i> <span class="title">系统管理</span> <span class="arrow <?=$nav_act?'open':''?>"></span> </a>
        <ul class="sub-menu" <?=$nav_act?'style="display: block;"':''?>>
        
          <?php if(permissions('manage_sy','','manage',1)){?>
          <li><a href="/xingao/config/form.php" > <i class="icon-cogs"></i> 系统设置 </a></li>
          <li><a href="/xingao/warehouse/list.php" ><i class="icon-home"></i>仓库&amp;渠道</a></li>
          <li><a href="/xingao/payapi/list.php" ><i class="icon-random"></i>支付接口</a></li>
          <?php }?>
          
          <?php if(permissions('manage_ex','','manage',1)){?>
          <li><a href="/xingao/exchange/list.php" ><i class="icon-retweet"></i>汇率设置</a></li>
          <?php }?>
          
		  <?php if(permissions('classify','','manage',1) ){?>
          <li><a href="/xingao/classify/list.php" ><?=$theme_manage_ico?'<i class="icon-sitemap"></i>':''?>分类管理</a></li>
          <?php }?>

          <?php if(permissions('manage_db','','manage',1)){?>
          <li><a href="/xingao/phpMyAdmin.php" > <i class="icon-inbox"></i> 数据库管理工具 </a></li>
          <?php }?>
          
        </ul>
      </li>
      <?php }?>
      <!---->
    </ul>
    <?php
		$_SESSION['cache_manage']=ob_get_contents();//得到缓冲区的数据
		$_SESSION['cache_manage_time']=time();
		ob_end_clean();//结束缓存：清除并关闭缓冲区
	}
	echo $_SESSION['cache_manage'];
	?>
    <!-- END SIDEBAR MENU --> 
</div>
  <!-- END SIDEBAR --> 
  <?php $CN_table='';$CN_field='';$CN_zhi='';$CN_where='';$CN_userid='';$CN_color='';$type='';//清空,防止调用上面参数?>
  <!-- BEGIN PAGE -->
   <div class="page-content"> 
    

<script>
//高亮显示
var myNav = document.getElementById("act_nav").getElementsByTagName("a"); 
for(var i=0;i<myNav.length;i++) 
{ 
	var links = myNav[i].getAttribute("href"); //原链接按钮
	var myURL = document.location.href; //网址链接
	
	//先把非 ?headtitle= 的?后面的参数删除再判断，否则分类时有参数就不相同，就不能高亮
/*	if(links.indexOf("?")>=0&&links.indexOf("?headtitle=")<0){links = links.split("?")[0];}
	if(myURL.indexOf("?")>=0&&links.indexOf("?headtitle=")<0){myURL = myURL.split("?")[0];}
*/	
	//指定哪些是不能展开的，否则有错误
	if(myURL.indexOf(links)>0&&myURL.indexOf('main.php')<0) 
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
