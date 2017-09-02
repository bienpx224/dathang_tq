<?php 
$bgydh=cadd($_REQUEST['bgydh']);

//<!--2017.06.17:删除dgid-->
?>
<div class="tabbable tabbable-custom boxless">
	<ul class="nav nav-tabs">
		<li class="active"><a>包裹资料</a></li>
	</ul>
	<div class="tab-content" style="padding:10px;"> 

<form action="?" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
<input name="lx" type="hidden" value="add" />
<input name="sm" type="hidden" value="<?=$sm?>" />
<input name="addid" type="hidden" value="<?=$addid?>" />
<!--表单内容-开始------------------------------------------------------------------------------------------------------> 
 <div class="portlet">
	<div class="portlet-body form" style="display: block;">
	<div class="form-group">
		<label class="control-label col-md-2">重量</label>
		<div class="col-md-2">
		<input type="text" class="form-control input-small   <?=$baoguo_req_weight?'input_txt_red':''?>"  name="weight" value="<?=spr($_REQUEST['weight'],2,0)?>" <?=$baoguo_req_weight?'required':''?>><?=$XAwt?>
 		</div>
        
        <label class="control-label col-md-2">单号</label>
        <div class="col-md-2">
 		<input type="text" class="form-control input-medium input_txt_red" name="bgydh"  value="<?=$bgydh?>" required >
        <font class="red" id="cw_msg"></font>
		</div>
        
                  
                    <label class="control-label col-md-2">快递公司</label>
                    <div class="col-md-2">
                     <select name="kuaidi" class="form-control input-medium select2me"  data-placeholder="请选择">
					 <?php baoguo_kuaidi(cadd($rs['kuaidi']));?>
					 </select>
                    </div>

	</div>
		
	<div class="form-group">
		<label class="control-label col-md-2">会员</label>
		<div class="col-md-10 has-error">
        <input type="text" class="form-control input-small" name="userid" autocomplete="off"  value="<?=cadd($_POST['userid'])?>"  title="会员ID"  placeholder="会员ID" onKeyUp="getUsernameId('userid');getBaoguoDate();">

		 <input type="text" class="form-control input-small" name="useric" value="<?=cadd($_POST['useric'])?>" title="会员入库码"  placeholder="会员入库码" onKeyUp="getUsernameId('useric');getBaoguoDate();">

		 
		 
		 <input type="text" class="form-control input-medium" name="username" autocomplete="off" value="<?=cadd($_POST['username'])?>" title="会员名"  placeholder="会员名" onKeyUp="getUsernameId('username');getBaoguoDate();">

			<br><span class="gray_prompt2">待领包裹时,请全部留空 (填写其中一项，自动获取其他)</span>	
		</div>
	  </div>
      
  <div class="form-group">
	<label class="control-label col-md-2">认领资料</label>
	<div class="col-md-10">
	  <textarea name="unclaimedContent" class="form-control" placeholder="对于待领包裹,可填写认领时需验证的资料,如包裹姓名"><?=cadd($_POST['unclaimedContent'])?></textarea>
	</div>
  </div>
		
				  
 <div class="form-group">
	<label class="control-label col-md-2"><?=$warehouse_more?'仓库':'仓位'?></label>
	<div class="col-md-10">
	<span class="has-error"   style="float:left; <?=$warehouse_more?'':'display:none'?>" > 
	 <select name="warehouse" class="form-control input-medium select2me" required  data-placeholder="请选择">
	 <?php warehouse($_SESSION['ruku_warehouse'],1,1);?>
	 </select>
	</span>

	 <input type="text" class="form-control input-medium <?=$baoguo_req_whPlace?'input_txt_red':''?> tooltips" data-container="body" data-placement="top" data-original-title="仓位"  name="whPlace"  onClick="select();" onFocus="select();" placeholder="仓位" <?=$baoguo_req_whPlace?'required':''?> value="<?=cadd($_POST['whPlace'])?>" style="float:left;">
     
<div class="radio-list gray2" style="float:left;">
   <label class="radio-inline">
   <input type="radio" name="getTyp" value="1" <?=$_SESSION['getTyp']==1||!$_SESSION['getTyp']?'checked':''?>  onClick="getBaoguoDate();"> 该会员最后所用仓位
   </label>
   
   <label class="radio-inline">
   <input type="radio" name="getTyp" value="2" <?=$_SESSION['getTyp']==2?'checked':''?>  onClick="getBaoguoDate();"> 刚才所用仓位
   </label>
</div>

	</div>
  </div>
	<?php if($bg_shelves){?>	
	<div class="form-group">
		<label class="control-label col-md-2">上架状态</label>
		<div class="col-md-10">
            <div class="radio-list">
               <label class="radio-inline">
               <input type="radio" name="shelves" value="0" <?=!$_SESSION['in_shelves']?'checked':''?>> 待上架
               </label>

               <label class="radio-inline">
               <input type="radio" name="shelves" value="1" <?=$_SESSION['in_shelves']?'checked':''?>> 已上架
               </label>
          </div>
		</div>
	</div>
	<?php }else{?>
   	    <input name="shelves" type="hidden" value="1" />
	<?php }?>
    	
	 <div class="form-group">
		<label class="control-label col-md-2">打印</label>
		<div class="col-md-10">
			<select class="form-control select2me input-small" data-placeholder="打印模板" name="print_tem">
			<option></option>
			<?php baoguo_print($_SESSION['print_tem'],1)?>
			</select>
            <a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="用默认打印自动打印 (请先设置好默认打印机和纸张)"> <i class="icon-info-sign"></i> </a>
		</div>
	</div>

	 <!--2017.06.17:删除dgid-->

  <div class="form-group">
	<label class="control-label col-md-2">管理留言</label>
	<div class="col-md-10">
	  <textarea name="reply" class="form-control" placeholder="给会员留言"><?=cadd($_POST['reply'])?></textarea>
	</div>
  </div>
  
  
</div>
</div>



<div class="portlet">
    <div class="portlet-title">
      <div class="caption"><i class="icon-reorder"></i>物品信息</div>
      <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
    </div>
    <div class="portlet-body form" style="display: block;"> 
		<?php wupin_from_general('','',$wp);//通用物品表单 ?> 
    </div>
</div>
<!--表单内容-结束------------------------------------------------------------------------------------------------------>        
        
                
<div align="center">

      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
</div>

</div>
</div>
</form>
<div class="xats">
	<strong>提示:</strong><br />
	&raquo; 支持外置设备，如扫描枪、电子秤等！(使用提示：先扫描单号按切换键“Tab”，然后再放上电子秤即可！电子称需要支持即插即用)<br />
	&raquo; 会员预报时可能有选错仓库，因此本页入库不分仓库权限（任何仓库的包裹都可入库，在入库时请选择真实所在仓库）<br />
	&raquo; 光标在文本框时按回车可直接提交<br />
</div>


<script>
$(function(){  
	document.getElementsByName("userid")[0].focus(); //停留
	document.getElementsByName("userid")[0].select(); //全选
});
</script>


<script src="/js/AntongJQ.js" type="text/javascript"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/js/baoguoJS.php');?>

