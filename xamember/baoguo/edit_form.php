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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['baoguo.edit_form_1'];//包裹编辑
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
if(!$off_baoguo||!$member_per[$Mgroupid]['ON_Mbaoguo']){exit ("<script>alert('{$LG['baoguo.add_form_2']}');goBack();</script>");}

//获取,处理
$lx=par($_GET['lx']);
$bgid=par($_GET['bgid']);

if($lx=='edit'){
	if(!$bgid){exit ("<script>alert('bgid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('baoguo','*',"bgid='{$bgid}' and status in (0,1) {$Mmy}");
}

if(!$rs['bgid']){
	exit ("<script>alert('{$LG['baoguo.edit_form_2']}');goBack();</script>");
}

if($rs['addSource']==3){
	exit ("<script>alert('{$LG['baoguo.edit_form_3']}');goBack();</script>");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("baoguo".$bgid);
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
    <form action="edit_save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="bgid" type="hidden" value="<?=$rs['bgid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
<!--表单内容-开始------------------------------------------------------------------------------------------------------>
	<div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['baoguo.edit_form_4'];//基本信息?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['baoguo.add_form_5'];//快递单号?></label>
                    <div class="col-md-10 has-error">
					  <input type="hidden" name="old_bgydh" value="<?=cadd($rs['bgydh'])?>">
                      <input type="text" class="form-control input-medium" name="bgydh" required value="<?=cadd($rs['bgydh'])?>" title="<?=$LG['baoguo.add_form_11'];//如果没有运单号可填写购物的订单号?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['baoguo.add_form_6'];//快递公司?></label>
                    <div class="col-md-10">
                     <select name="kuaidi" class="form-control input-medium select2me"  data-placeholder="<?=$LG['pleaseSelect'];//请选择?>">
					 <?php baoguo_kuaidi(cadd($rs['kuaidi']));?>
					 </select>
                    </div>
                  </div>
				  
                  <div class="form-group" <?=$warehouse_more?'':'style="display:none"'?>>
                    <label class="control-label col-md-2"><?=$LG['baoguo.add_form_7'];//寄至仓库?></label>
                    <div class="col-md-10 has-error">
                     <select name="warehouse" class="form-control input-medium select2me" required  data-placeholder="<?=$LG['pleaseSelect'];//请选择?>">
					 <?php warehouse(cadd($rs['warehouse']),1);?>
					 </select>
                    </div>
                  </div>
				  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['baoguo.add_form_8'];//发货点?></label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium" name="fahuodiqu" value="<?=cadd($rs['fahuodiqu'])?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['baoguo.add_form_9'];//购物网站?></label>
                    <div class="col-md-10">
                     <select name="wangzhan" id="wangzhan" class="form-control input-medium select2me" data-placeholder="<?=$LG['pleaseSelect'];//请选择?>" onChange="wangzhan_other();">
					 <?php wangzhan(cadd($rs['wangzhan']),1);?>
					 </select>

	<div id="msg_wangzhan_other"></div>
	<script language="javascript">
	function wangzhan_other()
	{
		var wangzhan=document.getElementById("wangzhan").value;
		if(wangzhan=="other")
		{
			document.getElementById("msg_wangzhan_other").innerHTML = '<br><input name="wangzhan_other" class="form-control input-medium" type="text" value="<?=cadd($rs['wangzhan_other'])?>"/>';
		}else
		{
			document.getElementById("msg_wangzhan_other").innerHTML = '';
		}
	}
	</script> 
					                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['baoguo.fahuotime'];//发货/购物日期?></label>
                    <div class="col-md-10">
                      <input name="fahuotime" type="text" class=" form-control input-small form-control-inline date-picker"  data-date-format="yyyy-mm-dd" value="<?=DateYmd($rs['fahuotime'],2)?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['note']?></label>
                    <div class="col-md-10">
                      <textarea name="content" class="form-control" placeholder="<?=$LG['baoguo.add_form_14'];//请在此写入您的要求或者任何有利于区分货物、查询货物的信息，比如包装的重量长宽高等?>"><?=cadd($rs['content'])?></textarea>
                    </div>
                  </div>

				  
                </div>
              </div>
			  		
			
              <div class="portlet">
			  <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['baoguo.edit_form_6'];//物品信息?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 

				<?php wupin_from_general('baoguo',$rs['bgid']);?>				  
        
                </div>
              </div>
 <!--表单内容-结束------------------------------------------------------------------------------------------------------>

            </div>
          </div>
        </div>        
        <!--提交按钮固定--> 
		<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
 
        <div align="center" class="fixed_btn" id="Autohidden">
        
        
<button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
          <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
        </div>
      </div>
    </div>
  </form>

	<div class="xats"><?=$LG['pptInfo']?><br />
		&raquo; <?=$LG['baoguo.edit_form_8']?><br />
		&raquo; <?=$LG['baoguo.edit_form_9']?><br />
	</div>
</div>


<script src="/js/AntongJQ.js" type="text/javascript"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/js/baoguoJS.php');?>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
<script language="javascript">
//单独分开,要放在foot.php后面
$(function(){       
     wangzhan_other();//包裹表单,选择其他购物网站时
});
//单独分开,要放在foot.php后面
$(function(){       
   CalcDeclareValue();//包裹表单,修改时自动计算总物品价值
});
</script>
