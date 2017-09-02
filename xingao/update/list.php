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
$pervar='qita';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="更新缓存/生成前台页面";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
      <h3 class="page-title">
       <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
      </h3>
      <ul class="page-breadcrumb breadcrumb">
        <button type="button" class="btn btn-info input-large" onClick="window.open('save.php?lx=jiben&headtitle=更新缓存和首页');"><i class="icon-refresh"></i> 更新缓存|生成首页 </button>
        <br> <br>

        <button type="button" class="btn btn-info input-large"  onClick="window.open('save.php?lx=list&headtitle=更新列表页');" ><i class="icon-refresh"></i> 生成首页|列表页|单页 </button>
        <br> <br>
        <button type="button" class="btn btn-default input-large popovers" data-trigger="hover" data-placement="top"  data-content="会同时生成首页<br>一般情况不用更新此项" onClick="window.open('save.php?lx=content&headtitle=更新信息内容页');" ><i class="icon-refresh"></i> 生成信息内容页 </button>
      

<?php if($off_shaidan){?>
        <br> <br>
      <button type="button" class="btn btn-default input-large popovers" data-trigger="hover" data-placement="top"  data-content="会同时生成首页<br>一般情况不用更新此项" onClick="window.open('save.php?lx=content_shaidan&headtitle=更新晒单内容页');"><i class="icon-refresh"></i> 生成晒单内容页 </button>
<?php }?>
      </ul>
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
    <div class="portlet tabbable">
      <div class="portlet-body" style="padding:10px;">
      <!--搜索-->
     <span class="help-block">
	 
     <font class="red">&raquo; 后面3项更新极占服务器资源，请等待更新完一项之后再更新另一项，否则可能会过于占用资源而卡住!<br></font>
     
	 &raquo; 如果已更新还是显示旧内容，请在该页面按CTRL+F5强行刷新浏览器<br>
	 </span> 
      </div>
      <!--表格内容结束--> 
      
    </div>
  
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
