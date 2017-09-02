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
$pervar='daigou';require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$alonepage=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
$dgid=spr($_POST['dgid']);
if(!$dgid){exit('开发错误OP001');}
$rs=FeData('daigou','*',"dgid={$dgid} {$Mmy}");//查询
?>


    <!--************************************未付款-操作菜单**********************************-->	
	<?php if(have('2,4',spr($rs['status']),1)){?>
    <a href="save.php?typ=cancel&dgid=<?=$rs['dgid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['daigou.54']//确定要取消订购吗? 取消后会全部退回所扣费用?>');"><i class="icon-remove"></i> <?=$LG['daigou.55']//取消订购?></a> 
    <?php }?>

	<?php if( have('0,1,2',spr($rs['status']),1) ){?> 
    <a href="form.php?typ=edit&dgid=<?=$rs['dgid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> <?=$LG['edit']//修改?></a>
    <?php }?>
    
	<?php if( have('0,1,2,10',spr($rs['status']),1)&&have($rs['pay'],'0')  && !($off_delbak&&spr($rs['status'])==10) ){?> 
    <a href="save.php?typ=del&dgid=<?=$rs['dgid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a> 
    <?php }?>
	
    
    
    
	<!--************************************已付款-操作菜单**********************************-->
	<?php 
	$show_op='';
	//取消申请-----------------------------------------
	$memberStatus=NumData('daigou_goods',"dgid='{$rs['dgid']}' and memberStatus>0");
	$value=0; if($memberStatus){ $show_op.=daigou_op($value,$rs);	}
	//申请查货-----------------------------------------
	$value=1; if(have('3,5,6,7',spr($rs['status']),1)){ $show_op.=daigou_op($value,$rs);	}
	//申请换货-----------------------------------------
	$value=2; if(have('3,4,5,6,7,8,9',spr($rs['status']),1)){ $show_op.=daigou_op($value,$rs);	}
	//申请增购数量-----------------------------------------
	$value=3; if(have('3,4,5',spr($rs['status']),1)){ $show_op.=daigou_op($value,$rs);	}
	//申请退货退款-----------------------------------------
	$value=4; if(have('3,4,5,6,7,8,9',spr($rs['status']),1)){ $show_op.=daigou_op($value,$rs);	}
	?>
    
    <!--输出-->
	<?=$show_op?>