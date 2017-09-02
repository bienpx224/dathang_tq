

<div class="portlet">
<div class="portlet-title">
  <div class="caption"><i class="icon-reorder"></i>选择代购单</div>
  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
</div>              
<div class="portlet-body form" style="display: block;"> 
<!--表单内容-->







<div class="form-group">
  <label class="control-label col-md-2">代购单号</label>
  <div class="col-md-10">
    <textarea  class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="一行一个" rows="5" name="wh_dgdh"></textarea>
    <br>
	<br>
    
	<input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="单号范围-起" name="wh_s_dgdh">
	-
	<input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="单号范围-止" name="wh_b_dgdh">

  </div>
</div>



<div class="form-group">
  <label class="control-label col-md-2">代购单ID</label>
  <div class="col-md-10">
	<input type="text" class="form-control tooltips" data-container="body" data-placement="top" data-original-title="用英文逗号“,”分开" name="wh_dgid" value="<?=$dgid?>"><br><br>
    
	<input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="ID范围-起" name="wh_s_dgid">
	-
	<input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="ID范围-止" name="wh_b_dgid">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">会员</label>
  <div class="col-md-10">
  <textarea  class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="会员名或会员ID，一行一个" rows="5" name="wh_username"></textarea>
  </div>
</div>


 <div class="form-group">
  <label class="control-label col-md-2">下单日期</label>
  <div class="col-md-10">
	 <input class="form-control form-control-inline input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="wh_addtime_s"> -
	 <input class="form-control form-control-inline input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="wh_addtime_b">
  </div>
</div>

 <div class="form-group">
  <label class="control-label col-md-2">采购日期</label>
  <div class="col-md-10">
	 <input class="form-control form-control-inline input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="wh_procurementTime_s"> -
	 <input class="form-control form-control-inline input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="wh_procurementTime_b">
  </div>
</div>

 <div class="form-group">
  <label class="control-label col-md-2">入库日期</label>
  <div class="col-md-10">
	 <input class="form-control form-control-inline input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="wh_inStorageTime_s"> -
	 <input class="form-control form-control-inline input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="wh_inStorageTime_b">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">代购单来源</label>
  <div class="col-md-10">
	<select  class="form-control input-medium select2me" data-placeholder="不限" name="wh_addSource">
	<option></option>
	<?=daigou_addSource('',1)?>
   </select>			
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">目前状态</label>
  <div class="col-md-10">
	<select  class="form-control input-medium select2me" name="wh_status" data-placeholder="不限" >
	<option></option>
	<?php daigou_Status('',1)?>
   </select>			
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">商品申请状态</label>
  <div class="col-md-10">
	<select  class="form-control input-medium select2me" name="wh_memberStatus" data-placeholder="不限" >
	<option></option>
	<?php daigou_memberStatus('',1)?>
   </select>			
  </div>
</div>

<div class="form-group">
<label class="control-label col-md-2">货源</label>
<div class="col-md-10">
  <select class="form-control input-small select2me" name="wh_source" data-placeholder="不限">
    <option></option>
    <?php daigou_source('',1)?>
  </select>
  </div>
</div>

<div class="form-group">
<label class="control-label col-md-2">品类</label>
<div class="col-md-10">
  <select multiple="multiple" class="multi-select" id="my_multi_select3_0" name="wh_types[]" data-placeholder="不限">
    <option></option>
    <?php ClassifyAll(4)?>
  </select>
  </div>
</div>

<div class="form-group">
<label class="control-label col-md-2">品牌</label>
<div class="col-md-10">
  <select multiple="multiple" class="multi-select" id="my_multi_select3_1" name="wh_brand[]" data-placeholder="不限">
    <option></option>
    <?php ClassifyAll(6)?>
  </select>
  </div>
</div>

<div class="form-group">
<label class="control-label col-md-2">所在仓库</label>
<div class="col-md-10">
 <select name="wh_warehouse" class="form-control input-medium select2me" data-placeholder="不限">
	 <option></option>
	 <?php warehouse('',1,1);?>
 </select>
</div>
</div>


<div class="form-group">
  <label class="control-label col-md-2">付款情况</label>
  <div class="col-md-10">
	<div class="radio-list">
	 <label class="radio-inline">
	 <input type="radio" name="wh_pay" value="" checked> 不限
	 </label>
     
	 <label class="radio-inline">
	 <input type="radio" name="wh_pay" value="0"> <?=daigou_payStatus(0)?>
	 </label>
     
	 <label class="radio-inline">
	 <input type="radio" name="wh_pay" value="1"> <?=daigou_payStatus(1)?>
	 </label> 
      
	 <label class="radio-inline">
	 <input type="radio" name="wh_pay" value="2"> <?=daigou_payStatus(2)?>
	 </label>  
      
	 <label class="radio-inline">
	 <input type="radio" name="wh_pay" value="3"> <?=daigou_payStatus(3)?>
	 </label>  
	</div>
  </div>
</div>




</div>
</div>


