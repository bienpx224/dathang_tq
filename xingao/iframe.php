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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

$typ=par($_GET['typ']);
switch($typ)
{
	case 'gd_japan_excel_import':
		$headtitle='商品资料批量导入'; 			$src='/xingao/gd_japan/excel_import.php';
	break;
	case 'gd_mosuda_excel_import':
		$headtitle='商品资料批量导入'; 			$src='/xingao/gd_mosuda/excel_import.php';
	break;
	case 'Scan_incoming':
		$headtitle='扫描入库'; 					$src='/xingao/ScanInStorage/form.php?first=1';
	break;
	case 'Scan_shelves':
		$headtitle='扫描上架'; 					$src='/xingao/baoguo/shelves.php?first=1';
	break;
	case 'baoguo_kuaisu':
		$headtitle='手工入库'; 					$src='/xingao/baoguo/kuaisu.php';
	break;
	case 'baoguo_excel_import':
		$headtitle='导入入库'; 					$src='/xingao/baoguo/excel_import.php';
	break;
	case 'yundan_excel_import':
		$headtitle='运单导入'; 					$src='/xingao/yundan/excel_import.php';
	break;
	case 'yundan_excel_import_other':
		$headtitle='快递/其他导入'; 			$src='/xingao/yundan/excel_import_other.php';
	break;
	case 'yundan_scanGoodsdata':
		$headtitle='扫描物品'; 					$src='/xingao/yundan/scanGoodsdata.php';
	break;
	case 'yundan_scanLotno':
		$headtitle='扫描预出库'; 				$src='/xingao/yundan/scanLotno.php';
	break;
	case 'yundan_scan':
		$headtitle='扫描修改状态'; 				$src='/xingao/yundan/scan.php';
	break;
	case 'yundan_scanWeighing':
		$headtitle='扫描称重'; 					$src='/xingao/yundan/scanWeighing.php';
	break;
	case 'member_excel_import':
		$headtitle='会员导入'; 					$src='/xingao/member/excel_import.php';
	break;

	default:
		exit('typ参数错误');
}



require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?>
	<iframe src="<?=$src?>" id="iframe" width="100%" frameborder="0" scrolling="auto"></iframe>
	<script>$(function(){ iframeHeight('iframe',100);	});//上传导入页面,用到100</script>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
