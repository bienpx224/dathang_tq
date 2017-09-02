<form action="?" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
<input name="lx" type="hidden" value="sm">
<div><!-- class="tabbable tabbable-custom boxless"-->
<div class="tab-content">
<div class="tab-pane active" id="tab_1">
<div class="form">
<div class="form-body"> 
<!--表单内容-开始------------------------------------------------------------------------------------------------------> 
<?php if($lx=='pass'){$fe=FeData('yundan','*',"ydid='".par($_GET['ydid'])."'");}?>
<div class="portlet">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>扫描</div>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
    </div>
	<div class="portlet-body form" style="display: block;">
	
		<div class="form-group">
			<label class="control-label col-md-2">单号</label>
			<div class="col-md-10 has-error">
				<input type="text" class="form-control input-medium" name="ydh" required value="<?=$ydh.cadd($fe['ydh'])?>" placeholder="填写/扫描单号">
			</div>
		</div>
	<div class="form-group">
			<label class="control-label col-md-2">扫描选项</label>
			<div class="col-md-10">
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
    </div>
</div>

<div class="portlet">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>修改</div>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
    </div>
	<div class="portlet-body form" style="display: block;">
		<div class="form-group">
		  <label class="control-label col-md-2">状态</label>
		  <div class="col-md-10 has-error">
			<select  class="form-control input-medium select2me" data-placeholder="请选择" name="update_status" required>
			<option></option>
			<?php 
			$status_new=cadd($_SESSION['update_status']);
			if($fe['status']){$status_new=cadd($fe['status']);}
			$status_new=20;//强行设置
			yundan_Status($status_new)
			?>
		   </select>	
			<span class="help-block">
			  <input name="update_op1" type="checkbox" value="1"  <?php if($update_op1){echo "checked";}?>/>
			  已超过该状态的运单也强行修改到该状态<br>
              
              <?php if($off_shenfenzheng){?>
			  <input name="update_op2" type="checkbox" value="1"  <?php if($update_op2||!$lx){echo "checked";}?>/>
			  对于要上传证件的渠道，未上传时状态修改为“<?=status_name(1)?>”<br>
              <?php }?>
			  
			  <input name="update_op3" type="checkbox" value="1"  <?php if($update_op3||!$lx){echo "checked";}?>/>
			  未算费时提示<br>
			  <input name="update_op4" type="checkbox" value="1"  <?php if($update_op4||!$lx){echo "checked";}?>/>
			  未付清款时提示<br>
			</span>
		  </div>
		</div>
			
    
		<div class="form-group">
		  <label class="control-label col-md-2">批次号</label>
		  <div class="col-md-10">
			<input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="状态变更时才修改 (留空则不修改)" name="lotno" value="<?=cadd($lotno).cadd($fe['lotno'])?>">
			<span class="help-block">
			<input name="lotno_op1" type="checkbox" value="1"  <?php if($lotno_op1){echo "checked";}?>/>
			已填过批次号的运单也强行修改为此号<br>
            
			<input name="lotno_op3" type="checkbox" value="1"  <?php if($lotno_op3||!$lx){echo "checked";}?>/>
			未填写批次号时提示<br>
            
            <?php if($off_shenfenzheng){?>
			<input name="lotno_op2" type="checkbox" value="1"  <?php if($lotno_op2||!$lx){echo "checked";}?>/>
			对于要上传证件的渠道，在同一个仓库,同一个批次号里，有重复证件号时提示<br>
            <?php }?>
            
			</span>
		  </div>
		</div>
		
        <div class="form-group">
          <label class="control-label col-md-2">托盘号</label>
          <div class="col-md-10">
            <?php 
                //$classtag='1';//标记:同个页面,同个$classtype时,要有不同标记
                $classtype=3;//分类类型
                //$classid=$rs['classid'];//已保存的ID
                require($_SERVER['DOCUMENT_ROOT'].'/public/classify.php');
            ?>
             <input type="text" class="form-control input-small" name="classid_del" placeholder="填“del”则清除" value="<?=$classid_del?>">
          </div>
        </div>

	 <div class="form-group">
		  <label class="control-label col-md-2">派送单号</label>
		  <div class="col-md-10">
			<select  class="form-control input-medium select2me" data-placeholder="请选择" name="gnkd">
			<option></option>
			<?php yundan_gnkd(cadd($gnkd).cadd($fe['gnkd']) )?>
			</select>
			<input type="text" class="form-control input-medium" name="gnkdydh" value="<?=cadd($gnkdydh).cadd($fe['gnkdydh']) ?>" placeholder="快递单号">
			<span class="help-block">
			<input name="gnkd_op1" type="checkbox" value="1"  <?php if($gnkd_op1){echo "checked";}?>/>
			已填过派送单号的运单也强行修改为此单号
			</span>
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
	&raquo; 如果有相同的单号，只会修改一个 (最新添加的运单)<br />
	<font class="red">&raquo; 如果无弹出页面，请把浏览器的屏蔽广告功能设置为不屏蔽本站广告<br /></font>
</div>

<script language="javascript">
//默认光标在某个INPUT停留,可不用放在foot.php后面,要确保有那个ID,否则会停止执行后面的其他JS
$(function(){       
	document.getElementsByName("ydh")[0].focus(); //停留
	document.getElementsByName("ydh")[0].select(); //全选
});
</script>