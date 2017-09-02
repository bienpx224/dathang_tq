<?php 

//处理:1125

//获取,处理
$lx=par($_GET['lx']);
$classid=(int)$_GET['classid'];
$brand_all=$brand;//获取config.php
$brand=par($_GET['brand']);
$warehouse_all=$warehouse;//获取config.php
$warehouse=par($_GET['warehouse']);

if(!$classid)
{
	$fr=mysqli_fetch_array($xingao->query("select classid from class where classtype='3' and bclassid='0' and checked='1' "));
	$classid=$fr['classid'];
}

$cr=ClassData($classid);
$headtitle=$cr['seotitle'.$LT]?cadd($cr['seotitle'.$LT]):cadd($cr['name'.$LT]);
$search.="&classid={$classid}";



//搜索
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$attr=par($_GET['attr']);

	if($key){$where.=" and (title{$LT} like '%{$key}%' or seokey{$LT} like '%{$key}%' )";}
	
	switch($attr)
	{
		case "mlid":
			$order.=" order by mlid desc";
			break;
		case "isgood"://推荐:用默认排序即可
			//$order.=" ";
			break;
		case "onclick":
			$order.=" order by onclick desc";
			break;
		case "number_sold":
			$order.=" order by number_sold desc";
			break;
	}
	if(CheckEmpty($brand)){$where.=" and (brand='{$brand}')";}
	if(CheckEmpty($warehouse)){$where.=" and (warehouse like '%{$warehouse}%' or warehouse='' )";}
	$search.="&so={$so}&key={$key}&attr={$attr}&warehouse={$warehouse}&brand={$brand}";
}

if(!$order)
{
	$order=' order by istop desc,isgood desc,ishead desc,edittime desc,addtime desc';//默认排序
}
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$allclassid=$classid.SmallClassID($classid);
$query="select mlid,url{$LT},title{$LT},edittime,addtime,titlecolor,titleimg{$LT},number,number_sold,plclick,onclick,price,selling{$LT},unit,ensure  from mall where checked=1 and classid in ({$allclassid}) {$where} {$order}";

?>