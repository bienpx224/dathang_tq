<?php
//运费计算公式
$warehouse=spr($_GET['warehouse']);
$weight=spr($_GET['weight'],2,0);
$country=spr($_GET['country']);
$cc_chang=spr($_GET['cc_chang'],2,0);
$cc_kuan=spr($_GET['cc_kuan'],2,0);
$cc_gao=spr($_GET['cc_gao'],2,0);

$groupid=$Mgroupid;//当前会员组ID
if(!$groupid){$groupid=FeData('member_group','groupid',"1=1 order by checked desc,myorder desc,off_company asc");}
//不能checked=1,当后台全部关闭时,便有错误
?>

<form action="<?=$m?>/yundan/price.php" method="get" >
<?php if($index){?>
	<?php if($m){?>
        <!--首页-手机版-->
        <select name="warehouse" class="form-control select2me" style="width:100%; margin-bottom:5px;" data-placeholder="<?=$LG['yundan.warehouse'];//所在仓库?>" required onChange="country_show('<?=$groupid?>','<?=$country?>');">
            <?php warehouse($warehouse,1,0,1);?>
        </select>
        
        <?php if($ON_country){?>
            <br>
            <span id="country" style="width:100%; margin-top:5px;"></span>
		<?php }else{?>
            <input type="hidden"  name="country" value="<?=$openCountry?>">
        <?php }?>
        <br>
        <input type="text" class="form-control" name="weight" placeholder="<?=$LG['front.10']?>(<?=$XAwt?>)" required style="width:50%;"> 
        
        <button type="submit" class="btn btn-info" style="width:100%; margin-top:5px;"> <i class="icon-list-alt"></i> <?=$LG['front.9'];//计算运费?> </button>
   
   
    <?php }else{?>
    
    
   		<!--首页-电脑版-->
        <select name="warehouse" class="xa_select" required  onChange="country_show('<?=$groupid?>','<?=$country?>');" <?=!$ON_country?'style="width:320px;"':''?>>
            <?php warehouse($warehouse,1,0,1);?>
        </select>
        
        <?php if($ON_country){?>
            <span id="country"></span>
		<?php }else{?>
              <input type="hidden"  name="country" value="<?=$openCountry?>">
        <?php }?>
        
        
        <input type="hidden" name="number" value="1">
    
        <input type="text" class="xa_text2" name="weight" placeholder="<?=$LG['weight']?> <?=$XAwt?>" required > 
        <input type="submit" class="xa_btn2" value="<?=$LG['front.3'];//在线报价?>">
	<?php }?>
	
    
    
<?php }else{?>



	<!--电脑和手机  内页-->
	<table width="100%">
		<tbody>
			<tr>
				<td width="70"><?=$LG['yundan.warehouse'];//所在仓库?></td>
				<td>
					 <select name="warehouse" class="form-control select2me"  data-placeholder="<?=$LG['pleaseSelect'];//请选择?>" required onChange="country_show('<?=$groupid?>','<?=$country?>');">
					 <?php warehouse($warehouse,1,0,1);?>
					 </select>
				
				</td>
			</tr>
	
			<?php if($ON_country){?>
			<tr>
				<td><?=$LG['yundan.country'];//寄往国家?></td>
				<td>	
					<style>
                    .xa_select2 { width:100% !important;max-width:100%  !important;}
                    </style>
					<span id="country"></span>
				</td>
			</tr>
		  <?php }else{?>
          		<input type="hidden"  name="country" value="<?=$openCountry?>">
          <?php }?>
			<tr>
				<td><?=$LG['yundan.XexcelExport_3_13'];//包裹重量?></td>
				<td><input type="text" class="form-control"  name="weight" placeholder="<?=$XAwt?>"  value="<?=$weight?>" title="<?=$XAwt?>" required>
				</td>
			</tr>
<tr>
				<td><?=$LG['yundan.XexcelExport_3_13_1'];//包裹尺寸?></td>
				<td>
<?=$LG['length']?><?=$XAsz?><input name="cc_chang" type="text"  value="<?=spr($cc_chang,1,0)?>" class="form-control input-xsmall" />
<br>
<?=$LG['width']?><?=$XAsz?><input name="cc_kuan" type="text" value="<?=spr($cc_kuan,1,0)?>" class="form-control input-xsmall"/>
<br>
<?=$LG['high']?><?=$XAsz?><input name="cc_gao" type="text" value="<?=spr($cc_gao,1,0)?>" class="form-control input-xsmall"/>

                
				</td>
			</tr>
            
			<tr>
				<td colspan="2" align="center">
				<button type="submit" class="btn btn-primary"  onClick="jisuan();" style="width:100%;"> <i class="icon-list-alt"></i> <?=$LG['front.9'];//计算运费?> </button>
				</td>
				</tr>
		</tbody>
	</table>
<?php }?>
</form>





<?php 
$CountryRequired=1;//yundanJS.php 参数:国家是否必选
require_once($_SERVER['DOCUMENT_ROOT'].'/js/yundanJS.php');
?>
<script language="javascript">
	$(function(){  
		country_show('<?=$groupid?>','<?=$country?>');  //显示国家下拉
	});
</script>
