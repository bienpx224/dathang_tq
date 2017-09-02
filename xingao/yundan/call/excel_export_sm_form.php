
<div class="portlet">
  <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i>添加运单</div>
    <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
  </div>
  <div class="portlet-body form" style="display: block;"> 
    <!--表单内容-->
    <form action="?" method="post" class="form-horizontal form-bordered" name="xingao">
      <input name="typ" type="hidden" value="smt">
      <div class="form-group">
        <label class="control-label col-md-2">号码</label>
        <div class="col-md-10 has-error">
          <input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="运单号/第三方转运单号/派送快递单号/HS-HG号码" name="number" required value="<?=$number?>" placeholder="填写/扫描号码">
          <button type="submit" class="btn btn-info input-small"> <i class="icon-plus"></i> 添加 </button>
          
		 <?php if($ppt){echo '<font class="'.$alert_color.'" style="font-size: 16px;">'.$ppt.'</font>';}?>
         
         <span class="help-block">
         <input type="checkbox" name="sm_op1" value="1"  <?php if($sm_op1){echo "checked";}?>>只添加已支付的运单
         </span>

         
        </div>
      </div>
    </form>
    
  </div>
</div>


