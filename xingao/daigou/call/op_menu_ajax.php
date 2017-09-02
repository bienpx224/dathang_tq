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
$pervar='daigou';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$alonepage=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
$dgid=spr($_POST['dgid']);
if(!$dgid){exit('开发错误OM001');}
$rs=FeData('daigou','*',"dgid='{$dgid}' {$Xwh}");//查询
?>

<!--************************************采购操作菜单**********************************-->
<?php if($rs['pay']>0&&permissions('daigou_cg','','manage',1)){?> 

    <?php if(have('3,5,6',spr($rs['status']),1)||spr($rs['status'])>=6){?>
    <a href="op.php?typ=procurement&value=1&dgid=<?=$rs['dgid']?>" class="btn btn-xs btn-info" target="iframe<?=$rs['dgid']?>"><i class="icon-caret-right"></i> 
        <?php if(spr($rs['status'])==6){echo '修改';}elseif(spr($rs['status'])>6){echo '查看';}?>采购
    </a>
    <?php }?>
    
    <?php if(have('0,1,2,3,4,5',spr($rs['status']),1)){?>
    <a href="save.php?typ=cancel&dgid=<?=$rs['dgid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('确定要拒绝采购吗? 拒绝后会退回此单所扣费用');"><i class="icon-remove"></i> 拒绝采购</a> 
    <?php }?>
    
<br>
<?php }?>




<!--************************************基本操作菜单**********************************-->	
<?php if(permissions('baoguo_ad','','manage',1)&&have('6,7,8',spr($rs['status']),1)){?> 
    <a href="/xingao/daigou/inStorage.php?dgid=<?=$rs['dgid']?>" class="btn btn-xs btn-default" target="_blank"><i class="icon-download-alt"></i> 入库</a>
<?php }?>

<?php if(permissions('daigou_ed','','manage',1)){?> 
    <a href="form.php?typ=edit&dgid=<?=$rs['dgid']?>" class="btn btn-xs btn-default" target="_blank"><i class="icon-edit"></i> <?=$LG['edit']?></a>
    
    <a href="user.php?typ=edit&dgid=<?=$rs['dgid']?>" class="btn btn-xs btn-default" target="_blank"><i class="icon-share-alt"></i> 转移会员</a>
    
    <?php if( have('0,1,2,10',spr($rs['status']),1) && !($off_delbak&&spr($rs['status'])==10) ){?> 
    <a href="save.php?typ=del&dgid=<?=$rs['dgid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a> 
    <?php }?>
<?php }?>
