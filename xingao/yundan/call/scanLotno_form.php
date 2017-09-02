<div class="portlet">
  <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i>操作</div>
    <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
  </div>
  <div class="portlet-body form" style="display: block;"> 
    <!--表单内容-->
    <form action="?" method="post" class="form-horizontal form-bordered" name="xingao">
      <input name="typ" type="hidden" value="add">
      
      
      
        <div class="form-group">
        <label class="control-label col-md-2">运单号码</label>
        <div class="col-md-10">
          <input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="运单号/第三方转运单号/派送快递单号/HS-HG号码" name="number" value="" placeholder="填写/扫描号码">
          
          <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="<strong>复称重量</strong><br>扫描单号后按Tab键切换到此框再放上电子称<br>留空则不修改(改为待复称请填0)" name="weightRepeat" value="" placeholder="复称重量">
          <a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="作用是确保包裹完整,防止工作人员计费称重后进行的其他操作引起的物品丢失问题"> <i class="icon-info-sign"></i> </a>          
          <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-plus"></i> 添加运单 </button>
          
		 <?php if($ppt){echo '<font class="'.$alert_color.'" style="font-size: 16px;">'.$number.'：'.$ppt.'</font>';}?>
         
         <span class="help-block">
             <input type="checkbox" name="sm_op5" value="1"  <?php if($sm_op5||!$typ){echo 'checked';}?>>必须复称<br>
             <input type="checkbox" name="sm_op6" value="1"  <?php if($sm_op6||!$typ){echo 'checked';}?>>只添加复称未超过仓库所设置差值<br>
             
			 <input name="sm_op3" type="checkbox" value="1"  <?php if($sm_op3||!$typ){echo 'checked';}?>/>
			只添加已付款的运单<br>
             <?php if($off_shenfenzheng){?>
			 <input name="sm_op2" type="checkbox" value="1"  <?php if($sm_op2||!$typ){echo 'checked';}?>/>
			运单渠道相同并且要上传证件时，只添加无重复证件号的运单<br>
             <?php }?>

             
             <input type="checkbox" name="sm_op1" value="1"  <?php if($sm_op1){echo 'checked';}?>>只添加未填写过批次号的运单<br>
             <input type="checkbox" name="sm_op4" value="1"  <?php if($sm_op4){echo 'checked';}?>>只添加未填写过托盘号的运单<br>
             
             
            
        </span>

         
        </div>
      </div>

      
      
       <div class="form-group">&nbsp;</div>
   
      
      
      
  	  <div class="form-group">
        <label class="control-label col-md-2">绑定批次号</label>
        <div class="col-md-10">
            <select  class="form-control input-msmall select2me" name="lotno" data-placeholder="请选择" onChange='document.getElementById("lotno").value=this.value;'>
                <option></option>
<?php 
$query="select * from hscode where types='2' and checked='1' order by hsid desc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$selected=$lotno==cadd($rs['number_str'])?'selected':''; echo '<option value="'.cadd($rs['number_str']).'" '.$selected.'>'.cadd($rs['number_str']).'</option>';
}
?>
            </select>
            
       		 <a href="?typ=generate" class="btn btn-default popovers" data-trigger="hover" data-placement="top"  data-content="按日期自动生成，格式：“A日期”，“B日期”。 比如:今天是2016年11月5号，第一个批次号则是 “A161105”"><i class="icon-tasks"></i> 自动生成一个批次号 </a>
             
       		 <a href="/xingao/hscode/form.php" class="btn btn-default" target="_blank"><i class="icon-tasks"></i> 批量生成/手工填写添加 </a>
             
        </div>
      </div>
      
      
      
      <div class="form-group">
          <label class="control-label col-md-2">绑定托盘号</label>
          <div class="col-md-10">
<?php 
//$classtag='1';//标记:同个页面,同个$classtype时,要有不同标记
//$classtype=3;//分类类型
//$classid=$rs['classid'];//已保存的ID
require($_SERVER['DOCUMENT_ROOT'].'/public/classify.php');
?>

        </div>
       </div>
   
      <div class="form-group">
          <label class="control-label col-md-2"></label>
          <div class="col-md-10">
          <button type="submit" class="btn btn-info input-msmall"  onClick="document.xingao.typ.value='so';" style="margin-right:20px;"> <i class="icon-search"></i> 查看运单</button>
          <span class="help-block"><?=$so_ppt?></span>
        </div>
       </div>

    
    </form>
    
  </div>
</div>

<script language="javascript">
//默认光标在某个INPUT停留,可不用放在foot.php后面,要确保有那个ID,否则会停止执行后面的其他JS
$(function(){       
	document.getElementsByName("number")[0].focus(); //停留
	document.getElementsByName("number")[0].select(); //全选
});
</script>
