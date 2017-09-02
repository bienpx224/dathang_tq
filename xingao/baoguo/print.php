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

if(!$off_baoguo){exit ("<script>alert('包裹系统已关闭！');goBack();</script>");}

$pervar='baoguo_ot';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//提交处理-----------------------------------------------------------------------------------------------------
//提交时显示打印页,并停止显示下面内容
$lx=par($_REQUEST['lx']);
if($lx=='tj'||$lx=='pr'){
	$callFrom='manage';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/print/index.php');exit;
}

//显示表单-----------------------------------------------------------------------------------------------------
$headtitle="打印包裹";
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
  
  <form action="?" method="post" class="form-horizontal form-bordered" name="xingao" target="_blank">
  <input type="hidden" name="lx" value="tj">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
			<div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>打印选项</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;">
                  <!--表单内容-->
                  
                <div class="form-group">
                  <label class="control-label col-md-2">打印模板</label>
                  <div class="col-md-10 has-error">
					<select class="form-control select2me input-medium" data-placeholder="打印模板" name="print_tem" required>
					<option></option>
					<?php baoguo_print('',1)?>
					</select>
                  </div>
                </div>
				
				<div class="form-group">
				<label class="control-label col-md-2">所在仓库</label>
				<div class="col-md-10">
				 <select name="warehouse" class="form-control input-medium select2me" data-placeholder="请选择" >
					 <?php warehouse('',1,1);?>
				 </select>
				 <font class="gray2">不选则不限</font>
				</div>
				</div>

                </div>
              </div>
          </div>
          </div>
        </div>        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">





      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> 打 印 </button>
				</div>
		
      </div>
    </div>
  </form>

</div>
<div class="xats">
	<strong>提示:</strong><br />
	&raquo; 建议操作流程:打印要操作的包裹 > 在仓库操作包裹 > 操作完后，使用“快速搜索包裹”功能扫描包裹并更新操作结果 (完成)<br />
</div>


<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>


