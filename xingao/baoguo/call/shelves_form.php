<div class="tabbable tabbable-custom boxless">
	<ul class="nav nav-tabs">
		<li class="active"><a>上架操作</a></li>
	</ul>
	<div class="tab-content" style="padding:10px;"> 

<form action="?" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
<input name="lx" type="hidden" value="smt" />
<input name="sm" type="hidden" value="<?=$sm?>" />
<!--表单内容-开始------------------------------------------------------------------------------------------------------> 
 <div class="portlet">
	<div class="portlet-body form" style="display: block;">
	<div class="form-group">
		<label class="control-label col-md-2">单号</label>
		<div class="col-md-10">
		<input type="text" class="form-control input-medium input_txt_red" name="bgydh"  value="<?=$bgydh?>" required onBlur="getBaoguoDate(1);"><font class="red" id="cw_msg"></font>
		</div>
	</div>
		
 <div class="form-group">
	<label class="control-label col-md-2">仓位</label>
	<div class="col-md-10">
	 <input type="text" class="form-control input-medium <?=$shelves_req_whPlace?'input_txt_red':''?> tooltips" data-container="body" data-placement="top" data-original-title="仓位"  name="whPlace"  onClick="select();" onFocus="select();" placeholder="仓位" <?=$shelves_req_whPlace?'required':''?> value="<?=cadd($_POST['whPlace'])?>"  style="float:left;">
     
<div class="radio-list gray2" style="float:left;">
   <label class="radio-inline">
   <input type="radio" name="getTyp" value="0" <?=!$_SESSION['getTyp']?'checked':''?>  onClick="getBaoguoDate(1);"> 该包裹原仓位
   </label>
   
   <label class="radio-inline">
   <input type="radio" name="getTyp" value="1" <?=$_SESSION['getTyp']==1||!$_SESSION['getTyp']?'checked':''?>  onClick="getBaoguoDate(1);"> 该会员最后所用仓位
   </label>
   
   <label class="radio-inline">
   <input type="radio" name="getTyp" value="2" <?=$_SESSION['getTyp']==2?'checked':''?>  onClick="getBaoguoDate(1);"> 刚才所用仓位
   </label>
</div>

	</div>
  </div>
		
	 <div class="form-group">
		<label class="control-label col-md-2">打印</label>
		<div class="col-md-10">
			<select class="form-control select2me input-small" data-placeholder="打印模板" name="print_tem">
			<option></option>
			<?php baoguo_print($_SESSION['sv_print_tem'],1)?>
			</select>
            <a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="用默认打印自动打印 (请先设置好默认打印机和纸张)"> <i class="icon-info-sign"></i> </a>
		</div>
	</div>

 		
	
 
  
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
	&raquo; 支持外置设备，如扫描枪等！(光标在输入框时可直接按回车提交)<br />
	&raquo; 扫描完单号后，按切换键可自动获取仓位<br />
</div>



<script language="javascript">
//默认光标在某个INPUT停留,可不用放在foot.php后面,要确保有那个ID,否则会停止执行后面的其他JS
$(function(){  
	document.getElementsByName("bgydh")[0].focus(); //停留
	document.getElementsByName("bgydh")[0].select(); //全选
});
</script>


<script src="/js/AntongJQ.js" type="text/javascript"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/js/baoguoJS.php');?>


