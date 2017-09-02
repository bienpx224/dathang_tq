<?php 
//防止重复提交
$token=new Form_token_Core();
$tokenkey=$token->grante_token("daigou{$dgid}");
?>

<!----------------------------------------基本资料---------------------------------------->
<form action="" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除style="margin:20px;"-->
<input type="hidden" name="typ" value="save">
<input type="hidden" name="dgid" value="<?=$dgid?>">
<input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
<div class="portlet">
   <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i><?=$LG['data.form_2'];//基本资料?></div>
    <div class="tools"> <a href="javascript:;" class="collapse"></a> </div><!--缩小expand 展开collapse-->
  </div>
 <div class="portlet-body form"> 
    <!--表单内容-->

    <div class="form-group">
      <div class="control-label col-md-2 right"><?=$LG['daigou.60'];//代购单号?></div>
      <div class="col-md-2 has-error">
      <?=cadd($rs['dgdh'])?>
      </div>

      <div class="control-label col-md-2 right"><?=$LG['status'];//状态?></div>
      <div class="col-md-2 has-error">
          <span class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['function.148']?><?=DateYmd($rs['statusTime'])?>">
		  <?=daigou_Status(spr($rs['status']))?>
		  </span>
      </div>

      <div class="control-label col-md-2 right"><?=$LG['source'];//来源?></div>
      <div class="col-md-2 has-error">
      <?=daigou_addSource($rs['addSource'])?>
      </div>

    </div>
    
    
      <div class="form-group">
       <div class="control-label col-md-2 right"><?=$LG['member'];//会员?></div>
      <div class="col-md-2 has-error">
      <?=cadd($rs['username'])?> (<?=$rs['userid']?>)
      </div>

    <div class="control-label col-md-2 right"><?=$LG['daigou.146'];//会员申请?></div>
      <div class="col-md-2 has-error">
          <?=daigou_memberStatus('',($callFrom=='member'?4:3),$rs)?>
      </div>

     <div class="control-label col-md-2 right"><?=$LG['daigou.147'];//支付情况?></div>
      <div class="col-md-2 has-error">
      <?=daigou_payStatus($rs['pay'])?>
      </div>
      

    </div>  
                   
    

   <div class="form-group">
      
      <div class="control-label col-md-2 right"><?=$LG['Museric'];//入库码?></div>
      <div class="col-md-2 has-error">
      <?=cadd($rs['whcod'])?>
      </div>

      <div class="control-label col-md-2 right"><?=$LG['baoguo.send_4'];//入库时间?></div>
      <div class="col-md-2 has-error">
      <?=DateYmd($rs['inStorageTime'])?>
      </div>
   
      <div class="control-label col-md-2 right"><?=$LG['addtime'];//下单时间?></div>
      <div class="col-md-2 has-error">
      <?=DateYmd($rs['addtime'])?>
      </div>
      
    </div>
    
    
  
 <div class="form-group">
  <label class="control-label col-md-2"><?=$LG['positions'];//仓位?> </label>
  <div class="col-md-4">
     <input type="text" class="form-control input-medium popovers" data-trigger="hover" data-placement="top"  data-content="双击自动输入<br>该单所有商品全部放同个仓位"  name="whPlace" value="<?=cadd($rs['whPlace'])?>"
ondblclick="$(this).val( $('#whPlace').html() );"    
     >
  </div>
  
	<label class="control-label col-md-2">仓库</label>
	<div class="col-md-4 has-error">
	 <select name="warehouse" class="form-control input-medium select2me tooltips" data-container="body" data-placement="top" data-original-title="该单所有商品全部放同个仓位" required  data-placeholder="请选择">
	 <?php warehouse($rs['warehouse']?$rs['warehouse']:$_SESSION['inStorage_warehouse'],1,1);?>
	 </select>
	</div>
    
</div>



  
</div> 
</div>  
          
          
          
          
          
          
          
          
<div class="portlet">
<div class="portlet-title">
  <div class="caption"><i class="icon-reorder"></i><?=$LG['daigou.129'];//商品资料?></div>
  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
</div>
<div class="portlet-body form">
<!--表单内容-开始-->

 <table class="table table-striped table-bordered table-hover" >
  <thead>
    <tr>
      <th align="center"><?=$LG['daigou.godh'];//商品单号?></th>
      <th align="center">现入库数量</th>
      <th align="center">单件重量</th>
      <th align="center">单件尺寸</th>

      <th align="center">商品条码</th>
      <th align="center">已入库</th>
      <th align="center">数量</th>
      
      
      <th align="center"><?=$LG['front.86'];//颜色?></th>
      <th align="center"><?=$LG['front.85'];//尺寸?></th>
      <th align="center"><?=$LG['status'];//状态?></th>
      
    </tr>
  </thead>
  
  <tbody>

<?php 
$gd_i=0;
$query_gd="select * from daigou_goods where dgid='{$rs['dgid']}' and number>0 and inventoryNumber<number order by godh asc";
$sql_gd=$xingao->query($query_gd);
while($gd=$sql_gd->fetch_array())
{
	$gd_i++;
	if($weight&&$gd['weight']){$weight=spr($gd['weight']);}
	if($sizeLength&&$gd['sizeLength']){$sizeLength=spr($gd['sizeLength']);}
	if($sizeWidth&&$gd['sizeWidth']){$sizeWidth=spr($gd['sizeWidth']);}
	if($sizeHeight&&$gd['sizeHeight']){$sizeHeight=spr($gd['sizeHeight']);}
	if($barcode&&$gd['barcode']){$barcode=cadd($gd['barcode']);}
?>
	<input type="hidden" name="goid[]" value="<?=$gd['goid']?>">
    
    <tr class="odd gradeX <?=$gd['number']<=$gd['inventoryNumber']?'gray2':''?>">
      <td align="center" valign="middle"><?=cadd($gd['godh'])?></td>
     
      <td align="center" valign="middle">
      <input type="text" class="form-control input-small popovers" data-trigger="hover" data-placement="top"  data-content="双击自动输入全部 (输入负数时减掉数量)"  name="inventoryNumber[]"
      ondblclick="$(this).val( <?=spr($gd['number']-$gd['inventoryNumber'])?> );" 
      onBlur="if($(this).val()><?=spr($gd['number']-$gd['inventoryNumber'])?>){alert('<?=cadd($gd['godh'])?>:入库数量超过了原数量!');$(this).focus();}" 
     />
      </td>
     
      <td align="center" valign="middle">
      <input type="text" class="form-control input-small popovers" data-trigger="hover" data-placement="top"  data-content="双击自动输入" name="weight[]" value="<?=spr($gd['weight'],2,0)?>" 
      ondblclick="$(this).val( $('#weight').html() );"
      onBlur="if($(this).val()>0){$('#weight').html( $(this).val() );}" 
     />
	  <?=$XAwt?>
      </td>
      
    <td align="center" valign="middle">
长<input name="sizeLength[]" type="text"  value="<?=spr($gd['sizeLength'],2,0)?>" class="form-control input-xsmall popovers" data-trigger="hover" data-placement="top"  data-content="双击自动输入" 
ondblclick="$(this).val( $('#sizeLength').html() );"
onBlur="if($(this).val()>0){$('#sizeLength').html( $(this).val() );}" 
/>

*宽<input name="sizeWidth[]" type="text" value="<?=spr($gd['sizeWidth'],2,0)?>" class="form-control input-xsmall popovers" data-trigger="hover" data-placement="top"  data-content="双击自动输入" 
ondblclick="$(this).val( $('#sizeWidth').html() );"
onBlur="if($(this).val()>0){$('#sizeWidth').html( $(this).val() );}" 
/>

*高 <input name="sizeHeight[]" type="text" value="<?=spr($gd['sizeHeight'],2,0)?>" class="form-control input-xsmall popovers" data-trigger="hover" data-placement="top"  data-content="双击自动输入" 
ondblclick="$(this).val( $('#sizeHeight').html() );"
onBlur="if($(this).val()>0){$('#sizeHeight').html( $(this).val() );}" 
/>
 
 (<?=$XAsz?>)
      </td>
      
     
      <td align="center" valign="middle">
      <input type="text" class="form-control input-small popovers" data-trigger="hover" data-placement="top"  data-content="双击自动输入 (填写后可支持备案渠道发货)"  name="barcode[]" value="<?=cadd($gd['barcode'])?>"
      ondblclick="$(this).val( $('#barcode').html() );"
      onBlur="if($(this).val()>0){$('#barcode').html( $(this).val() );}" 
     />
      </td>
     
      <td align="center" valign="middle"><?=spr($gd['inventoryNumber'])?></td>
      <td align="center" valign="middle"><?=$gd['number']?></td>
      
      <td align="center" valign="middle"><?=$gd['color']>0?classify($gd['color'],2):cadd($gd['colorOther'])?></td>
      <td align="center" valign="middle"><?=$gd['size']>0?classify($gd['size'],2):cadd($gd['sizeOther'])?></td>
      <td align="center" valign="middle"><?=$gd['number']>0?daigou_memberStatus('',2,$gd):daigou_Status(10,2)?></td>
    </tr>
<?php }?>


  </tbody>
</table>
<font style="display:none" id="whPlace"><?=$_SESSION['inStorage_whPlace']?></font> 
<font style="display:none" id="weight"><?=$weight?></font> 
<font style="display:none" id="sizeLength"><?=$sizeLength?></font> 
<font style="display:none" id="sizeWidth"><?=$sizeWidth?></font> 
<font style="display:none" id="sizeHeight"><?=$sizeHeight?></font> 
<font style="display:none" id="barcode"><?=$barcode?></font> 

           
            
</div>
</div>


<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">
<button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled >
<i class="icon-ok"></i> <?=$LG['submit']?> </button>
<button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
</div>
         
         



</form>