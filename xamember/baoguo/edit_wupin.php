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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['baoguo.edit_wupin_1'];//包裹物品编辑
$alonepage=1;
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

//获取,处理
$lx=par($_REQUEST['lx']);
$bgid=par($_REQUEST['bgid']);



if(!$bgid){exit ("<script>alert('bgid{$LG['pptError']}');goBack('c');</script>");}

$rs=FeData('baoguo','*',"bgid='{$bgid}' and status<4 {$Mmy}");
if(!$rs['bgid']){
	exit ("<script>alert('{$LG['baoguo.edit_wupin_2']}');goBack('c');</script>");
}

if(spr($rs['status'])==2||spr($rs['status'])==3){
	if(!$off_edit_wp){exit ("<script>alert('{$LG['baoguo.edit_wupin_3']}');goBack('c');</script>");}
}

if($rs['addSource']==3){
	exit ("<script>alert('{$LG['baoguo.edit_form_3']}');goBack('c');</script>");
}

if($lx=='edit')
{
	$tokenkey=par($_POST['tokenkey']);
	$token=new Form_token_Core();
	$token->is_token('baoguo_wp'.$bgid,$tokenkey); //验证令牌密钥
	
	wupin_save('baoguo',$rs['bgid']);
	
	$token->drop_token('baoguo_wp'.$bgid); //处理完后删除密钥
	exit ("<script>goBack('c');</script>");
}


//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token('baoguo_wp'.$bgid);
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <!--<a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> >--> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> <small><?=cadd($rs['bgydh'])?></small></h3>
		
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
    <form action="?" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
    <input name="lx" type="hidden" value="edit">
    <input name="bgid" type="hidden" value="<?=$rs['bgid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
<!--表单内容-开始------------------------------------------------------------------------------------------------------>
              <div class="portlet">
			  <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['baoguo.edit_form_6'];//物品信息?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 

				  
				<?php wupin_from_general('baoguo',$rs['bgid']);?>				  
        
                </div>
              </div>
 <!--表单内容-结束------------------------------------------------------------------------------------------------------>

            </div>
          </div>
        </div>        
        <!--提交按钮固定--> 
		<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
 
        <div align="center" class="fixed_btn" id="Autohidden">
        
        
<button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
          <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
<button type="button" class="btn btn-danger" onClick="goBack('c');"  style="margin-left:30px;"><i class="icon-remove"></i> <?=$LG['close']?> </button>
        </div>
      </div>
    </div>
  </form>

	<div class="xats"><?=$LG['pptInfo']?><br />
		&raquo; <?=$LG['baoguo.edit_form_8']?><br />
		&raquo; <?=$LG['baoguo.edit_form_9']?><br />
	</div>
</div>

<script src="/js/AntongJQ.js" type="text/javascript"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/js/baoguoJS.php');?>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
<script language="javascript">
//单独分开,要放在foot.php后面
$(function(){       
     wangzhan_other();//包裹表单,选择其他购物网站时
});
//单独分开,要放在foot.php后面
$(function(){       
   CalcDeclareValue();//包裹表单,修改时自动计算总物品价值
});
</script>
