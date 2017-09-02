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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="扫描入库";
$alonepage=2;//1单页形式;2框架形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

?>
<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
        <h3 class="page-title"> 
        	<a href="?" class="gray"> 重新扫描</a>
        </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 

  <!-- BEGIN PAGE CONTENT-->

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/ScanInStorage/save.php');
//输出各种处理的提示
XAalert($ts,$alert_color,'style="font-size: 18px; line-height:23px; padding-left:20px;"');
?>









<form action="?" method="post" class="form-horizontal form-bordered" name="xingao">
<input name="typ" type="hidden" value="smt">
<div><!-- class="tabbable tabbable-custom boxless"-->
<div class="tab-content">
<div class="tab-pane active" id="tab_1">
<div class="form">
<div class="form-body"> 
<!--表单内容-开始------------------------------------------------------------------------------------------------------> 

<div class="portlet">
    <div class="portlet-body form" style="display: block;">
     
        <div class="form-group">
            <label class="control-label col-md-2">重量</label>
            <div class="col-md-10  <?=$baoguo_req_weight?'has-error':''?>">
            <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="代购单时请称单件重量"  name="weight" <?=$baoguo_req_weight?'required':''?>  onClick="select();" onFocus="select();" onKeyUp="document.getElementsByName('bgydh')[0].value='';"><?=$XAwt?>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-md-2">单号</label>
            <div class="col-md-10 has-error">
                <input type="text" class="form-control input-medium" name="bgydh" required value="<?=$bgydh?>"  onClick="select();" onFocus="select();">
                <br>
                <br>
                
             <?php if($off_baoguo){?>
                <input name="smbg" type="checkbox" value="1"  <?php if($_GET['first']||$_SESSION['smbg']||$_GET['smbg']){echo "checked";}?>/>
                搜索包裹：
                <div class="radio-list gray2" style="padding-left:25px;">
                    <input name="smbg_op1" type="checkbox" value="1"  <?php if($_GET['first']||$_SESSION['smbg_op1']||$_GET['smbg']){echo "checked";}?>/>包裹、代购、运单 都找不到时手工添加包裹</div>
                <br>
              <?php }?>  
                
                
             <?php if($ON_daigou){?>
                <input name="smdg" type="checkbox" value="1"  <?php if($_GET['first']||$_SESSION['smdg']||$_GET['smdg']){echo "checked";}?>/>
                搜索代购单 
                 <a href="javascript:void(0)" class=" tooltips" data-container="body" data-placement="top" data-original-title="用代购单的“入库码”和“单号”搜索 (不支持商品单号搜索)"> <i class="icon-info-sign"></i> </a>
                <br>
             <?php }?>  




              <input name="smyd" type="checkbox" value="1"  <?php if($_GET['first']||$_SESSION['smyd']||$_GET['smyd']){echo "checked";}?>/>
              搜索运单：
               <a href="javascript:void(0)" class=" tooltips" data-container="body" data-placement="top" data-original-title="用运单的“运单号”和“寄库快递单号”搜索"> <i class="icon-info-sign"></i> </a> 
               
              <div class="radio-list gray2" style="padding-left:25px;">
                      <label class="radio-inline">
                      <input name="smyd_op1" type="radio" value="3"  <?php if($_SESSION['smyd_op1']==3){echo "checked";}?>/>
                      更新为已入库,状态为【<?=$status_0?>】 
                      </label>
                      
                      <label class="radio-inline">
                      <input name="smyd_op1" type="radio" value="1"  <?php if($_SESSION['smyd_op1']==1){echo "checked";}?>/>
                      运单资料 
                      </label>
                  
                      <label class="radio-inline">
                      <input name="smyd_op1" type="radio" value="2"  <?php if($_SESSION['smyd_op1']==2||!$_SESSION['smyd_op1']){echo "checked";}?>/>
                      称重计费 
                      </label>
              </div>
                
              
            </div>
        </div>
        
        
    </div>
</div>
<!--表单内容-结束------------------------------------------------------------------------------------------------------> 

</div>
</div>
</div>
<div align="center">
<button type="submit" class="btn btn-primary input-small" id="AutoSmt"> <i class="icon-ok"></i> <?=$LG['submit']?> </button>
</div>
</div>
</div>
</form>
<div class="xats">
<strong>提示:</strong><br />
    &raquo; 操作流程：包裹放上电子秤(电子秤可设置自动Tab切换) → 扫描单号条码(扫描枪可设置自动提交) → 提交<br />
    &raquo; 包裹系统：会员预报时可能有选错仓库，因此本页入库不分仓库权限（任何仓库的包裹都可入库，在入库时请选择真实所在仓库）<br />
    <font class="red">&raquo; 如果无弹出页面，表示已被浏览器屏蔽，请把浏览器的广告屏蔽功能设置为不屏蔽本站广告<br /></font>
</div>

<script>
	function ScanCheck(){
		$('[name="weight"]')[0].focus(); //停留
		$('[name="weight"]')[0].select(); //全选
	}
	$(function(){   ScanCheck(); });

	//自动点击提交扫描
	<?php if($AutoSmt){?>$(function(){   $('#AutoSmt').trigger('click');  });<?php }?>
</script>

</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');?>
