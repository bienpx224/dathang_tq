
<div class="portlet">
  <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i>操作</div>
    <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
  </div>
  <div class="portlet-body form" style="display: block;"> 
    <!--表单内容-->
    <form action="?<?=$search?>" method="post" class="form-horizontal form-bordered" name="xingao">
      <input name="typ" type="hidden" value="so">
      
  	  <div class="form-group">
        <label class="control-label col-md-2">运单</label>
        <div class="col-md-10 has-error">
           <input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="运单号/运单ID/第三方转运单号/派送快递单号/HS-HG号码" name="yd_number" required value="<?=$yd_number?>" placeholder="填写/扫描运单号码" onBlur='document.getElementById("yd_number").value=this.value;'>

             <button type="submit" class="btn btn-grey input-msmall" style="margin-right:20px;"> <i class="icon-search"></i> 查看该运单的商品资料 </button>
             
             
        </div>
      </div>
  </form>
  
  
    <form action="?" method="post" class="form-horizontal form-bordered" name="xingao">
      <input name="typ" type="hidden" value="add">
      <input name="yd_number" id="yd_number" type="hidden" value="<?=$yd_number?>">
      
      <div class="form-group">
        <label class="control-label col-md-2">商品条码</label>
        <div class="col-md-10 has-error">
          <input type="text" class="form-control input-medium" name="barcode" required value="" placeholder="填写/扫描商品条码">
          <input type="text" class="form-control input-small" name="number" required value="" placeholder="商品数量">
          
          <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-plus"></i> 添加 </button>
          
		 <?php if($ppt){echo '<font class="'.$alert_color.'" style="font-size: 16px;">'.$barcode.'：'.$ppt.'</font>';}?>
         
         <span class="help-block">
             <input type="checkbox" name="sm_op1" value="1"  <?php if($sm_op1||!$typ){echo "checked";}?>>只添加在【日本清关资料】可用的商品<br>

             
			 <input name="sm_op3" type="checkbox" value="1"  <?php if($sm_op3||!$typ){echo "checked";}?>/>
			 只添加已付款的运单<br>
        </span>

         
        </div>
      </div>
    </form>
    
  </div>
</div>

<script language="javascript">
//默认光标在某个INPUT停留,可不用放在foot.php后面,要确保有那个ID,否则会停止执行后面的其他JS
$(function(){       
	document.getElementsByName("barcode")[0].focus(); //停留
	document.getElementsByName("barcode")[0].select(); //全选
});
</script>
