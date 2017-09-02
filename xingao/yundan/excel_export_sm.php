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

$pervar='yundan_ex';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
//显示表单-----------------------------------------------------------------------------------------------------
$headtitle="报表导出";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=all" class="gray" target="_parent"><?=$LG['backList']?></a> > 
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
        </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
	<div class="tabbable tabbable-custom boxless">
	<ul class="nav nav-tabs">
		<li><a href="excel_export.php">批量导出</a></li>
		<li class="active"><a href="excel_export_sm.php">扫描导出</a></li>
	</ul>
    
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
            
            
              <!---->
              <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/excel_export_sm_save.php');?>
              <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/excel_export_sm_form.php');?>
              <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/excel_export_sm_list.php');?>
              <!---->
          </div>
          </div>
        </div>
        
        
      </div>
    </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
