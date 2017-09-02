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
$headtitle=$LG['transfer.form_1'];//转账申请
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
if(!$ON_bankAccount){exit ("<script>alert('{$LG['transfer.form_2']}');goBack();</script>");}

//获取,处理
$lx=par($_GET['lx']);
$tfid=par($_GET['tfid']);
$money=spr($_GET['money']);

$OnAutoPay=spr($_GET['OnAutoPay']);
$fromtable=par($_GET['fromtable']);
$fromid=par($_GET['fromid']);




if(!$lx){$lx='add';}
if($lx=='edit')
{
	if(!$tfid){exit ("<script>alert('tfid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('transfer','*'," tfid='{$tfid}' {$Mmy}");
	if(spr($rs['status'])>0){exit ("<script>alert('{$LG['transfer.form_3']}');goBack('uc');</script>");}
}

if(($OnAutoPay&&$fromid&&$fromtable)||($rs['fromtable']&&$rs['fromid'])){$OnAutoPay=1;}else{$OnAutoPay=0;}//验证是否支持自动支付


$mr=FeData('member','money,money_lock',"1=1 {$Mmy}");

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey=$token->grante_token("transfer_add");

?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
        
    <h3 class="page-title"> 
    <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
    <?=$headtitle?>
    </a> 
        
   	 <small> 
        <?=$LG['money']?>:<font class="red"><?=spr($mr['money'])?> <?=$Mcurrency?></font> 
        <?php if($mr['money_lock']>0){?>
        <font class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['transfer.form_4'];//可能是在申请提现或其他操作中?>">
        <?=$LG['money_lock']?>:<font class="red"><?=spr($mr['money_lock'])?> <?=$Mcurrency?></font> 
        </font> 
        <?php }?>
        
        <?php if($money>0){echo $LG['transfer.form_11'].'<font class="red">'.$money.$Mcurrency.'</font>';}?>
   	 </small>
        
   </h3>
   
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="tfid" type="hidden" value="<?=$rs['tfid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless"><!-- -->
 		<ul class="nav nav-tabs">
			<li class="active"><a href="javascript:void(0)"><?=$LG['transfer.form_1'];//转账申请?></a></li>
			<li><a href="list.php"><?=$LG['transfer.form_5'];//转账申请记录?></a></li>
		</ul>			

     <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['transfer.form_6'];//转账回单(必须上传)?></label>
                    <div class="col-md-10">
                      <?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='img';//保存字段名，多个时加[]

//$off_water=0;//水印(不手工设置则按后台设置)
//$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
//$rsfile_my='no';//指定文件，no则空
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');


?>
                    </div>
                  </div>
                  
                  
                  <?php if($OnAutoPay){?>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['transfer.autoPay'];//自动支付?></label>
                    <div class="col-md-10">
                    
<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
<input type="checkbox" class="toggle" name="autoPay" value="1" <?=$rs['autoPay']||$lx=='add'?'checked':''?> />
</div>
<input type="hidden" name="fromtable" value="<?=$fromtable?><?=$rs['fromtable']?>">
<input type="hidden" name="fromid" value="<?=$fromid?><?=$rs['fromid']?>">

<font class="help-block">
 &raquo;  <?=LGtag($LG['transfer.form_12'],'<tag1>=='.$fromid.$rs['fromid']);?><br>
</font>

                    </div>
                  </div>
                  <?php }?>
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['explain']?></label>
                    <div class="col-md-10">
                      <textarea  class="form-control" rows="3" name="content"><?=cadd($rs['content'])?>
</textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['transfer.form_7'];//收款账号?></label>
                    <div class="col-md-10">
                      <?=caddhtml($bankAccount)?>
                      
<font class="help-block">
	<br><br>
    <font class="red2">
    &raquo; <?=$LG['transfer.form_8'];//请转账至以上账号，然后把转账回单 拍照/截图 上传。（图片文字需清晰可见，否则无法为您处理）?><br>
    </font>
    
    <font class="red">
    &raquo; <?=$LG['transfer.form_9']?>
    <?php if($member_per[$Mgroupid]['off_tixian']){?>（<?=$LG['transfer.form_13']?>）<?php }?><br>
    </font>
    
    &raquo; <?=$LG['transfer.form_10'];//我们审核无误后会尽快帮您充值，如果长时间未见有处理，请联系我们客服！?><br>
</font>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>        
        <!--提交按钮固定--> 
		<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
 
        <div align="center" class="fixed_btn" id="Autohidden">
        
        
<button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
          <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
        </div>
      </div>
    </div>
  </form>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
