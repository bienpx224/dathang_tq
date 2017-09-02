<?php
//输出商品资料列表
/*
	在ajax.php中调用
	$lx=ajax.php的参数
*/
if ($lx=='gd_mosuda_list')
{ 	
	if(!$ON_gd_mosuda){echo $customs.$LG['pptCloseGD'];}
	
	$warehouse=spr($_POST['warehouse']);
	$channel=spr($_POST['channel']);
	$menu_key=par($_POST['menu_key']);
	$menu_producer=spr($_POST['menu_producer']);
	$menu_types=spr($_POST['menu_types']);
	$menu_brand=spr($_POST['menu_brand']);
	$menu_name=spr($_POST['menu_name']);
	$menu_unit=spr($_POST['menu_unit']);
	$tag=par($_POST['tag']);
	
	$where="checked='1' and record in (0,2)";
	
	
	
	if($menu_key){
		$where.=" and (barcode='{$menu_key}' or itemNo like '%{$menu_key}%' or HYG like '%{$menu_key}%' or taxCode like '%{$menu_key}%' or recordCode like '%{$menu_key}%' or HSCode like '%{$menu_key}%' or itemsTaxCode like '%{$menu_key}%' or name like '%{$menu_key}%' or spec like '%{$menu_key}%' or unit like '%{$menu_key}%' or types like '%{$menu_key}%' or content like '%{$menu_key}%' or merchants like '%{$menu_key}%' or brand like '%{$menu_key}%' or producer like '%{$menu_key}%' or composition like '%{$menu_key}%' or foodAdditives like '%{$menu_key}%' or harmful like '%{$menu_key}%' )";	
		$showList=1;
	}

	//下拉条件,小级优先
	if($menu_name)
	{
		$rs=FeData('gd_mosuda','gdid,producer,types,brand,name,unit',"gdid='{$menu_name}'");
		$where.=" and producer='{$rs['producer']}' and types='{$rs['types']}' and brand='{$rs['brand']}' and unit='{$rs['unit']}' and name='{$rs['name']}'";
		$showList=1;
	}elseif($menu_unit){
		$rs=FeData('gd_mosuda','gdid,producer,types,brand,unit',"gdid='{$menu_unit}'");
		$where.=" and producer='{$rs['producer']}' and types='{$rs['types']}' and brand='{$rs['brand']}' and unit='{$rs['unit']}'";
		$showList=1;
	}elseif($menu_brand){
		$rs=FeData('gd_mosuda','gdid,producer,types,brand',"gdid='{$menu_brand}'");
		$where.=" and producer='{$rs['producer']}' and types='{$rs['types']}' and brand='{$rs['brand']}'";
		$showList=1;
	}elseif($menu_types){
		$rs=FeData('gd_mosuda','gdid,producer,types',"gdid='{$menu_types}'");
		$where.=" and producer='{$rs['producer']}' and types='{$rs['types']}'";
		$showList=1;
	}elseif($menu_producer){
		$rs=FeData('gd_mosuda','gdid,producer',"gdid='{$menu_producer}'");
		$where.=" and producer='{$rs['producer']}' ";
		$showList=1;
	}

	$where="userid='{$Muserid}' or ({$where})";//放后面
	if($tag&&$_SESSION[$tag]){$where="({$where}) and gdid not in ({$_SESSION[$tag]})";}//放最后
	
	
	$customs_types_limit=channelPar($warehouse,$channel,'customs_types_limit');	
	if($customs_types_limit)
	{
		$customs_types_limit=str_ireplace(',',"','",$customs_types_limit);//中文
		$where.=" and (types in ('{$customs_types_limit}') or types='' )";
	}

	if(!$showList){goto a;}
	
	$query="select * from gd_mosuda where {$where} order by myorder desc, onclick desc,brand asc,name asc,unit asc";
	
	$ajaxName='gd_mosuda_list';
	$line=10;$page_line=15;//分页处理，不设置则默认
	include($_SERVER['DOCUMENT_ROOT'].'/public/ajaxPage.php');
	
 ?>
<style>
input{background-color:transparent;border: 0px; }
.input_red input{color:#FF6600!important;}
</style>
 
 
    <table width="100%" class="table-hover">
    <!--自动计算要加这个 id="tableProduct"-->
    
    <tr style="height:35px;">
     <?=wupin_header_general(1)?>
     <th align="center" class="title"></th>
    </tr>
    

	<?php
	while($rs=$sql->fetch_array())
	{
		$rs['weightGross']=$rs['weightGross']/$XAwtkg;//KG转网站单位
		?>	
        <!--此页无法使用:tooltips-->
         <tr  class="odd gradeX <?=$rs['record']==1?'red2':''?>" id="line" style="height:35px;">
          <?=wupin_form_gd($rs,'add',0,1)?>
         </tr>
	<?php
	}
	?>	
    </table>
    
    <!--分页-->
	<table width="100%">
	<tr>
		<td valign="middle"><div class="row"><?=$listpage?></div></td>
	</tr>
	</tbody>
    
<?php
}

a://位置
?>