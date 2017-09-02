<div class="portlet">
  <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i>共<?=$num=mysqli_num_rows($xingao->query("select * from yundan_export_temp where userid='{$Xuserid}' "));?>个运单</div>
    <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
  </div>
  <div class="portlet-body form" style="display: block;"> 
    <!--表单内容-->
    
    <form action="excel_export/" method="post" class="form-horizontal form-bordered" name="xingao" target="_blank">
      <!--目录后面要加/否则获取不到值-->
      <input type="hidden" name="lx" value="sm">
      <input type="hidden" name="call_lx" value="manage">
      <div class="form-group">
        <label class="control-label col-md-2">报表类型</label>
        <div class="col-md-10 has-error">
          <select class="form-control select2me input-medium" data-placeholder="报表类型" name="ex_tem" required>
            <option></option>
            <?php yundan_excel_export('',1)?>
          </select>
          <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> 导 出 </button>
          <a href="?typ=del&epid=all" class="btn btn-default"  style="margin-left:30px;"><i class="icon-remove"></i> 清空列表</a>
        </div>
      </div>
   </form> 
      


	<!---->
<?php
$order=' order by epid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页
$query="select * from yundan_export_temp where userid='{$Xuserid}' {$order}";
//分页处理
//$line=1;$page_line=15;//不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
?>
	<table class="table table-striped table-bordered table-hover" style="border:0px solid #ddd;">
	<thead>
	<tr>
    
 	<th align="center"><a href="?<?=$search?>&orderby=epid&orderlx=" class="<?=orac('epid')?>">序号ID</a></th>
	<th align="center">运单号</th>
	<th align="center">重量</th>
	<th align="center">收件人</th>
	<th align="center">证件</th>
	<th align="center">操作</th>
	</tr>
	</thead>
	<tbody>
	<?php 
	while($rs=$sql->fetch_array())
	{
		$yd=FeData('yundan','ydid,ydh,weight,s_name,s_mobile,s_shenfenhaoma,s_shenfenimg_z,s_shenfenimg_b',"ydid='{$rs['ydid']}'");
	?>
	<tr class="odd gradeX">
	
	<td align="center" valign="middle"><?=$rs['epid']?></td>
	<td align="center" valign="middle"><a href="show.php?ydid=<?=$yd['ydid']?>" target="_blank"><?=cadd($yd['ydh'])?></a></td>
	<td align="center" valign="middle"><?=$yd['weight']>0?spr($yd['weight']).$XAwt:''?></td>
	<td align="center" valign="middle"><?=cadd($yd['s_name'])?>:<?=cadd($yd['s_mobile'])?></td>
      
	<td align="center" valign="middle">
      <?=cadd($yd['s_shenfenhaoma'])?>
	  <?=ShowImg(cadd($yd['s_shenfenimg_z']).','.cadd($yd['s_shenfenimg_b']))?>
	  </td>
	<td align="center" valign="middle">
   <a href="?typ=del&epid=<?=$rs['epid']?>" class="btn btn-xs btn-danger"><i class="icon-remove"></i> <?=$LG['del']?></a>
    </td>
	</tr>
	<?php }?>
	</tbody>
	</table>
	<div class="row">
		<?=$listpage?>
	</div>
	<!---->

      
   
    
    <!--表单内容 结束--> 
  </div>
</div>

<div class="xats">
&raquo; 导出时按序号从小到大排序
</div>