<div class="page_ny">
  <form action="?<?=$urlpar?>" method="post" class="form-horizontal form-bordered" name="XingAoForm">
    <input name="tmp" type="hidden" value="<?=add($tmp)?>">
    <input name="typ" type="hidden" value="<?=add($typ)?>">

  <div class="form">
      <div class="form-body"> 
 
 
<?php if(($typ!='add'&&$typ)||!$typ){?>    
<div align="center" style="margin-bottom:20px;">
    <button type="button" class="btn btn-info" onClick="location.href='?typ=add<?=$urlpar?>#add1';"  style=" height:40px;"><i class="icon-plus"></i> <?=$LG['daigou.122'];//添加商品?> </button>
</div>            
<?php }?>
 


        <!--列表----------------------------------------------------------------------------->
        <div class="portlet">
          <div class="portlet-title">
            <div class="caption"><i class="icon-reorder"></i><?=$LG['daigou.118'];//商品列表?></div>
            <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
          </div>
          <div class="portlet-body form"> 
            <!--表单内容-开始--> 

            
            
<table class="table table-striped table-bordered table-hover" >
  <thead>
    <tr>
      <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="<?=$LG['checkAll'];//全选/取消?>"/>
      </th>
       <th align="center"><?=$LG['daigou.godh'];//商品单号?></th>
      <th align="center"><?=$LG['daigou.119'];//单价*数量*品牌折扣*手续费?></th>
      <th align="center"><?=$LG['front.86'];//颜色?></th>
      <th align="center"><?=$LG['front.85'];//尺寸?></th>
      <th align="center"><?=$LG['daigou.120'];//保存时间?></th>
      <th align="center"><?=$LG['op'];//操作?></th>
    </tr>
  </thead>
  
  <tbody>
<?php 
if($typ=='addStaging')
{
	$xingao->query("update daigou_goods  set tmpStaging='{$tmp}' where tmp<>'{$tmp}' and tmp<>''  {$Mmy}");
	SQLError('载入暂存');
	$rc=mysqli_affected_rows($xingao);
	if($rc>0){$ppt=LGtag($LG['daigou.125'],'<tag1>=='.$rc);}else{$err=1;$ppt=$LG['daigou.126'];}
}elseif($typ=='delStaging'){
	$xingao->query("update daigou_goods  set tmpStaging='' where tmp<>'{$tmp}' and tmp<>'' and tmpStaging='{$tmp}' {$Mmy}");
	SQLError('消除暂存');
	$rc=mysqli_affected_rows($xingao);
	if($rc>0){$ppt=LGtag($LG['daigou.127'],'<tag1>=='.$rc);}else{$err=1;$ppt=$LG['daigou.128'];}
}

$gd_i=0;
$query="select * from daigou_goods where (tmp='{$tmp}' or tmpStaging='{$tmp}' or (dgid='{$dgid}' and dgid>0) ) {$Mmy} order by goid asc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$gd_i++;
?>

    <tr class="odd gradeX">
      <td rowspan="2" align="center" valign="middle">
        <input type="checkbox" id="a" onClick="chkColor(this);" name="goid[]" value="<?=$rs['goid']?>" />
      </td>
      
      <td align="center" valign="middle"><?=cadd($rs['godh'])?></td>
      <td align="center" valign="middle">
      <font title="<?=$LG['mall_order.Xcall_money_payment_9'];//单价?>"> <?=spr($rs['price'])?> </font>
      *
      <font title="<?=$LG['number'];//数量?>"> <?=spr($rs['number'])?></font> 
      *
      <font title="<?=$LG['daigou.135'];//品牌折扣?>"> <?=spr($_SESSION["{$tmp}brandDiscount"])?><?=$LG['fold']?></font>
      *
      <font title="<?=$LG['daigou.130'];//手续服务费?>"> <?=spr($_SESSION["{$tmp}serviceRate"])?>%</font>
      = 
	  <?php 
	  echo $goodsFee=daigou_goodsFee_calc('',$_SESSION["{$tmp}brandDiscount"],$_SESSION["{$tmp}serviceRate"],'',$rs['number'],$rs['price']);
	  $total_goodsFee+=$goodsFee;
	  ?>
      <script>document.writeln(parent.document.getElementsByName('priceCurrency')[0].value)</script>
      <br>
      </td>
      
      <td align="center" valign="middle"><?=$rs['color']>0?classify($rs['color'],2):cadd($rs['colorOther'])?></td>
      <td align="center" valign="middle"><?=$rs['size']>0?classify($rs['size'],2):cadd($rs['sizeOther'])?></td>
      <td align="center" valign="middle"><?=DateYmd($rs['addtime']);?></td>
      <td align="center" valign="middle">
        <a href="?typ=edit&goid=<?=$rs['goid']?><?=$urlpar?>#location" class="btn btn-xs btn-info"><i class="icon-edit"></i> <?=$LG['edit']?></a>
        
       <a href="?typ=del&goid=<?=$rs['goid']?><?=$urlpar?>#location" class="btn btn-xs btn-danger" onClick="return confirm('<?=$LG['pptDelConfirm'];//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a>
     </td>
    </tr>
    <tr>
      <td colspan="8" align="left" class="gray2">
        <?php if($rs['addid']){echo addressShow($rs['addid']);}?> 
        </td>
    </tr>
<?php }?>


  </tbody>
  
  <thead>
  <tr class="odd gradeX">
    <th align="center" valign="middle"></th>
    <th align="center" valign="middle"><?=$gd_i?></th>
    <th colspan="6" align="left" valign="middle "> 
	  <?=$LG['total'];//总计?>:<?=spr($total_goodsFee+$_SESSION["{$tmp}freightFee"])?>
      <script>document.writeln(parent.document.getElementsByName('priceCurrency')[0].value)</script>
      
      (<?=$LG['daigou.162']//含寄库运费?>)
      <a href="?<?=$urlpar?>" class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['recalculate']//重新计算?>" style="margin-left:10px;"><i class="icon-list-alt"></i></a>
      
      
    </th>
    </tr>
  </thead>

  
</table>


<div style="margin-top:20px; margin-bottom:20px; margin-left:20px;">
        <button type="submit" class="btn btn-grey input-small"
        onClick="
        document.XingAoForm.typ.value='addStaging';
        "><i class="icon-signin"></i> <?=$LG['daigou.123'];//载入暂存列表?> </button>
        
        <button type="submit" class="btn btn-grey input-small"
        onClick="
        document.XingAoForm.typ.value='delStaging';
        "><i class="icon-signout"></i> <?=$LG['daigou.124'];//消除暂存列表?> </button>
        
        <button type="submit" class="btn btn-grey"  style="margin-left:30px;"
        onClick="
        document.XingAoForm.typ.value='del';
        return confirm('<?=$LG['pptDelConfirm'];//确认要删除所选吗?此操作不可恢复!?>');
        "><i class="icon-remove"></i> <?=$LG['delSelect'];//删除所选?></button>
    </div>

  </form>
  
<?php 
//通用提示---------------------------------------------------------------
$pptTyp='success';	if($err){$pptTyp='warning';}
XAalert($ppt,$pptTyp,'margin:10px;');//提示位置1
//XAtoastr($ppt,$pptTyp,'toast-bottom-right');//因为是放在框架里,该效果不好

?>  
<a name="add1"></a>  
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/goods_form.php');?>


            <!--表单内容-结束--> 
          </div>
        </div>
      </div>
    </div>
    
    
    
    
</div>

