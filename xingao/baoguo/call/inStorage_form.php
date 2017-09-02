<form action="?" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
<input name="lx" type="hidden" value="sm_save" />
<input type="hidden" name="status" value="<?=spr($rs['status'])?>"/>
<input type="hidden" name="bgid" value="<?=$rs['bgid']?>"/>

<div><!-- class="tabbable tabbable-custom boxless"-->
<div class="tab-content">
<div class="tab-pane active" id="tab_1">
<div class="form">
<div class="form-body"> 
<!--表单内容-开始------------------------------------------------------------------------------------------------------> 
 <div class="portlet">
	<div class="portlet-body form" style="display: block;">

 <div class="form-group" <?=$warehouse_more?'':'style="display:none"'?>>
	<label class="control-label col-md-2">仓库</label>
	<div class="col-md-10 has-error">
	 <select name="warehouse" class="form-control input-medium select2me" required  data-placeholder="请选择">
	 <?php warehouse($rs['warehouse']?$rs['warehouse']:$_SESSION['ruku_warehouse'],1,1);?>
	 </select>
	</div>
  </div>

	<div class="form-group">
		<label class="control-label col-md-2">单号</label>
		<div class="col-md-4 has-error">
		<input type="text" class="form-control input-medium" name="bgydh" required value="<?=cadd($rs['bgydh'])?>"><font class="red" id="cw_msg"></font>
	</div>
        
                  
                    <label class="control-label col-md-2">快递公司</label>
                    <div class="col-md-4">
                     <select name="kuaidi" class="form-control input-medium select2me"  data-placeholder="请选择">
					 <?php baoguo_kuaidi(cadd($rs['kuaidi']));?>
					 </select>
                    </div>
    
    
	</div>

		
	<div class="form-group">
		<label class="control-label col-md-2">会员</label>
		<div class="col-md-10 has-error">
		 <input type="text" class="form-control input-small" name="useric" autocomplete="off" value="<?=cadd($rs['useric'])?>" title="会员入库码"  placeholder="会员入库码" onBlur="getUsernameId('useric');getBaoguoDate();" required>

		 <input type="text" class="form-control input-small" name="userid" autocomplete="off"  value="<?=cadd($rs['userid'])?>"  title="会员ID"  placeholder="会员ID" onBlur="getUsernameId('userid');getBaoguoDate();" required>
		 
		 <input type="text" class="form-control input-medium" name="username" autocomplete="off" value="<?=cadd($rs['username'])?>" title="会员名"  placeholder="会员名" onBlur="getUsernameId('username');getBaoguoDate();" required>

			<br><span class="gray_prompt2">如果转移给其他会员,可直接修改 (填写其中一项，自动获取其他)</span>	
		</div>
	  </div>

		
	<div class="form-group">
		<label class="control-label col-md-2">重量</label>
		<div class="col-md-10  <?=$baoguo_req_weight?'has-error':''?>">
		<input type="text" class="form-control input-small"  name="weight"  value="<?=spr(($_REQUEST['weight']?$_REQUEST['weight']:$rs['weight']),2,0)?>" <?=$baoguo_req_weight?'required':''?>><?=$XAwt?>
		</div>
	</div>
		
	<div class="form-group">
		<label class="control-label col-md-2">仓位</label>
		<div class="col-md-10">
		<input type="text" class="form-control input-medium <?=$baoguo_req_whPlace?'input_txt_red':''?> tooltips" data-container="body" data-placement="top" data-original-title="仓位"  name="whPlace" onClick="select();" onFocus="select();" value="<?=$whPlace?>" <?=$baoguo_req_whPlace?'required':''?> style="float:left;">
     
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

	<?php if(trim($rs['content'])){?>
	<div class="form-group">
	<div class="control-label col-md-2 right">会员/系统备注</div>
	<div class="col-md-10 has-error">
	  <?=TextareaToBr($rs['content'])?>
	</div>
	</div>
	<?php }?>

  <div class="form-group">
	<label class="control-label col-md-2">回复</label>
	<div class="col-md-10">
	  <textarea name="reply" class="form-control" placeholder="给会员回复"><?=cadd($rs['reply'])?></textarea>
	</div>
  </div>
  
  
</div>
</div>
<!--表单内容-结束------------------------------------------------------------------------------------------------------> 

</div>
</div>
</div>
<div align="center">
<button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled  title="光标在文本框时可直接按回车提交"> <i class="icon-ok"></i> <?=$LG['submit']?> </button>
</div>
</div>
</div>
</form>


<script language="javascript">
//默认光标在某个INPUT停留,可不用放在foot.php后面,要确保有那个ID,否则会停止执行后面的其他JS
$(function(){  
	document.getElementsByName("weight")[0].focus(); //停留
	document.getElementsByName("weight")[0].select(); //全选
});
</script>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/js/baoguoJS.php');?>
