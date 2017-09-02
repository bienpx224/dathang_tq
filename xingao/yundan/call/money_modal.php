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

//获取,处理
$ydid=par($_GET['ydid']);
$callFrom=par($_GET['callFrom']);
if(!$ydid){exit ("ydid{$LG['pptError']}");}

if($callFrom=='manage'){$pervar='yundan_se';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');}
elseif($callFrom=='member'){require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');}
else{exit ($LG['yundan.Xcall_money_modal_1']);}

$rs=FeData('yundan','*',"ydid={$ydid} {$Mmy}");//查询
if(!$rs['ydid']){exit ("ydid{$LG['pptError']}");}

?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title"><?=$LG['yundan.Xcall_money_modal_2'];//费用明细?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
				<?php 
				$show_small=0;
				require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/money_content.php');
				?>
			    <div align="right"><?=DateYmd($rs['moneytime'])?></div>

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