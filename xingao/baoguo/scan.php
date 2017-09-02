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

$pervar='baoguo_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

$lx=par($_POST['lx']);
$bgydh=par($_POST['bgydh']);
if($lx=='edit'){
	$alert_color='info';
	$rsid='';

	$id='bgid';
	$table='baoguo';
	$field='bgydh';
	$smlx=0;//1只精确搜索;0全部搜索
	$where_search="";//有其他条件时,以空格 and 开头,如: and userid='{$userid}'
	$rsid=searchNumber($id,$table,$field,$bgydh,$smlx,$where_search);//搜索处理
	
	if($rsid)
	{
		echo '<script language=javascript>';
		echo 'window.open("/xingao/baoguo/form.php?lx=edit&bgid='.$rsid.'#op")';
		echo '</script>';
		
		music('yes');//播放提示声音
		
	}else{
		$ts='<font class="red">未找到包裹</font>';
		$alert_color='danger';
		
		music('no');//播放提示声音

	}
}



//显示表单-----------------------------------------------------------------------------------------------------
$headtitle="快速搜索包裹";
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
<?php 
if($ts)
{
	echo '<br><div class="alert alert-'.$alert_color.'" style="font-size: 18px; line-height:23px; padding-left:20px;">'.$ts.'</div>';
}
?>
  

<form action="?" method="post" class="form-horizontal form-bordered" name="xingao">
  <input type="hidden" name="lx" value="edit">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
			<div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>搜索包裹</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;">
                  <!--表单内容-->
                  
                <div class="form-group">
                  <label class="control-label col-md-2">包裹快递单号</label>
                  <div class="col-md-10 has-error">
					<input type="text" class="form-control input-medium" name="bgydh" placeholder="光标在此位置时可直接扫描">
					
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





      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> 搜 索 </button>
				</div>
      </div>
    </div>
  </form>
</div>

<div class="xats">
	<strong>提示:</strong><br />
	&raquo; 支持外置设备，如扫描枪<br />
	&raquo; 如果有相同的单号，只会显示一个 (最新添加的包裹)<br />
	<font class="red">&raquo; 如果无弹出页面，请把浏览器的屏蔽广告功能设置为不屏蔽本站广告<br /></font>
</div>


<script language="javascript">
//默认光标在某个INPUT停留,可不用放在foot.php后面,要确保有那个ID,否则会停止执行后面的其他JS
$(function(){       
	document.getElementsByName("bgydh")[0].focus(); //停留
	document.getElementsByName("bgydh")[0].select(); //全选
});

</script>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
