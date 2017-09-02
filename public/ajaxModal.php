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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$noper=1;$Xwh=1;$WHPerShow=1;
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//管理员可查看全部
if($_SESSION['manage']['userid']){$Mmy='';}

//此页只能用_GET获取参数，因此无法与ajax.php合并
$typ=par($_GET['typ']);
$id=intval($_GET['id']);


/*
	调用方法：
	<a data-toggle="modal" data-target="#ajaxModal<?=$rs['userid']?>" href="/public/ajaxModal.php?typ=memberContact&userid=<?=$rs['userid']?>">联系</a>
	
	<!--弹出载入-->
	<div class="modal fade" id="ajaxModal<?=$rs['userid']?>" tabindex="-1" role="basic" aria-hidden="true">
	<img src="/images/ajax-modal-loading.gif" class="loading">
	</div>
*/
?>










<!--查询内容------------------------------------------------------------------------>
<?php
//会员联系方式----------------------------------------------------------
if ($typ=='memberContact')
{ 	
	$userid=spr($_GET['userid']);
	//$modalTitle='会员联系方式';
	
	
	if(permissions('member_cp,member_co','','manage',1))
	{	
		$rs=FeData('member','userid,username,truename,enname,gender,qq,wx,weibo,zip,store,mobile_code,mobile',"userid='{$userid}' {$Mmy}");
		if($rs['userid'])
		{
			
			$modalTitle.= $rs['truename']?cadd($rs['truename']):'';
			$modalTitle.= $rs['enname']?' /'.cadd($rs['enname']):'';
			$modalTitle.= ' ('.Gender($rs['gender']).')';
			
			$modalContent.= $rs['mobile']?'手机：'.$rs['mobile_code'].' '.cadd($rs['mobile']).'<br>':'';
			$modalContent.= $rs['email']?'邮箱：'.cadd($rs['email']).'<br>':'';
			$modalContent.= $rs['qq']?'Q Q：'.cadd($rs['qq']).'<br>':'';
			$modalContent.= $rs['wx']?'微信：'.cadd($rs['wx']).'<br>':'';
			$modalContent.= $rs['weibo']?'微博：'.cadd($rs['weibo']).'<br>':'';
			$modalContent.= $rs['zip']?'邮编：'.cadd($rs['zip']).'<br>':'';
			$modalContent.= $rs['store']?'网店：'.cadd($rs['store']).'<br>':'';
		}else{
			$modalTitle.= '<span style="color:red">会员ID错误</span>';
		}
	}else{
		$modalContent.= '无权限';
	}
}


?>






<!--输出------------------------------------------------------------------------>
<div class="modal-dialog gray">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
      <h4 class="modal-title"><?=$modalTitle?$modalTitle:$LG['pptError']?></h4>
    </div>
    
    <div class="modal-body">
      <div class="row">
        <div class="col-md-12" align="left"> <?=$modalContent?> </div>
      </div>
    </div>
    
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?=$LG['close']?></button>
    </div>
  </div>
</div>
