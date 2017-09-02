<?php 
if($status=='delivery')
{
	//发货勾选时:不用框架,直接放到列表中
	$checkbox_where=whereCHK(daigou_deliveryAsk('','',1),'and');
	$checkbox=1;
}else{
	//不是发货勾选时:用框架
	if(!$dgid){exit('dgid:'.$LG['pptEmpty']);}
	$rs=FeData('daigou','*',"dgid='{$dgid}' {$Mmy} {$Xwh}");
}

require($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/basic_show.php');
?>



<!--列表-开始--------------------------------------------------------------------->
 <table class="table table-bordered table-hover">
    <tr>
     <?php if($checkbox&&spr($rs['status'])!=10){?>
      <td align="center"><input type="checkbox"  id="aAll" onClick="chkAll(this);<?=$status=='delivery'?'id_save();':''?>"  title="<?=$LG['checkAll'];//全选/取消?>"/></td> 
	  <?php }?>
      
      <td align="center"><?=$LG['daigou.godh'];//商品单号?></td>
      <td align="center"><?=$LG['status'];//状态?></td>
      <td align="center"><?=$LG['front.86'];//颜色?></td>
      <td align="center"><?=$LG['front.85'];//尺寸?></td>
      <td align="center"><?=$LG['weight'];//重量?></td>
      <td align="center"><?=$LG['price'];//单价?></td>
      <td align="center"><?=$LG['number'];//数量?></td>
      <td align="center"><?=$LG['daigou.165'];//入库?></td>
      <td align="center"><?=$LG['daigou.179'];//发货?></td>
      <td align="center"><?=$LG['daigou.70_1'];//已退货/断退?></td>
      <td align="center"><?=$LG['op'];//操作?></td>
    </tr>
    
	<?php 
    $gd_i=0;
    $query_gd="select * from daigou_goods where  dgid='{$rs['dgid']}'  {$checkbox_where} order by godh asc";
    $sql_gd=$xingao->query($query_gd);
    while($gd=$sql_gd->fetch_array())
    {
        $gd_i++;
	
		//是否勾选:只用于发货
		$checked=0; if(have($id_checked,$gd['goid'])&&$status=='delivery'){$checked=1;}
		
    ?>
        <tr class="odd gradeX <?=!spr($gd['number'])?'gray2':''?>">
        
        
          <?php if($checkbox&&spr($rs['status'])!=10){?>
          <td align="center" valign="middle">
              <?php if(($typ||$status=='delivery')&&$gd['number']>0){?>
              <input name="goid[]" type="checkbox" id="a" onClick="<?=$status=='delivery'?'id_save();':'chkColor(this);'?>" value="<?=$gd['goid']?>"  <?=$checked?'checked':''?>/>
              <?php }?>
          </td>
		  <?php }?>
          
    
    
          <td align="center" valign="middle">
          <?php if($gd['addid']){?>
            <font class=" popovers" data-trigger="hover" data-placement="right"  data-content="<?=striptags(addressShow($gd['addid']))?>">
          <?php }else{echo '<font>';}?>
             <a href="../yundan/list.php?so=1&key=<?=cadd($gd['godh'])?>" target="_blank"><?=cadd($gd['godh'])?></a>
          </font>
          </td>
          
          <td align="center" valign="middle">
			<?=$gd['number']>0||$gd['inventoryNumber']>0||$gd['deliveryNumber']>0?daigou_memberStatus('',2,$gd):daigou_Status(10,2);?>
          </td>
          
          <td align="center" valign="middle"><?=$gd['color']>0?classify($gd['color'],2):cadd($gd['colorOther'])?></td>
          <td align="center" valign="middle"><?=$gd['size']>0?classify($gd['size'],2):cadd($gd['sizeOther'])?></td>
          <td align="center" valign="middle"><?=$gd['weight']>0?spr($gd['weight']).$XAwt:''?></td>
          <td align="center" valign="middle"><?=spr($gd['price']).$rs['priceCurrency']?></td>
          <td align="center" valign="middle"><?=spr($gd['number'])?></td>
          <td align="center" valign="middle"><?=spr($gd['inventoryNumber'])?></td>
          <td align="center" valign="middle"><?=spr($gd['deliveryNumber'])?></td>
          <td align="center" valign="middle">
		  <font class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.69'].DateYmd($gd['lackStatusTime'],1)?><br><?=$LG['daigou.70'].$gd['numberRet']?>"> <?=daigou_lackStatus($gd['lackStatus'],2,$gd['numberRet'])?></font> 
          </td>
          
          
          <td align="center" valign="middle">
          
<?php
//会员操作------------------------------------------------
if($callFrom=='member'&&$gd['number']>0){
?>
	<?php if($status=='delivery'&&daigou_deliveryAsk($rs,$gd)){?>
		<a href="delivery.php?typ=0&dgid=<?=$rs['dgid']?>&goid=<?=$gd['goid']?>" class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.189']?>" target="_blank"><?=$LG['daigou.188']?></a>
	<?php }?> 
     
<?php }?>






<?php
//后台操作------------------------------------------------
if($callFrom=='manage'&&$gd['number']>0){
?>
	<?php if(have('0,1',$gd['manageStatus'])&&$gd['memberStatus']&&daigou_per_op($gd['memberStatus']) ){?>
		<a href="op.php?typ=op&dgid=<?=$rs['dgid']?>&goid=<?=$gd['goid']?>" >处理申请</a>
	<?php }?>  


    <?php if($callFrom=='manage'&&$rs['pay']&&have('0,1,2,3,4,5',spr($rs['status']),1)){?>
    <a href="op.php?typ=procurement&value=2&dgid=<?=$rs['dgid']?>&goid=<?=$gd['goid']?>" class="btn btn-xs btn-info" ><i class="icon-caret-right"></i> 补款/退款</a>
    <?php }?>
    
    <?php if($callFrom=='manage'&&$rs['pay']&&have('0,1,2,3,4,5,6',spr($rs['status']),1)){?>
    <a href="op.php?typ=procurement&value=3&&dgid=<?=$rs['dgid']?>&goid=<?=$gd['goid']?>" class="btn btn-xs btn-info"><i class="icon-caret-right"></i> 断货退款</a>
    <?php }?>
    
<?php }?>
		  
          
          </td>
          
          
        </tr>
        
    <?php }?>
</table>
<!--列表-结束--------------------------------------------------------------------->

