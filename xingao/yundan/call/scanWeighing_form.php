<form action="?" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
<input name="lx" type="hidden" value="sm">
<div><!-- class="tabbable tabbable-custom boxless"-->
<div class="tab-content">
<div class="tab-pane active" id="tab_1">
<div class="form">
<div class="form-body"> 
<!--表单内容-开始------------------------------------------------------------------------------------------------------> 

<div class="portlet">
	<div class="portlet-body form" style="display: block;">
	
		<div class="form-group">
			<label class="control-label col-md-2">单号</label>
			<div class="col-md-10 has-error">
				<input type="text" class="form-control input-medium" name="ydh" required value="<?=$ydh?>" placeholder="填写/扫描单号">
			</div>
		</div>
	<div class="form-group">
			<label class="control-label col-md-2">扫描选项</label>
			<div class="col-md-10 help-block">
				<input name="op_ydh" type="checkbox" value="1" <?php if($op_ydh||!$op_ydh){echo "checked";}?>/>
				运单号 <br>

				<input name="op_dsfydh" type="checkbox" value="1"  <?php if($op_dsfydh){echo "checked";}?>/>
				第三方运单号  <br>
				
				<input name="op_gwkdydh" type="checkbox" value="1"  <?php if($op_gwkdydh){echo "checked";}?>/>
				寄库快递单号  <br>
				
				<input name="op_gnkdydh" type="checkbox" value="1"  <?php if($op_gnkdydh){echo "checked";}?>/>
				派送快递单号  <br>
				
				<input name="op_hscode" type="checkbox" value="1"  <?php if($op_hscode){echo "checked";}?>/>
				HG/HS编码  <br>

			</div>
		</div>
		<div class="form-group">&nbsp;</div>
		<div class="form-group">
			<label class="control-label col-md-2">操作类型</label>
			<div class="col-md-10 help-block">
				<div class="radio-list">
					 <label>
					 <input type="radio" name="calc" value="fee" <?php if($calc=='fee'||!$calc){echo "checked";}?>> 称重计费
					 </label>
					 
					 <label>
					 <input type="radio" name="calc" value="tax" <?php if($calc=='tax'){echo "checked";}?>> 计算税费
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
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">





      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
</div>
</div>
</div>
</form>
<div class="xats">
	<strong>提示:</strong><br />
	&raquo; 支持外置设备，如扫描枪<br />
	&raquo; 如果有相同的单号，只会显示一个 (最新添加的运单)<br />
	<font class="red">&raquo; 如果无弹出页面，请把浏览器的屏蔽广告功能设置为不屏蔽本站广告<br /></font>
</div>

<script language="javascript">
//默认光标在某个INPUT停留,可不用放在foot.php后面,要确保有那个ID,否则会停止执行后面的其他JS
$(function(){       
	document.getElementsByName("ydh")[0].focus(); //停留
	document.getElementsByName("ydh")[0].select(); //全选
});

</script>