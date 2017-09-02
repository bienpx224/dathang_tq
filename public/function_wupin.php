<?php
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/
if(!defined('InXingAo')){exit('No InXingAo');}



//---------------------------------------------------------------------------------------------------
//表单:通用
/*
	$fromtable='',$fromid='' 空时,可以指定一行物品$rs
	$addSource 运单来源
*/
function wupin_from_general($fromtable='',$fromid='',$wp='',$addSource='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $Mmy,$totalPrice,$bgForecast,$lx,$wupin_id_s;
	if(!$addSource){global $addSource;}
	
	//是否可添加行,删除行
	$LimitOP=0;if($addSource==7){$LimitOP=1;}//7代购下单时不显示添加行,删除行按钮

	?>
	<table width="100%" id="tableProduct">
	<!--自动计算要加这个 id="tableProduct"-->
	<tr>
		 <?=wupin_header_general()?>
		 <th align="center" class="title"></th>
	</tr>
		
		
	
	<?php
	$i=0;
	if($fromtable&&$fromid)
	{
		//代购正在下单时-----------------------------------------
		if($fromtable=='daigou')
		{
			global $weightEstimate;
			$weightEstimate=0;
			$checkbox_where=whereCHK(daigou_deliveryAsk('','',1),'and');
			
			$query_wp="select * from daigou_goods where goid in ({$fromid}) {$Mmy} {$checkbox_where} order by dgid asc,goid asc";
			$sql_wp=$xingao->query($query_wp);
			while($gd=$sql_wp->fetch_array())
			{
				$i++;
				if($dgid!=$gd['dgid']){$rs=FeData('daigou','*',"dgid='{$gd['dgid']}'");$dgid=$gd['dgid'];}
				
				//赋值给wupin_formline_general
				$gd['price']*=exchange($rs['priceCurrency'],$XAScurrency);
				$wp['wupin_type']	= $rs['types'];
				$wp['wupin_brand']	= daigou_brand($rs['brand']);
				$wp['wupin_name']	= $rs['name'];
				$wp['wupin_price']	= $gd['price'];
				$wp['wupin_weight']	= $gd['weight'];
				$wp['wupin_number']	= $gd['inventoryNumber'];
				$wp['wupin_total']	= $gd['price']*$gd['inventoryNumber'];
				$wp['wupin_spec']	= $gd['size']>0?classify($gd['size'],2):$gd['sizeOther'];
				$wp['wupin_unit']	= '';
				$wp['wupin_number_max']	= $gd['inventoryNumber'];//自定:数量最大限制
				$wp['wupin_dh']		= $gd['godh'];//自定:显示单号
				
				$weightEstimate+=$gd['weight']*$gd['inventoryNumber'];
				
				?>
                <tr id="line">
				<input type="hidden" name="goid[]" value="<?=$gd['goid']?>">
				<?=wupin_formline_general($wp,$LimitOP,$lx,$i,$addSource,$wupin_id_s)?>
                </tr>
				<?php
			}

		
		
		
		
		
		
		//运单修改和包裹正在下单时-----------------------------------------
		}else{
			if($addSource==7){$LimitOP=2;}//7代购下单时不显示添加行,删除行按钮
			
			$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid in ({$fromid}) order by wupin_brand asc,wupin_name asc";
			$sql_wp=$xingao->query($query_wp);
			while($wp=$sql_wp->fetch_array())
			{
				$i++;
				//包裹正在下单时:自动减少分包中物品的数量
				if($wp['wupin_number']>0&&$fromtable=='baoguo'){$wp=baoguo_wupin_number($wp);}
				
				?><tr id="line"><?=wupin_formline_general($wp,$LimitOP,$lx,$i,$addSource,$wupin_id_s)?></tr><?php
			}
		}
	}
	
	if(!$i){?><tr id="line"><?=wupin_formline_general($wp,$LimitOP,$lx,$i,$addSource,$wupin_id_s)?></tr><?php }?>
	</table>
	
    
    
    
	<?php if($fromtable=='yundan'){?>
		<!--运单表单-->
		<div class="xats">
		  <font class="red2">
		  &raquo; <?=$LG['yundan.form_28'];//请务必准确如实填写商品的名称、单价和数量，如您购买的是套装，数量为套装商品数量。若因您的填写错误而导致海关扣货以及目的国清关额外税费的，由您自行承担。?><br>
		  </font>
          
		<?php if($LimitOP==2){?>
           &raquo; <?=$LG['yundan.29'];//为了方便与库存数量同步更新,禁止修改物品资料,如需修改,请删除该运单并重新下单?><br>
        <?php }?>

		</div>
        
    
	<?php }elseif(!$bgForecast){?>
		<!--包裹表单-->
		<div align="right" style="padding-right:50px; padding-top:10px; padding-bottom:20px;">
			<?=$LG['wupin.total_price']?>
			<input type="hidden" id="declarevalue"/><!--没使用到，数据没保存只是显示-->
			<font class="show_price" id="lblinsureamounte" ><?=$totalPrice?></font><?=$XAsc?>
		</div>
	<?php
		}
}








//---------------------------------------------------------------------------------------------------
//列头:通用
function wupin_show($fromtable,$fromid)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $ON_wupin_type,$ON_wupin_name,$ON_wupin_brand,$ON_wupin_spec,$ON_wupin_weight,$ON_wupin_number,$ON_wupin_unit,$ON_wupin_price,$ON_wupin_total;
	if(!$rs){$rs=array();}//必须
	
	//读取该运单所用资料库
	if($fromtable=='yundan')
	{
		$yd=FeData('yundan','warehouse,channel',"ydid='{$fromid}'");
		$customs=channelPar(spr($yd['warehouse']),spr($yd['channel']),'customs');
	}
?>
    <table width="100%" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
         <?=wupin_header_general(($customs?1:0))?>
         <th align="center"><?=$LG['yundan.list_35']//代购商品单号?></th>
   </tr>
    </thead>
    <?php
    if($fromtable&&$fromid)
    {
        $query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by goid asc,wupin_brand asc,wupin_name asc";
        $sql_wp=$xingao->query($query_wp);
        while($wp=$sql_wp->fetch_array())
        {
			//读取资料库
			if($customs&&$wp['gdid']){$gd=FeData($customs,'gdid,barcode',"gdid='{$wp['gdid']}'");}
        ?>
        <tr>
            <input name="gdid[]" type="hidden" value="<?=cadd($wp['gdid'])?>" />
            <?php if($ON_wupin_type){?><td align="center"><?=is_numeric($wp['wupin_type'])?classify($wp['wupin_type'],2):cadd($wp['wupin_type'])?></td><?php }?>
            <?php if($customs){?><td align="center"><?=cadd($gd['barcode'])?></td><?php }?>
            <?php if($ON_wupin_name){?><td align="center"><?=cadd($wp['wupin_name'])?></td><?php }?>
            <?php if($ON_wupin_brand){?><td align="center"><?=cadd($wp['wupin_brand'])?></td><?php }?>
            <?php if($ON_wupin_spec){?><td align="center"><?=cadd($wp['wupin_spec'])?></td><?php }?>
            <?php if($ON_wupin_weight){?><td align="center"><?=spr($wp['wupin_weight'])?></td><?php }?>
            <?php if($ON_wupin_number){?><td align="center"><?=spr($wp['wupin_number'])?></td><?php }?>
            <?php if($ON_wupin_unit){?><td align="center"><?=is_numeric($wp['wupin_unit'])?classify($wp['wupin_unit'],2):cadd($wp['wupin_unit'])?></td><?php }?>
            <?php if($ON_wupin_price){?><td align="center"><?=spr($wp['wupin_price'])?></td><?php }?>
            <?php if($ON_wupin_total){?><td align="center"><?=spr($wp['wupin_total'])?></td><?php }?>
            <td align="center">
           	   <a href="../daigou/show.php?dgid=<?=spr($gd['dgid'])?>" target="_blank"><?=cadd($gd['godh'])?></a>
            </td>
        </tr>
        <?php
        }
    }
    ?>
    </table>
<?php
}











//---------------------------------------------------------------------------------------------------
//列头:通用
/*
	$barcode 是否显示条码字段
*/
function wupin_header_general($barcode=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $ON_wupin_type,$ON_wupin_name,$ON_wupin_brand,$ON_wupin_spec,$ON_wupin_weight,$ON_wupin_number,$ON_wupin_unit,$ON_wupin_price,$ON_wupin_total;
?>
	<?php if($ON_wupin_type){?><th align="center" class="title"><?=$LG['wupin.type']?></th><?php }?>
    <?php if($barcode){?><th align="center" class="title"><?=$LG['wupin.barcode']?></th><?php }?>
    <?php if($ON_wupin_name){?><th align="center" class="title"><?=$LG['wupin.name']?></th><?php }?>
    <?php if($ON_wupin_brand){?><th align="center" class="title"><?=$LG['wupin.brand']?></th><?php }?>
    <?php if($ON_wupin_spec){?><th align="center" class="title"><?=$LG['wupin.spec']?></th><?php }?>
    <?php if($ON_wupin_weight){?><th align="center" class="title"><?=$LG['wupin.weight']?> <?="(".$XAwt.")"?></th><?php }?>
    <?php if($ON_wupin_number){?><th align="center" class="title"><?=$LG['wupin.number']?></th><?php }?>
    <?php if($ON_wupin_unit){?><th align="center" class="title"><?=$LG['wupin.unit']?></th><?php }?>
    <?php if($ON_wupin_price){?><th align="center" class="title"><?=$LG['wupin.price']?> <?="(".$XAsc.")"?></th><?php }?>
    <?php if($ON_wupin_total){?><th align="center" class="title"><?=$LG['wupin.total']?> <?="(".$XAsc.")"?></th><?php }?>
<?php
}





//---------------------------------------------------------------------------------------------------
//表单行:通用
/*
	$LimitOP=0 不限制操作:显示添加行,删除行
	$LimitOP=1 限制操作:不显示添加行,删除行
	$LimitOP=2 禁止修改数量
*/
function wupin_formline_general($wp='',$LimitOP=0,$lx='',$i='',$addSource='',$wupin_id_s='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $ON_wupin_type,$ON_wupin_name,$ON_wupin_brand,$ON_wupin_spec,$ON_wupin_weight,$ON_wupin_number,$ON_wupin_unit,$ON_wupin_price,$ON_wupin_total;
	global $wupin_req_type,$wupin_req_name,$wupin_req_brand,$wupin_req_spec,$wupin_req_weight,$wupin_req_number,$wupin_req_unit,$wupin_req_price,$wupin_req_total;
	
	if(!$wp){$wp=array();}//必须
?>
    <input name="noCheck[]" id="noCheck" type="hidden" value="0"/>

	<?php if($ON_wupin_type){?>
    <td align="center">
    <select name="wupin_type<?=$wupin_id_s?>[]" id="wupin_type" style="width:80px;" <?=$wupin_req_type?'class="input_txt_red" required':'class="input_txt_gray"'?>>
    <?php ClassifyAll(4,$wp['wupin_type'])?>
	</select>
    </td>
	<?php }?>
    	
	<?php if($ON_wupin_name){?>	
	<td align="center"><!--注意:品名是判断该行是否有内容，所以必需为必填字段，如果改为其他字段，对应程序也要改-->
		<input name="wupin_name<?=$wupin_id_s?>[]"  id="wupin_name" type="text"  style="width:120px;" 
		<?=$wupin_req_name?' class="input_txt_red" required ':' class="input_txt_gray" '?>  
        value="<?=cadd($wp['wupin_name'])?>"
        title="<?=$LG['wupin.6']?>"
        />
        
        </td>
	<?php }?>
    	
		
	<?php if($ON_wupin_brand){?>	
	<td align="center">
    <input name="wupin_brand<?=$wupin_id_s?>[]" id="wupin_brand"  type="text"  style="width:120px;" 
	<?=$wupin_req_brand?' class="input_txt_red" required ':' class="input_txt_gray" '?>  
    <?=$LimitOP&&$wp['wupin_brand']?' readonly ':''//限制操作?> 
    value="<?=cadd($wp['wupin_brand'])?>" 
    title="<?=$LG['wupin.7']?>"
    />
    </td>
	<?php }?>
    	
	
	<?php if($ON_wupin_spec){?>
	<td align="center"><input name="wupin_spec<?=$wupin_id_s?>[]" id="wupin_spec"  type="text"  style="width:40px;" <?=$wupin_req_spec?'class="input_txt_red" required':'class="input_txt_gray"'?> title="<?=$LG['wupin.1']?>" value="<?=cadd($wp['wupin_spec'])?>"/></td>
	<?php }?>
    	
	
	<?php if($ON_wupin_weight){?>
	<td align="center"><input name="wupin_weight<?=$wupin_id_s?>[]" id="wupin_weight"  type="text"  style="width:40px;" 
	<?=$wupin_req_weight?' class="input_txt_red" required ':' class="input_txt_gray" '?> 
    <?=$LimitOP&&$wp['wupin_weight']>0?' readonly ':''//限制操作?> 
    
    title="<?=$LG['wupin.2']?>" value="<?=spr($wp['wupin_weight'],2,0)?>"/></td>
	<?php }?>
    	
	
	<?php if($ON_wupin_number){?>
	<td align="center"><!--注意，不能有<div align="center">，否则无法计算-->
        <input name="wupin_number<?=$wupin_id_s?>[]" id="productNum" type="text" style="width:40px;" 
        onafterpaste="value=value.replace(/[^0-9]/g,'');" 
        onKeyUp="value=value.replace(/[^0-9]/g,''); limitNumber(this,'1,<?=$wp['wupin_number_max']>0?$wp['wupin_number_max']:'10000'?>',0); CalcTotalPrice(this);CalcDeclareValue(<?=$LimitOP?>)" 
        <?=$wupin_req_number?' class="input_txt_red" required ':' class="input_txt_gray" '?>  
     	<?=$LimitOP==2?' readonly ':''//限制操作?> 
       value="<?=spr($wp['wupin_number'],2,0)?>"/></td>
 	<?php }?>
    
       
    <?php if($ON_wupin_unit){?>    
	<td align="center">
    <select name="wupin_unit<?=$wupin_id_s?>[]" id="wupin_unit" style="width:60px;" <?=$wupin_req_unit?'class="input_txt_red" required':'class="input_txt_gray"'?>>
    <?php ClassifyAll(5,$wp['wupin_unit'])?>
	</select>
    </td>
	<?php }?>
    	
		
	<?php if($ON_wupin_price){?>	
	<td align="center"><!--注意，不能有<div align="center">，否则无法计算-->
		<input name="wupin_price<?=$wupin_id_s?>[]" id="productPrice" type="text" style="width:50px;"
        onafterpaste="value=value.replace(/[^0-9\.]/g,'');" 
        onKeyUp="value=value.replace(/[^0-9\.]/g,'');CalcTotalPrice(this);CalcDeclareValue(<?=$LimitOP?>);" 
        <?=$wupin_req_price?' class="input_txt_red" required ':' class="input_txt_gray" '?> 
        <?=$LimitOP&&$wp['wupin_price']>0?' readonly ':''//限制操作?> 
        
        title="<?=LGtag($LG['wupin.3'],
	'<tag1>=='.$XAsc.'||'.
	'<tag2>=='.$XAsc
 );?>" value="<?=spr($wp['wupin_price'],2,0)?>"/></td>
	<?php }?>
    
    
		
	<?php if($ON_wupin_total){?>	
	<td align="center"><!--注意，不能有<div align="center">，否则无法计算-->
		<input name="wupin_total<?=$wupin_id_s?>[]" id="productTotalPrice"   type="text" style="width:50px;"
        onafterpaste="value=value.replace(/[^0-9\.]/g,'');" 
        onKeyUp="value=value.replace(/[^0-9\.]/g,'');CalcDeclareValue(<?=$LimitOP?>);" 
        <?=$wupin_req_total?'class="input_txt_red" required':'class="input_txt_gray" readonly'?> 
        <?=$LimitOP&&$wp['wupin_total']>0?' readonly ':''//限制操作?> 
        
         title="<?=LGtag($LG['wupin.3'],
	'<tag1>=='.$XAsc.'||'.
	'<tag2>=='.$XAsc
 );?>"  value="<?=spr($wp['wupin_total'],2,0)?>"/>
   </td>
	<?php }?>
		
        
        
        
	<td align="center">
    <?php if(!$LimitOP){?>
        <a href=" javascript:void(0)" onclick="addProductDetail(this)" class="red2" title="<?=$LG['wupin.4']?>"><i class="icon-plus"></i></a> <br/>
        <a href="javascript:void(0)" name="deleteHref" onclick="delProductDetail(this)"  class="red2"  title="<?=$LG['wupin.5']?>"><i class="icon-minus"></i></a>
       <!--原修改时也不能删除物品:<?php if($lx=='edit'){?> style="display: none"<?php }?>-->
       
       <!--//客户要求:去掉一定要添加物品和地址的限制
	   <?php if($i<=1||have($addSource,'1,7')){?> style="display: none" <?php }?>-->
       
    <?php }elseif($wp['wupin_dh']){?>
   		 <?=cadd($wp['wupin_dh'])?>
    <?php }?>
	</td>
<?php
}














//---------------------------------------------------------------------------------------------------
//表单行:商品资料库
/*
	$typ='add'
	$typ='edit'
	
	$LimitOP=0 不限制操作:显示添加行,删除行
	$LimitOP=1 限制操作:不显示添加行,删除行
	$LimitOP=2 禁止修改数量
	
	$barcode 是否显示条码字段
*/
function wupin_form_gd($wp='',$typ='add',$LimitOP=0,$barcode=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $ON_wupin_type,$ON_wupin_name,$ON_wupin_brand,$ON_wupin_spec,$ON_wupin_weight,$ON_wupin_number,$ON_wupin_unit,$ON_wupin_price,$ON_wupin_total;
	global $Muserid;
	if(!$wp){$wp=array();}//必须
?>
  <input name="noCheck[]" id="noCheck" type="hidden" value="<?=$typ=='add'&&!$LimitOP?1:0?>"/>
  <input name="gdid[]"  id="gdid" type="hidden" value="<?=cadd($wp['gdid'])?>" />
  <input name="record[]"  id="record" type="hidden" value="<?=cadd($wp['record'])?>" />
  
  <?php if($ON_wupin_type){?>
  <td align="center">
    <input name="wupin_type[]"  id="wupin_type" type="hidden"  style="width:80px;" value="<?=cadd($wp['types'].$wp['wupin_type'])?>" readonly/><?=cadd($wp['types'].$wp['wupin_type'])?>
     <?=$wp['record']==1?Record($wp['record']):''?>
  </td>
  <?php }?>
         
    <?php if($barcode){?>	
    <td align="center"><?=cadd($wp['barcode'])?></td>
    <?php }?>
       
    <?php if($ON_wupin_name){?>	
    <td align="center">
     <input name="wupin_name[]" id="wupin_name" type="hidden"  style="width:120px;" value="<?=cadd($wp['name'].$wp['wupin_name'])?>" readonly/><?=cadd($wp['name'].$wp['wupin_name'])?>
   </td>
    <?php }?>
        
        
    <?php if($ON_wupin_brand){?>	
  <td align="center"><input name="wupin_brand[]" id="wupin_brand"  type="hidden"  style="width:120px;"  value="<?=cadd($wp['brand'].$wp['wupin_brand'])?>" readonly/><?=cadd($wp['brand'].$wp['wupin_brand'])?></td>
    <?php }?>
        
    
    <?php if($ON_wupin_spec){?>
    <td align="center"><input name="wupin_spec[]" id="wupin_spec"  type="hidden"  style="width:80px;"  value="<?=cadd($wp['spec'].$wp['wupin_spec'])?>" readonly/><?=cadd($wp['spec'].$wp['wupin_spec'])?></td>
    <?php }?>
        
    
    <?php if($ON_wupin_weight){?>
    <td align="center"><input name="wupin_weight[]" id="wupin_weight"  type="hidden"  style="width:50px;"  value="<?=spr($wp['weightGross'].$wp['wupin_weight'],2,0)?>" readonly/><?=spr($wp['weightGross'].$wp['wupin_weight'],2,0)?></td>
    <?php }?>
        
    
    <?php if($ON_wupin_number){?>
    <td align="center"><!--注意，不能有<div align="center">，否则无法计算-->
    <input name="wupin_number[]" id="productNum" type="text" style="width:40px;"
        onafterpaste="value=value.replace(/[^0-9]/g,'');" 
        onKeyUp="value=value.replace(/[^0-9]/g,''); limitNumber(this,'1,<?=$wp['wupin_number_max']>0?$wp['wupin_number_max']:'10000'?>',0); CalcTotalPrice(this);CalcDeclareValue(<?=$LimitOP?>)"  
        class="<?=$typ=='add'?' input_txt_gray ':' input_txt_red '?>"  
        <?=$LimitOP==2?' readonly ':''//禁止修改?> 
        <?=$typ=='add'?'':' required '?>  
    value="<?=spr($wp['wupin_number'],2,0)?>"/>
    </td>
    <?php }?>
        
       
    <?php if($ON_wupin_unit){?>    
    <td align="center">
    <input name="wupin_unit[]" id="wupin_unit"  type="hidden"  style="width:40px;"  value="<?=cadd($wp['unit'].$wp['wupin_unit'])?>" readonly/><?=cadd($wp['unit'].$wp['wupin_unit'])?>
    </td>
    <?php }?>
        
        
    <?php if($ON_wupin_price){?>	
    <td align="center"><!--注意，不能有<div align="center">，否则无法计算-->
    <input name="wupin_price[]" id="productPrice"  type="text" style="width:50px;"
    onafterpaste="value=value.replace(/[^0-9\.]/g,'');" 
    onKeyUp="value=value.replace(/[^0-9\.]/g,'');CalcTotalPrice(this);CalcDeclareValue(<?=$LimitOP?>);" 
    class="<?=$typ=='add'?' input_txt_gray ':' input_txt_red '?>"  
	<?=$typ=='add'?'':' required '?> 
    <?=$LimitOP&&$wp['wupin_price']>0?' readonly ':''//限制操作?> 
    
   title="<?=LGtag($LG['wupin.3'],
    '<tag1>=='.$XAsc.'||'.
    '<tag2>=='.$XAsc
 );?>" value="<?=spr($wp['wupin_price'],2,0)?>"/>
 </td>
    <?php }?>
        
        
    <?php if($ON_wupin_total){?>	
  <td align="center"><!--注意，不能有<div align="center">，否则无法计算-->
    <input name="wupin_total[]" id="productTotalPrice"  type="text" style="width:50px;"  
    onafterpaste="value=value.replace(/[^0-9\.]/g,'');" 
    onKeyUp="value=value.replace(/[^0-9\.]/g,'');CalcDeclareValue(<?=$LimitOP?>);"
    value="<?=spr($wp['wupin_total'],2,0)?>" readonly />
    </td>
    <?php }?>
        
        
    <td align="left">
     <?php if(!$LimitOP){?>

		<?php if($typ=='add'){?>
        <a href="javascript:void(0)" name="addHref" onclick="moveDetail(this);addOnClick(<?=$wp['gdid']?>);UPtag('add',<?=$wp['gdid']?>);CalcDeclareValue(<?=$LimitOP?>);submit_chk('customs_weight_limit');calc();" class="red2" title="<?=$LG['wupin.4']?>"><i class="icon-ok"></i></a><!--添加-->
        <?php }?>
        
        <a href="javascript:void(0)" name="deleteHref" onclick="UPtag('del',<?=$wp['gdid']?>);delProductDetail(this);CalcDeclareValue(<?=$LimitOP?>);calc();"  style="display:<?=$typ=='add'?'none':''?>;" class="red2"  title="<?=$LG['wupin.5']?>"><i class="icon-minus"></i></a><!--删除--><!--delProductDetail内部可能执行不完,因此放最后-->
        
        <?php if($typ=='add'&&$wp['record']='1'&&$wp['userid']==$Muserid){?>
        <a href="javascript:void(0);" name="editHref" onClick="applyEdit('/xamember/gd_mosuda/form.php?lx=edit&gdid=<?=$wp['gdid']?>');" style="margin-left:10px;color: #666666;"><i class="icon-edit"></i></a><!--修改-->
        <?php }?>
        
    <?php }elseif($wp['wupin_dh']){?>
   		 <?=cadd($wp['wupin_dh'])?>
    <?php }?>
    </td>
<?php
}


















//---------------------------------------------------------------------------------------------------
//保存物品-表单
/*
$req=1 必须字段按默认配置；$req=0 全部非必填；
wupin_save('baoguo',mysqli_insert_id($xingao));
*/
function wupin_save($fromtable,$fromid,$req=1)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $_POST;
	
	//获取哪些是必须字段
	if($req)
	{
		global $wupin_req_type,$wupin_req_name,$wupin_req_brand,$wupin_req_spec,$wupin_req_price,$wupin_req_unit,$wupin_req_price,$wupin_req_total,$wupin_req_weight;
	}else{
	  $wupin_req_type='0';
	  $wupin_req_name='0';
	  $wupin_req_brand='0';
	  $wupin_req_spec='0';
	  $wupin_req_price='0';
	  $wupin_req_unit='0';
	  $wupin_req_number='0';
	  $wupin_req_total='0';
	  $wupin_req_weight='0';
	}
	
	//开始处理
	$fromtable=add($fromtable);
	$fromid=(int)$fromid;
	if($fromtable&&$fromid)
	{
		$gdid=$_POST['gdid'];
		$record=$_POST['record'];
		$noCheck=$_POST['noCheck'];

		$goid=$_POST['goid'];

		$wupin_type=$_POST['wupin_type'];
		$wupin_name=$_POST['wupin_name'];
		$wupin_brand=$_POST['wupin_brand'];
		$wupin_spec=$_POST['wupin_spec'];
		$wupin_price=$_POST['wupin_price'];
		$wupin_unit=$_POST['wupin_unit'];
		$wupin_weight=$_POST['wupin_weight'];
		$wupin_number=$_POST['wupin_number'];
		$wupin_total=$_POST['wupin_total'];
		$ok=1;
		
		wupin_del($fromtable,$fromid,0);
	
		foreach($wupin_name as $key_w=>$value_w)
		{
			if($noCheck[$key_w]){continue;}//此行设置为未选择时,则不保存 (备案物品)
			if(!$gdid[$key_w])
			{
				//通用渠道物品
				if($ok&&!$wupin_type[$key_w]&&!$wupin_name[$key_w]&&!$wupin_brand[$key_w]&&!$wupin_spec[$key_w]&&!$wupin_price[$key_w]&&!$wupin_unit[$key_w]&&!$wupin_weight[$key_w]&&!$wupin_number[$key_w]&&!$wupin_total[$key_w]){$ok=0;}
				if($ok&&$wupin_req_type&&!trim($wupin_type[$key_w])){$ok=0;}
				if($ok&&$wupin_req_name&&!trim($wupin_name[$key_w])){$ok=0;}
				if($ok&&$wupin_req_brand&&!trim($wupin_brand[$key_w])){$ok=0;}
				if($ok&&$wupin_req_spec&&!trim($wupin_spec[$key_w])){$ok=0;}
				if($ok&&$wupin_req_price&&!trim($wupin_price[$key_w])){$ok=0;}
				if($ok&&$wupin_req_unit&&!trim($wupin_unit[$key_w])){$ok=0;}
				if($ok&&$wupin_req_weight&&!trim($wupin_weight[$key_w])){$ok=0;}
				if($ok&&$wupin_req_number&&!trim($wupin_number[$key_w])){$ok=0;}
				if($ok&&$wupin_req_total&&!trim($wupin_total[$key_w])){$ok=0;}
			}else{
				//有备案物品时
				$ok=1;
			}
			
			if($ok)
			{
				$xingao->query("insert into wupin (
					fromtable,fromid,gdid,goid,record,
					wupin_type,wupin_name,wupin_brand,wupin_spec,wupin_price,wupin_unit,wupin_weight,wupin_number,wupin_total
					
				) values (
					'".add($fromtable)."','".spr($fromid)."','".spr($gdid[$key_w])."','".spr($goid[$key_w])."','".spr($record[$key_w])."',
					'".add($wupin_type[$key_w])."','".add($wupin_name[$key_w])."','".add($wupin_brand[$key_w])."','".add($wupin_spec[$key_w])."','".spr($wupin_price[$key_w])."','".add($wupin_unit[$key_w])."','".spr($wupin_weight[$key_w])."','".spr($wupin_number[$key_w])."','".spr($wupin_total[$key_w])."'
				)");
				SQLError('保存物品');
			}
		}
	}
}

//保存物品-导入 (原物品多值字段形式保存为数据库形式,API用到)
/*
新版物品数据升级也可以使用
wupin_save_import('yundan',mysqli_insert_id($xingao),$strs[4]);
*/
function wupin_save_import($fromtable,$fromid,$wupin)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $wupin_req_type,$wupin_req_name,$wupin_req_brand,$wupin_req_spec,$wupin_req_price,$wupin_req_unit,$wupin_req_price,$wupin_req_total,$wupin_req_weight;
	
	$fromtable=add($fromtable);
	$fromid=(int)$fromid;
	if($fromtable&&$fromid&&$wupin)
	{
			
			$mvf_record=explode('|||',$wupin);
			$mvf_count=count($mvf_record);
			for($i=0;$i<$mvf_count;$i++)
			{
				$ok=1;
				$mvf_field=explode(':::',$mvf_record[$i]);
	
				if($ok&&!$mvf_field[0]&&!$mvf_field[1]&&!$mvf_field[2]&&!$mvf_field[3]&&!$mvf_field[4]&&!$mvf_field[5]&&!$mvf_field[6]&&!$mvf_field[7]&&!$mvf_field[8]){$ok=0;}
				if($ok&&$wupin_req_type&&!trim($mvf_field[0])){$ok=0;}
				if($ok&&$wupin_req_name&&!trim($mvf_field[1])){$ok=0;}
				if($ok&&$wupin_req_brand&&!trim($mvf_field[2])){$ok=0;}
				if($ok&&$wupin_req_spec&&!trim($mvf_field[3])){$ok=0;}
				if($ok&&$wupin_req_price&&!trim($mvf_field[4])){$ok=0;}
				if($ok&&$wupin_req_unit&&!trim($mvf_field[5])){$ok=0;}
				if($ok&&$wupin_req_weight&&!trim($mvf_field[8])){$ok=0;}
				if($ok&&$wupin_req_number&&!trim($mvf_field[6])){$ok=0;}
				if($ok&&$wupin_req_total&&!trim($mvf_field[7])){$ok=0;}
			
			if($ok)
			{
				$xingao->query("insert into wupin (
					fromtable,fromid,
					wupin_type,wupin_name,wupin_brand,wupin_spec,wupin_price,wupin_unit,wupin_weight,wupin_number,wupin_total
				) values (
					'".add($fromtable)."','".spr($fromid)."',
					'".add($mvf_field[0])."','".add($mvf_field[1])."','".add($mvf_field[2])."','".add($mvf_field[3])."','".add($mvf_field[4])."','".add($mvf_field[5])."','".spr($mvf_field[8])."','".spr($mvf_field[6])."','".spr($mvf_field[7])."'
				)");
				SQLError('保存物品');
			}
		}
	}
}


//从数据库获取物品,并生成多字段内容
function wupin_morefield_sql($fromtable,$fromid)
{
	if($fromtable&&$fromid)
	{
		require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
		
		$query="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_brand asc,wupin_name asc";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			//A类别1:::品名2:::品牌厂商3:::规格4:::单价5:::单位6:::数量7:::总价8:::重量
			//一行物品里的字段
			$wupin_field=$rs['wupin_type'].':::'.$rs['wupin_name'].':::'.$rs['wupin_brand'].':::'.$rs['wupin_spec'].':::'.$rs['wupin_price'].':::'.$rs['wupin_unit'].':::'.$rs['wupin_number'].':::'.$rs['wupin_total'].':::'.$rs['wupin_weight'];
			
			//一个包裹里的物品
			if($wupin){$wupin.='|||'.$wupin_field;}else{$wupin=$wupin_field;}
		}
		return cadd($wupin);
	}
}


//从表单获取物品,并生成多字段内容  (导入时是多字段内容,运单验证总价,生成描述等也是用多字段内容)
function wupin_morefield()
{
	global $_POST;
	$wupin_type=$_POST['wupin_type'];
	$wupin_name=$_POST['wupin_name'];
	$wupin_brand=$_POST['wupin_brand'];
	$wupin_spec=$_POST['wupin_spec'];
	$wupin_price=$_POST['wupin_price'];
	$wupin_unit=$_POST['wupin_unit'];
	$wupin_weight=$_POST['wupin_weight'];
	$wupin_number=$_POST['wupin_number'];
	$wupin_total=$_POST['wupin_total'];
	$noCheck=$_POST['noCheck'];
	
	
	$wupin_field='';
	$wupin='';
	
	//$wupin_1是用品名字段，用类别判断该行是否有内容
	foreach($wupin_name as $key_w=>$value_w)
	{
		if($noCheck[$key_w]){continue;}//此行设置为未选择时,则不保存 (备案物品)
		
		//A类别1:::品名2:::品牌厂商3:::规格4:::单价5:::单位6:::数量7:::总价8:::重量
		//一行物品里的字段
		$wupin_field=$wupin_type[$key_w].':::'.$wupin_name[$key_w].':::'.$wupin_brand[$key_w].':::'.$wupin_spec[$key_w].':::'.$wupin_price[$key_w].':::'.$wupin_unit[$key_w].':::'.$wupin_number[$key_w].':::'.$wupin_total[$key_w].':::'.$wupin_weight[$key_w];
		
		//一个包裹里的物品
		if($wupin){$wupin.='|||'.$wupin_field;}else{$wupin=$wupin_field;}
	}
	return add($wupin);
}

//删除物品
function wupin_del($fromtable,$fromid,$fx_del=1)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($fromtable&&$fromid)
	{
		$xingao->query("delete from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' ");
		SQLError('删除物品');
		
		if($fromtable=='baoguo'&&$fx_del)
		{
			$xingao->query("delete from wupin where fromtable like '%{$fromid}%'  ");
			SQLError('删除未提交临时分箱申请的物品');
		}
	}
}

//验证是否有填写物品
function wupin_yz()
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $_POST;

	$noCheck=$_POST['noCheck'];

	$wupin_type=$_POST['wupin_type'];
	$wupin_name=$_POST['wupin_name'];
	$wupin_brand=$_POST['wupin_brand'];
	$wupin_spec=$_POST['wupin_spec'];
	$wupin_price=$_POST['wupin_price'];
	$wupin_unit=$_POST['wupin_unit'];
	$wupin_weight=$_POST['wupin_weight'];
	$wupin_number=$_POST['wupin_number'];
	$wupin_total=$_POST['wupin_total'];
	$wupin_field='';$wupin='';
	
	if($wupin_name&&is_array($wupin_name)){
		foreach($wupin_name as $key_w=>$value_w)
		{
			if($noCheck[$key_w]){continue;}//此行设置为未选择时,则不保存 (备案物品)
			
			$wupin_field=$wupin_type[$key_w].$wupin_name[$key_w].$wupin_brand[$key_w].$wupin_spec[$key_w].$wupin_price[$key_w].$wupin_unit[$key_w].$wupin_weight[$key_w].$wupin_number[$key_w].$wupin_total[$key_w];
			
			$wupin.=$wupin_field;
			$wupin_weight_total+=$wupin_weight[$key_w]*$wupin_number[$key_w];
		}
	}
	
	//无物品-------------------
	if(!str_ireplace(' ','', $wupin)){return $LG['yundan.save_10'];}
	
	
	//其他验证-------------------
	//物品重量已经超过该渠道限重!
	
	$weightUnit=$XAwt;
	$limit=channelPar($_POST['warehouse'],$_POST['channel'],'customs_weight_limit');
	$customs=channelPar($_POST['warehouse'],$_POST['channel'],'customs');
	if($customs=='gd_mosuda'){$weightUnit='KG';		$wupin_weight_total*=$XAwtkg;}//该商品资料所用重量单位
	if($limit>0&&$wupin_weight_total>$limit){return LGtag($LG['yundan.26'],'<tag1>=='.$limit.$weightUnit);}
	
}




//物品合并处理程序
//----------------------------------------------------------------------------------------

/*
	$zheng=0 通用更新:总价,重量
	$zheng=1 整合一起:把$fromtable_new整合到$fromtable 有重复则加数量,无重复则新加
	$zheng=2 重复的只相加,不新加:$fromtable(处理)+$fromtable_new
	$zheng=3 重复的只相减,不新加:$fromtable(处理)-$fromtable_new
	
	$zheng=4 单整合重复的$fromtable,重复则加数量,不新加
	$zheng=5 把数量为0的删除($fromtable)
	-----------------------------------------
	$fromid,$fromid_new 支持字符串多ID
*/

function wupin_run($zheng,$fromtable,$fromid,$fromtable_new='',$fromid_new='')
{    
	if(!$fromtable||!$fromid){return '';}
	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$where="fromtable='{$fromtable}' and fromid in ({$fromid})";
	$xingao->query("update wupin set tem_zheng='0' where {$where}");//标记为未整合过
	
	if($fromtable_new&&$fromid_new)
	{
		$where_new="fromtable='{$fromtable_new}' and fromid in ({$fromid_new})";
		$xingao->query("update wupin set tem_zheng='0' where {$where_new}");//标记为未整合过
	}



	
	/*	
		$zheng=1 整合一起:把$fromtable_new整合到$fromtable 有重复则加数量,无重复则新加
		$zheng=2 重复的只相加,不新加:$fromtable(处理)+$fromtable_new
		$zheng=3 重复的只相减,不新加:$fromtable(处理)-$fromtable_new
	*/
	if($zheng==1||$zheng==2||$zheng==3)
	{
		//有重复则整合-开始------------------------------------------

		//检查是否重复
		$query_wp="select * from wupin where {$where}";
		$sql_wp=$xingao->query($query_wp);
		while($wp=$sql_wp->fetch_array())
		{
			$query_new="select * from wupin where tem_zheng='0' and {$where_new}";
			$sql_new=$xingao->query($query_new);
			while($new=$sql_new->fetch_array())
			{
				$rep=wupin_rep_yz($wp,$new);
				if($rep)
				{
					//更新重复物品
					if($zheng==1||$zheng==2)
					{
						$xingao->query("update wupin set 
						wupin_weight=wupin_weight+".$new['wupin_weight'].",
						wupin_number=wupin_number+".$new['wupin_number']."
						where fromtable='{$wp[fromtable]}' and fromid='{$wp[fromid]}'");
						SQLError('整合-重复物品更新-加');
					}elseif($zheng==3){ 
						$xingao->query("update wupin set 
						wupin_weight=wupin_weight-".$new['wupin_weight'].",
						wupin_number=wupin_number-".$new['wupin_number']."
						where fromtable='{$wp[fromtable]}' and fromid='{$wp[fromid]}'");
						SQLError('整合-重复物品更新-减');
					}
					
					
					//标记为已整合
					$xingao->query("update wupin set tem_zheng='1' where wpid='{$new[wpid]}'");
				}
			}
		}
		//有重复则整合-结束------------------------------------------
		
		//无重复则添加-开始------------------------------------------
		if($zheng==1)
		{
			$query_new="select * from wupin where tem_zheng='0' and {$where_new}";
			$sql_new=$xingao->query($query_new);
			while($new=$sql_new->fetch_array())
			{
				$new['fromtable']=$fromtable;
				$new['fromid']=$fromid;
				$savelx='add';//调用类型(add,edit,cache)
				$getlx='SQL';//获取类型(POST,GET,REQUEST,SQL)
				$alone='wpid';//不处理的字段
				$digital='';//数字字段
				$radio='';//单选、复选、空文本、数组字段
				$textarea='';//过滤不安全的HTML代码
				$date='';//日期格式转数字
				$save=XingAoSave($new,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date,1);
				$xingao->query("insert into wupin (".$save['field'].") values (".$save['value'].")");
				SQLError('整合-添加物品');
			}
		}
		//无重复则添加-结束------------------------------------------
	}





	
	//$zheng=4 单整合重复的$fromtable,重复则加数量,不新加===============================================
	if($zheng==4)
	{
		//检查是否重复
		$query_wp="select * from wupin where tem_zheng='0' and {$where}";
		$sql_wp=$xingao->query($query_wp);
		while($wp=$sql_wp->fetch_array())
		{
			$wpid=FeData('wupin','wpid',"tem_zheng='0' and wpid='{$wp[wpid]}'");
			if($wpid)
			{
				//标记为已整合
				$xingao->query("update wupin set tem_zheng='1' where wpid='{$wp[wpid]}'");
				
				$query_new="select * from wupin where tem_zheng='0' and {$where}";
				$sql_new=$xingao->query($query_new);
				while($new=$sql_new->fetch_array())
				{
					$rep=wupin_rep_yz($wp,$new);
					if($rep)
					{
						//更新重复物品
						$xingao->query("update wupin set 
						wupin_weight=wupin_weight+".$new['wupin_weight'].",
						wupin_number=wupin_number+".$new['wupin_number']."
						where wpid='{$wp[wpid]}'");
						SQLError('整合-重复物品更新');
						
						//整合过删除
						$xingao->query("delete from wupin where wpid='{$new[wpid]}'");SQLError('整合-整合过删除');
					}
				}
			}
		}
	}




	//$zheng=5 把数量为0的删除($fromtable)============================================================
	if($zheng==5)
	{
		$xingao->query("delete from wupin where  wupin_number<='0' and {$where}");SQLError('整合-把数量为0的删除');
	}



	
	//全部更新============================================================
	//更新总价
	$xingao->query("update wupin set wupin_total=wupin_number*wupin_price where {$where} ");
	SQLError('整合-更新总价');
	
	//更新0数量
	$xingao->query("update wupin set wupin_number=0 where {$where} and wupin_number<0 ");
	SQLError('整合-更新0数量');
	
	//更新0重量
	$xingao->query("update wupin set wupin_weight=0 where {$where} and wupin_weight<0 ");
	SQLError('整合-更新0重量');
}

//验证物品是否重复  
function wupin_rep_yz($wp,$new)
{    
	$rep=1;
	if($rep&&$wp['wupin_type']!=$new['wupin_type']){$rep=0;}
	if($rep&&$wp['wupin_name']!=$new['wupin_name']){$rep=0;}
	if($rep&&$wp['wupin_brand']!=$new['wupin_brand']){$rep=0;}
	if($rep&&$wp['wupin_spec']!=$new['wupin_spec']){$rep=0;}
	if($rep&&$wp['wupin_unit']!=$new['wupin_unit']){$rep=0;}
	if($rep&&$wp['wupin_price']!=$new['wupin_price']){$rep=0;}
	return $rep;
}


//替换过滤物品非法字符 (只在分箱的时候先处理,有特殊字符无法物品处理)
function wupin_rep($wupin)    
{    
	//$wupin=str_replace("*"," ",$wupin);
	//$wupin=str_replace("#"," ",$wupin);
	$wupin=str_replace("=","＝",$wupin);
	$wupin=str_replace("/","｜",$wupin);
	//$wupin=str_replace("|","｜",$wupin);//要用到
	$wupin=str_replace("[","（",$wupin);
	$wupin=str_replace("]","）",$wupin);
	$wupin=str_replace("{","（",$wupin);
	$wupin=str_replace("}","）",$wupin);
	$wupin=str_replace(")","）",$wupin);
	$wupin=str_replace("(","（",$wupin);
	$wupin=str_replace("?","？",$wupin);
	$wupin=str_replace("<","（",$wupin);
	$wupin=str_replace(">","）",$wupin);
	$wupin=str_replace("","’",$wupin);
	$wupin=str_replace("'","’",$wupin);
	$wupin=str_replace('"',"”",$wupin);
	//$wupin=str_replace(",","，",$wupin);
	//$wupin=str_replace(".","。",$wupin);//小数点要用
	$wupin=trim($wupin);
	return $wupin; 
}



?>