<form action="/mall/cart.php" method="post" name="xingao">
  <input name="lx" type="hidden">
  <input name="mlid" type="hidden" value="<?=$rs['mlid']?>">
  <?php if(!$callFrom_m=='m'){?>
  <div class="names">
	<h1 class="name"><?=cadd($rs['title'.$LT])?></h1>
	<?php 
	if($rs['ensure']){echo '<span class="label label-sm label-danger">'.$LG['front.100'].'</span>';}
	?>
	<div class="clearss"></div>
	<?php 
	if(cadd($rs['selling'.$LT])){echo '<div class="fbt_zx"><span>★</span>'.cadd($rs['selling'.$LT]).'</div>';}
	?>
  </div>
  <?php }?>
  
  <p class="bottom_bg"></p>
  <ul>
	<div class="youjia">
	  <li class="goodsattr"> <?=$LG['front.101']?>
      <b class="shp" id="ECS_SHOPPRICE"><?=spr($rs['price'])?></b>
	   <?=$XAmc?>
	   <?=$XAMcurrency!=$XAScurrency?' (<b>≈'.spr($rs['price']*exchange($XAMcurrency,$XAScurrency)).$XAsc.'</b>)':''?>
         
	   <?php if(spr($rs['price_other'])>0) {?>
		&nbsp;&nbsp; 
	  <font title="<?=$LG['front.103']?>&#13<?=$LG['front.104'].cadd($rs['price_otherwhy'])?>">
	  <?=$LG['front.105']//其他收费：?><font class="red"><?=spr($rs['price_other'])?></font>
		<?=$XAmc?>
	  </font>
	  <?php }?>
	  
	 <?php if($rs['price_market']){?>
		&nbsp;&nbsp; <?=$LG['front.106']//市场价：?><del class="mkp"><?=cadd($rs['price_market'])?></del>
     <?php }?>   
		
		</li>
<?php 
$weight=spr($rs['weight']);
$warehouse=cadd($rs["warehouse"]);

if(!is_array($warehouse)&&$warehouse){$warehouse_arr=explode(",",$warehouse);}//转数组
if(!$warehouse_arr[0]){$warehouse_arr[0]=FeData('warehouse','whid',"checked='1' order by myorder desc,whid desc" );}
?>             
	  <li class="goodsattr"> <?=$LG['front.107']?><?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?></li>
      <li class="goodsattr">
        <table>
          <tr>
            <td valign="top"><?=$LG['front.108']?>：</td>
            <td valign="top"><font id="ReferFreight"><?=$LG['front.109']//请先选择存放仓库和收件国家?></font></td>
          </tr>
        </table>
      </li>
      
      
<script>
function ReferFreight() {
	var country=$('[name="country"]')[0].value;
	var warehouse=$('[name="warehouse"]')[0].value;
	if(!country||!warehouse){return;}
	
	$.ajax({
        type: "POST",
        cache: false,
        data: 'lx=ReferFreight&country='+country+'&warehouse='+warehouse+'&weight=<?=$rs['weight']?>',
        async: false,//true导步处理;false为同步处理
        url: "/public/ajax.php",
        success: function (data) 
		{
  		    document.getElementById('ReferFreight').innerHTML= data;
		}
    });
}
</script>
      
	</div>
	<p class="bottom_bg"></p>
	<!-- ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝-->
		<li class="goodsattr"> 
		<?=$LG['yundan.country'];//寄往国家?>：
		<select name="country" class="form-control input-medium select2me" data-placeholder="<?=$LG['country'];//仓库?>" onChange="ReferFreight()">
		<?php  Country('',2);?>
		</select><a href="javascript:void(0)" title="<?=$LG['front.109_1']?>"> <i class="icon-info-sign"></i> </a>
		</li>
	<!-- ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝-->
		<li class="goodsattr"> 
		<?=$LG['front.110']?>
		<select name="warehouse" class="form-control input-medium select2me" data-placeholder="<?=$LG['warehouse'];//仓库?>" onChange="ReferFreight()">
		<?php  
		if($warehouse){warehouse('',1,$warehouse);}
		else{warehouse('',1);}
		?>
		</select>
		</li>
	<!-- ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝-->
	<?php
	$str=cadd($rs["package"]);
	if($str)
	{
	?>
		<li class="goodsattr"> 
		
	  
		<?=$LG['front.111']?>
		<select name="package" class="form-control input-medium select2me" data-placeholder="<?=$LG['front.84'];//套餐?>">
		<option value="<?=$LG['unlimited'];//不限?>" selected><?=$LG['unlimited'];//不限?></option>
		 <?=Select($str)?>
		</select>
	  
		</li>
	<?php
	}
	?>
	<!-- ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝-->
	<?php
	$str=cadd($rs["size"]);
	if($str)
	{
	?>
		<li class="goodsattr"> 
		<?=$LG['front.112']?>
		<select name="size" class="form-control input-medium select2me" data-placeholder="<?=$LG['front.85'];//尺寸?>">
		<option value="<?=$LG['unlimited'];//不限?>" selected><?=$LG['unlimited'];//不限?></option>
		 <?=Select($str)?>
		</select>
	   
		</li>
	<?php
	}
	?>

	<!-- ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝-->
	<?php
	$str=cadd($rs["color"]);
	if($str)
	{
	?>
		<li class="goodsattr"> 
		
	    <?=$LG['front.113']?>
		<select name="color" class="form-control input-medium select2me" data-placeholder="<?=$LG['front.86'];//颜色?>">
		<option value="<?=$LG['unlimited'];//不限?>" selected><?=$LG['unlimited'];//不限?></option>
		 <?=Select($str)?>
		</select>
	   
		</li>
	<?php
	}
	?>

  </ul>
  <div id="buy">
  <li class="goodsattr"> 
   <?=$LG['front.114']?> <img style="CURSOR: pointer; vertical-align:middle" onClick="setAmount.reduce('number')" href="javascript:void(0)" src="/images/minus.gif" alt="minus">
	<input name="number" type="text" id="number" value="1" size="5" onKeyUp="setAmount.yz('number')" style="text-align:center;"/>
	<img style="CURSOR: pointer; vertical-align:middle" onClick="setAmount.add('number')" href="javascript:void(0)" src="/images/plus.gif" alt="plus">
	
	 <font style="padding-left:30px;"><?=$LG['front.115']?>：<?=$rs['number']?> <?=$rs['unit']?> <font class=gray_prompt2><?=$rs['number_sold']?'('.$LG['front.116'].$rs['number_sold'].$rs['unit'].')':''?></font></font>
	<?php if($rs['number_limit']>0){echo ' <font class=gray_prompt2>('.$LG['front.117'].$rs['number_limit'].$rs['unit'].')</font>';}?>
	<font class="red" id="msg_number"></font>
	</li>
	

<?php 
$number=$rs['number'];
if($rs['number_limit']>0){$number=$rs['number_limit'];}
if($number>$rs['number']){$number=$rs['number'];}
?>

<script type="text/javascript">
var setAmount={
min:1,
reduce:function(obj){
var ele = document.getElementById(obj);
var x=parseInt(ele.value)-1;
if (x>=1){
ele.value=x;
document.getElementById('msg_number').innerHTML="";
}else{
document.getElementById('msg_number').innerHTML="<br><?=$LG['front.118']?>";
ele.value=1;
}

},
add:function(obj){
var ele = document.getElementById(obj);
var x=parseInt(ele.value)+1;

if (x<<?=$number?>+1){
ele.value=x;
document.getElementById('msg_number').innerHTML="";
}else{
document.getElementById('msg_number').innerHTML="<br><?=$LG['front.119']?>";
ele.value=<?=$number?>;
}

},
yz:function(obj){
var ele = document.getElementById(obj);
var x=parseInt(ele.value);

if (x><?=$number?>||x<1)
{
document.getElementById('msg_number').innerHTML="<br><?=LGtag($LG['front.120'],'<tag1>=='.$number)?>";
ele.value=1;
}else
{
document.getElementById('msg_number').innerHTML="";
}

}

}
</script>
	
	<?php if($off_integral){?>
	
	<input name="integral_to" type="hidden" value="<?=$rs['integral_to']?>">
	
	<?php if($rs['integral_use']){?>
	<li class="goodsattr"> 
	<label ><?=$LG['front.121']?></label>

	<label class="radio-inline" title="<?=$LG['front.88'];//可抵消部分费用?>">
	<input name="integral_use" type="radio" value="1" checked style="width:15px; height:15px;"/><?=$LG['front.122']?>
	</label>

	<label class="radio-inline">
	<input name="integral_use" type="radio" value="0"  style="width:15px; height:15px;"/>
	<?=$LG['front.123']?> 
	</label>  
	&nbsp;&nbsp;  
	
	<a onMouseOver="show('div1')" onMouseOut="hide('div1')">
	<font class=gray_prompt2>
	 (<?php 
	  if($rs['integral_to']==1&&$rs['integral_use']==1){ echo $LG['front.90'].$integral_mall;}
	  elseif($rs['integral_to']==1||$rs['integral_to']==2){ echo $LG['front.92'].$integral_mall;}
	  elseif(!$rs['integral_to']){ echo $LG['front.93'];}
	  ?>
	   )</font>
	</a> 
	<br>
  <div id="div1" style="display:none;padding-left:70px; width:520px; height:60px; line-height:20px; font-size:12px; padding-top:10px;"> 
  <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/integral/ts_call.php');?>
</div>
 
	
	<?php }else{?>
	 <font class=gray_prompt2>
	<?=$LG['front.124']?>
	(
	<?php if($rs['integral_to']==1&&$rs['integral_use']==1){ echo $LG['front.90'].$integral_mall;}
	  elseif($rs['integral_to']==1||$rs['integral_to']==2){ echo $LG['front.97'].$integral_mall;}
	  elseif(!$rs['integral_to']){ echo $LG['front.93'];}
	  ?>
	)
	 </font>
	<?php }//if($rs['integral_use']){ ?>
   
	</li>
	<?php }//if($off_integral&&$integral_mall>0){?>
 
	
	 <li class="goodsattr"> 
	<?=$LG['front.125']?>
	<textarea name="content" class="form-control input-xlarge"></textarea>
	 </li>
	<div class="textinfo_amoum">
	 <button type="submit" class="btn btn-warning"  id="openSmt1" disabled 
     onClick="document.xingao.lx.value='0';document.xingao.target='_blank';" ><i class="icon-shopping-cart"></i> <?=$LG['front.98'];//加 入 购 物 车?> </button>
	 
	 <button type="submit" class="btn btn-danger"   id="openSmt2" disabled 
     onClick="document.xingao.lx.value='1';document.xingao.target='_blank';" ><i class="icon-credit-card"></i> <?=$LG['front.99'];//立 即 购 买?> </button>
	</div>
	<div class="clear"></div>
	<p class="bottom_bg"></p>
  </div>
</form>