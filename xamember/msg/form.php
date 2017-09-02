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
$headtitle=$LG['msg.form_1'];//发信息
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$id=par($_GET['id']);
if(!$lx){$lx='add';}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("msg_add");
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
  
  <div class="tabbable tabbable-custom boxless">
    <div class="tab-content">
      <div class="portlet-body">
        <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
          <input name="lx" type="hidden" value="<?=add($lx)?>">
          <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
          <div class="chat-form">
            <div class="input-cont" style="margin-right:0px;" align="left">
              <div class="radio-list">
                <?=MsgStatus(spr($_GET['status']),2)?>
                <label class="radio-inline"><font class="red">*</font></label>
              </div>
              <br>
              <input name="title"  type="text" class="form-control placeholder-no-fix" placeholder="<?=$LG['msg.form_2'];//标题?>" required  title="<?=$LG['msg.form_2'];//标题?>" value="<?=par($_GET['title'])?>"/><br>
<br>

              
              <textarea  class="form-control" rows="4" name="content" placeholder="<?=$LG['msg.form_3'];//内容?>..."  title="<?=$LG['msg.form_3'];//内容?>"  required ></textarea>
            </div>
            <div align="right" style="margin-top:10px;">
              <?php if( $off_code_liuyan){?>
              <?=$LG['code'];//验证码?>
              <input name="code" id="code" type="text" class="form-control placeholder-no-fix input-small" placeholder="<?=$LG['codeShort'];//验证码?>" style="margin-right:10px;" autocomplete="off"  maxlength="10" required onkeyup="checkcode('gbook');"  title="<?=$LG['codePpt1'];//不分大小写?>"/>
              <span align="left"><span id="msg_code"></span> <img src="/images/code.gif" onclick="codeimg.src='/public/code/?v=gbook&rm='+Math.random()" id="codeimg" title="<?=$LG['codePpt2'];//看不清，点击换一张(不分大小写)?>"  width="100" height="35"/></span>
              <?php }?>
              <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled  style="margin-left:30px;" required> <i class="icon-ok"></i> <?=$LG['submit']?> </button>
              <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
            </div>
            <?php 
//文件上传配置
$uplx='file';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='file';//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
$img_w=500;$img_h=500;//裁剪尺寸：指定
$rsfile_my='no';//指定文件，no则空
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/js/checkJS.php');//通用验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
