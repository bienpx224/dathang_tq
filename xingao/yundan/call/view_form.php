<form action="?" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
<input name="type" type="hidden" value="smt">
<div><!-- class="tabbable tabbable-custom boxless"-->
<div class="tab-content">
<div class="tab-pane active" id="tab_1">
<div class="form">
<div class="form-body"> 
<!--表单内容-开始------------------------------------------------------------------------------------------------------> 

<div class="portlet">
    <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>查询</div>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
    </div>
	<div class="portlet-body form" style="display: block;">
	
		<div class="form-group">
			<label class="control-label col-md-2">批次号</label>
			<div class="col-md-10">
                <textarea  class="form-control input-large tooltips" data-container="body" data-placement="top" data-original-title="一行一个" rows="4" name="number" placeholder="一行一个"><?=$number?></textarea>
                
                 <br> <br>
                <input name="op_number" type="checkbox" value="1"  <?php if($op_number||!$type){echo "checked";}?>/>多个号码时合并查看<br>
                
			</div>
		</div>
        
		<div class="form-group">
			<label class="control-label col-md-2">航班/船运/托盘号</label>
			<div class="col-md-10">
<?php 
    //$classtag='so';//标记:同个页面,同个$classtype时,要有不同标记
    //$classtype=1;//分类类型
    //$classid=8;//已保存的ID
    require($_SERVER['DOCUMENT_ROOT'].'/public/classify.php');
?>
			</div>
		</div>
	
        <div class="form-group">
			<label class="control-label col-md-2">查看类型</label>
			<div class="col-md-10">
           	 <div class="radio-list">
             <label>
				<input name="op_proportion" type="radio" value="1"  <?php if($op_proportion==1){echo "checked";}?>/>
                <font class=" popovers" data-trigger="hover" data-placement="top"  data-content="比如1个运单里,有5行物品是衣服的分类(品名或其他参数不同),各行所填写的数量为10,那按50个统计">
                物品分类比例 (按物品所填数量统计)
                </font>
                </label>
                
                <label>
				<input name="op_proportion" type="radio" value="2"  <?php if($op_proportion==2){echo "checked";}?>/>
                <font class=" popovers" data-trigger="hover" data-placement="top"  data-content="比如1个运单里,有5行物品是衣服的分类,各行所填写的数量为10,那也是按1个统计">
                物品分类比例 (按运单所含数量统计)
                </font>
               </label>
                
                <label>
				<input name="op_proportion" type="radio" value="3"  <?php if($op_proportion==3||!$type){echo "checked";}?>/>
                <font class=" popovers" data-trigger="hover" data-placement="top"  data-content="比如1个运单里,有5行物品是衣服的分类,各行所填写的数量为10,那也是按1个统计">
                物品分类比例 (按运单数量统计)
                </font>
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
</div>

<script language="javascript">
//默认光标在某个INPUT停留,可不用放在foot.php后面,要确保有那个ID,否则会停止执行后面的其他JS
$(function(){       
	document.getElementsByName("number")[0].focus(); //停留
	document.getElementsByName("number")[0].select(); //全选
});

</script>