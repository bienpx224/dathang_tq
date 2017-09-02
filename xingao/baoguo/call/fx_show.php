    <?php 
    $arr=cadd($rs['fx_wupin']);
    if($rs['fx']==1&&$arr)
    {
	?><br>

      <div class="gray" style="clear:both;border: 1px dashed #CCCCCC; padding:10px; ">
      <?php if($form){?>
	  <div align="center" class="red" ><strong>要分箱时注意上面的分箱状态选择为“ <?=baoguo_fx(2)?>”,否则以下填写不会保存</strong></div>
      <?php }?>
    <?php
	$i=0;
	$num=arrcount($arr);
	if($rs['weight']>0){$b_zl=spr($rs['weight']/($num+1));} //算重量（平分）
	
	if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
	foreach($arr as $arrkey=>$value)
	{
		$i+=1;
	?>
        <br>
        <div class="fx_1" style="width:100%"><?=$i?></div>
        <table class="table table-striped table-bordered table-hover" width="100%">
		<thead>
          <tr>
            <td colspan="9" align="left" bgcolor="#CCCCCC" class="title">单号：
              <input type="hidden" name="mvf_record_j[]" value="<?=$i-1?>" title="默认单号"/>
             
              <input type="text" class="form-control input-small input_txt_red" name="mvf_record_bgydh[]" value="<?=$rs['bgydh']?><?=strtoupper(chr($i+97))?>" />
            
              &nbsp;&nbsp;&nbsp; 仓位：<input type="text" name="mvf_record_weizhi[]"  class="form-control input-small"   value="<?=$rs['whPlace']?>"  title="默认存放"/> 
			  
			 &nbsp;&nbsp;&nbsp;  重量：<input name="mvf_record_weight[]" class="form-control input-xsmall" type="text" size="5" value="<?=$b_zl?>" title="默认平分的重量"/>
              <?=$XAwt?>
<?php if($form){?>
             &nbsp;&nbsp;&nbsp; <input name="mvf_record_fx_suo[]" type="checkbox" value="1" checked="checked"/>不可再申请分箱         
<?php }?>	
			 </td>
          </tr>
          <tr>
            <td align="center" bgcolor="#EAEAEA" class="title">类别</td>
            <td align="center" bgcolor="#EAEAEA" class="title">品名</td>
            <td align="center" bgcolor="#EAEAEA" class="title">品牌/厂商</td>
            <td align="center" bgcolor="#EAEAEA" class="title">数量</td>
            <td align="center" bgcolor="#EAEAEA" class="title">重量<?=$XAwt?></td>
            <td align="center" bgcolor="#EAEAEA" class="title">规格</td>
            <td align="center" bgcolor="#EAEAEA" class="title">单位</td>
            <td align="center" bgcolor="#EAEAEA" class="title">单价<?="(".$XAsc.")"?></td>
            <td align="center" bgcolor="#EAEAEA" class="title">总价<?="(".$XAsc.")"?></td>
          </tr>
		  </thead>
		  <tbody>
          <?php
			$query_wp="select * from wupin where fromtable='{$value}' order by wupin_name desc";
			$sql_wp=$xingao->query($query_wp);
			while($wp=$sql_wp->fetch_array())
			{
          ?>
          <tr>
				<td align="center"><?=is_numeric($wp['wupin_type'])?classify($wp['wupin_type'],2):cadd($wp['wupin_type'])?></td>
				<td align="center"><?=cadd($wp['wupin_name'])?></td>
				<td align="center"><?=cadd($wp['wupin_brand'])?></td>
				<td align="center"><?=spr($wp['wupin_number'])?></td>
				<td align="center"><?=spr($wp['wupin_weight'])?></td>
				<td align="center"><?=cadd($wp['wupin_spec'])?></td>
				<td align="center"><?=is_numeric($wp['wupin_unit'])?classify($wp['wupin_unit'],2):cadd($wp['wupin_unit'])?></td>
				<td align="center"><?=spr($wp['wupin_price'])?></td>
				<td align="center"><?=cadd($wp['wupin_total'])?></td>
          </tr>
          <?php
            }
		  ?>
			</tbody>
        </table>
        <?php
           }//foreach($arr as $arrkey=>$value)
		  ?>
      </div>
    <?php
	}//if($rs['fx']==1&&$arr)
	?>
