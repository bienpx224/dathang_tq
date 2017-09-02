<?php 
//获取参数
$hsid=par(ToStr($_REQUEST['hsid']));
if($hsid){$r=WiData('hscode','number_str',"hsid in ({$hsid}) and types=2",0,PHP_EOL);  $wh_lotno=$r['number_str'];}
?>

<div class="portlet">
<div class="portlet-title">
  <div class="caption"><i class="icon-reorder"></i>选择运单</div>
  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
</div>              
<div class="portlet-body form" style="display: block;"> 
<!--表单内容-->
<div class="form-group">
  <label class="control-label col-md-2">批次号</label>
  <div class="col-md-10">
    <textarea  class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="一行一个；填“Empty”则没填批次号的运单；" rows="5" name="wh_lotno"><?=$wh_lotno?></textarea>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">航班/船运/托盘号</label>
  <div class="col-md-10">
    <?php 
        $classtag='wh_';//标记:同个页面,同个$classtype时,要有不同标记
        $classtype=3;//分类类型
        //$classid=$wh_classid;//已保存的ID
        require($_SERVER['DOCUMENT_ROOT'].'/public/classify.php');
    ?>
  </div>
</div>


<div class="form-group">
  <label class="control-label col-md-2">运单号</label>
  <div class="col-md-10">
    <textarea  class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="一行一个" rows="5" name="wh_ydh"></textarea>
    <br>
	<br>
    
	<input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="单号范围-起" name="wh_s_ydh">
	-
	<input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="单号范围-止" name="wh_b_ydh">

  </div>
</div>



<div class="form-group">
  <label class="control-label col-md-2">运单ID</label>
  <div class="col-md-10">
	<input type="text" class="form-control tooltips" data-container="body" data-placement="top" data-original-title="用英文逗号“,”分开" name="wh_ydid" value="<?=$ydid?>"><br><br>
    
	<input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="ID范围-起" name="wh_s_ydid">
	-
	<input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="ID范围-止" name="wh_b_ydid">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">会员</label>
  <div class="col-md-10">
  <textarea  class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="会员名或会员ID，一行一个" rows="5" name="wh_username"></textarea>
  </div>
</div>

 <div class="form-group">
  <label class="control-label col-md-2">出库日期</label>
  <div class="col-md-10">
	 <input class="form-control form-control-inline input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="wh_chukutime_s"> -
	 <input class="form-control form-control-inline input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="wh_chukutime_b">
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
  <label class="control-label col-md-2">运单来源</label>
  <div class="col-md-10">
	<select  class="form-control input-medium select2me" data-placeholder="请选择" name="wh_addSource">
	<option></option>
	<?=yundan_addSource('',1)?>
   </select>			
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">目前状态</label>
  <div class="col-md-10">
	<select  class="form-control input-medium select2me" data-placeholder="请选择" name="wh_status">
	<option></option>
	<?php yundan_Status('')?>
   </select>			
  </div>
</div>

<div class="form-group">
<label class="control-label col-md-2">所在仓库</label>
<div class="col-md-10">
 <select name="wh_warehouse" class="form-control input-medium select2me" data-placeholder="请选择" onChange="wh_channel_show();">
	 <option></option>
	 <?php warehouse('',1);?>
 </select>
</div>
</div>
				  
<?php if($ON_country){?>
   <div class="form-group">
	 <label class="control-label col-md-2">寄往国家</label>
	<div class="col-md-10">
	 <select multiple="multiple" class="multi-select" id="my_multi_select3_0" name="wh_country[]" data-placeholder="请选择">
		  <option></option>
		  <?=Country('',2)?>
	  </select>
   </div>
   </div>
<?php }?>

				  
<div class="form-group">
  <label class="control-label col-md-2">运输渠道</label>
  <div class="col-md-10">
  <span id='wh_channel'><span class="help-block">请先选择仓库</span></span>
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
	 <input type="radio" name="wh_pay" value="1"> 已付
	 </label>
	 <label class="radio-inline">
	 <input type="radio" name="wh_pay" value="0"> 未付
	 </label>  
	</div>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">派送单号</label>
  <div class="col-md-10">
	<div class="radio-list">
	 <label class="radio-inline">
	 <input type="radio" name="wh_gnkdydh" value="" checked> 不限
	 </label>
	 <label class="radio-inline">
	 <input type="radio" name="wh_gnkdydh" value="0"> 未填
	 </label>  
	 <label class="radio-inline">
	 <input type="radio" name="wh_gnkdydh" value="1"> 已填
	 </label>
	</div>
  </div>
</div>

</div>
</div>

<script language="javascript">
//显示渠道下拉
function wh_channel_show() 
{
	var wh_warehouse=document.getElementsByName("wh_warehouse")[0].value ;
	var wh_xmlhttp_channel=createAjax(); 
	if (wh_xmlhttp_channel) 
	{  
		wh_xmlhttp_channel.open('POST','/public/ajax.php?n='+Math.random(),true); 
		wh_xmlhttp_channel.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		wh_xmlhttp_channel.send('lx=channel&callFrom=manage&channel=&warehouse='+wh_warehouse+'');

		wh_xmlhttp_channel.onreadystatechange=function() 
		{  
			if (wh_xmlhttp_channel.readyState==4 && wh_xmlhttp_channel.status==200) 
			{ 
				document.getElementById('wh_channel').innerHTML='<select multiple="multiple" class="multi-select" id="my_multi_select2" name="wh_channel[]" style="padding:5px; width:240px; height:150px;">'+unescape(wh_xmlhttp_channel.responseText)+'</select>'; 
			}
		}
	}
}
</script>
