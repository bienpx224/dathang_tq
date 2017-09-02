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



/*
  	显示日志
	调用:
	
	<a data-target="#ajaxLog<?=$rs['dgid']?>" data-toggle="modal" href="/public/opLogModal.php?fromtable=daigou&fromid=<?=$rs['dgid']?>&callFrom=<?=$callFrom?>">
      链接
	</a>
	<div class="modal fade" id="ajaxLog<?=$rs['dgid']?>" tabindex="-1" role="basic" aria-hidden="true">
		<img src="/images/ajax-modal-loading.gif" class="loading">
	</div>
*/





//获取,处理
$fromtable=par($_GET['fromtable']);
$fromid=spr($_GET['fromid']);
$callFrom=par($_GET['callFrom']);
if(!$fromid||!$fromtable){exit ("{$LG['pptError']}");}

if($callFrom=='manage')
{
	//后台支持的权限
	if($fromtable=='daigou'){$pervar='daigou_ed,daigou_se,daigou_cg,daigou_th,daigou_zg,daigou_hh,daigou_ch,daigou_ck,daigou_ex,daigou_ot';$chk=1;}
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
}elseif($callFrom=='member'){
	//会员支持的权限
	//if($fromtable=='daigou'){$pervar='daigou';$chk=1;}//不支持
	require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
}

if(!$chk){exit ($LG['pptOpError']);}
?>
<div class="modal-dialog modal-wide">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title"><?=$LG['log']?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12" align="left">
				 <?=opLog($fromtable,$fromid)?>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			 <button type="button" class="btn btn-danger" data-dismiss="modal"> <?=$LG['close']?> </button>
		</div>
	</div>
	<!-- /.modal-content --> 
</div>
<!-- /.modal-dialog -->