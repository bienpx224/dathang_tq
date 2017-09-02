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
$pervar='daigou_ad,daigou_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="代购";
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>"); }


/*
	由于代购单必须用PHP获取会员组ID,因此需要有这页面
*/

//获取,处理
$typ=par($_REQUEST['typ']);
$dgid=spr($_REQUEST['dgid']);
$tokenkey=par($_POST['tokenkey']);
$smt=spr($_POST['smt']);

if(!$typ){
	$typ='add';
	$rs['userid']=$_SESSION['DGuserid'];
	$rs['username']=$_SESSION['DGusername'];
}elseif($typ=='edit'){
	if(!$dgid){exit ("<script>alert('dgid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('daigou','username,userid,dgid',"dgid='{$dgid}' {$Xwh}");
}

if($smt)
{
	//验证
	$token=new Form_token_Core();
	$token->is_token("daigou{$dgid}",$tokenkey); //验证令牌密钥

	if(!$_POST['username']&&!$_POST['userid']){exit ("<script>alert('请填写会员！');goBack();</script>");}
	
	
	
	
	
	//验证获取会员
	if(add($_POST['username'])){	
		$member=FeData('member','userid,username,groupid',"username='".add($_POST['username'])."'");
	}elseif(add($_POST['userid'])){	
		$member=FeData('member','userid,username,groupid',"userid='".add($_POST['userid'])."'");
	}
	if(!$member['userid']){exit ("<script>alert('会员账号错误！');goBack();</script>");}
	$_SESSION['DGuserid']=$member['userid'];
	$_SESSION['DGusername']=$member['username'];
	$_SESSION['DGgroupid']=$member['groupid'];



	$token->drop_token("daigou{$dgid}"); //处理完后删除密钥





	if($typ=='add')
	{
		echo '<script language=javascript>';
		echo 'location.href="/xingao/daigou/form.php";';
		echo '</script>';
		XAtsto('/xingao/daigou/form.php');
	}elseif($typ=='edit'){
		if(!$dgid){exit ("<script>alert('dgid{$LG['pptError']}');goBack();</script>");}
		$xingao->query("update daigou set userid='{$member['userid']}',username='{$member['username']}' where dgid='{$dgid}' {$Xwh}");
		SQLError('修改');
		exit ("<script>alert('已修改为{$_SESSION['DGusername']}  ({$_SESSION['DGuserid']})');goBack('c');</script>");
	}
	

}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("daigou{$dgid}");

?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="?" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除style="margin:20px;"-->
    <input name="typ" type="hidden" value="<?=add($typ)?>">
    <input name="dgid" type="hidden" value="<?=$dgid?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <input name="smt" type="hidden" value="1">
  
    
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
            
            <!----------------------------------------基本资料---------------------------------------->
              <div class="portlet">
               <div class="portlet-body form"> 
                  <!--表单内容-->
 
                  <div class="form-group">
                    <label class="control-label col-md-2">所属会员</label>
                    <div class="col-md-10 has-error">
                    
                     <input type="text" class="form-control input-medium" name="username" autocomplete="off"  value="<?=cadd($rs['username'])?>" title="会员名"  placeholder="会员名" onBlur="getUsernameId('username');">
                     
					 <input type="text" class="form-control input-small" name="userid" autocomplete="off"   value="<?=cadd($rs['userid'])?>" title="会员ID"  placeholder="会员ID" onBlur="getUsernameId('userid');">
                     
					 <span class="help-block">可以只填写一项,使用优先从左到右</span>	
                    </div>
                  </div>

  
  
              
              
              
              
            </div>
          </div>
        </div>
        <div align="center">
          <button type="submit" class="btn btn-primary input-xmedium"> <i class="icon-ok"></i> <?=$LG['submit']?></button>

          <button type="button" class="btn btn-danger" onClick="goBack('c');" style="margin-left:30px;"><i class="icon-remove"></i> <?=$LG['close']?> </button>

		</div>
      </div>
    </div>
  </form>

</div>

<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/js/daigouJS.php');
?>


