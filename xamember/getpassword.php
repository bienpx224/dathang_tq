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
$noper=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
	
//验证手机版
if(isMobile()){
	$_SESSION['isMobile']=1;$ism=1;$m='/m';
}else{
	$_SESSION['isMobile']=0;
}

//获取,处理
$lx=par($_GET['lx']);
if(!$lx||!$_SESSION['getpassword_yz']){$lx='steps1';}

$headtitle=$LG['getpassword.headtitle'];

if($Muserid&&$_COOKIE["member_cookie"])
{
	echo '<script language=javascript>';
	echo 'location.href="main.php";';
	echo '</script>';
	XAtsto('main.php');
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("getpassword");

?>
<link href="/bootstrap/css/pages/login.css" rel="stylesheet" type="text/css"/>
<link href="/css/member.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/themes/<?=$theme_member?>" rel="stylesheet" type="text/css" id="style_color"/>
<?php 

if($ism)
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');
	echo '<link href="/css/xingao_m.css" rel="stylesheet" type="text/css"/>';
}else{
	require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/nav.php');
	echo '<style>.gb_member {background-image: url(/images/gb_member_login_'.rand(1,3).'.jpg);}</style>';
}?>
<!--内容开始-->
<div class="gb_member">
<div class="login">
  <div class="content fr"> 
    <!-- BEGIN LOGIN FORM -->
    <form class="login-form" action="getpassword_save.php" method="post">
      <input name="lx" type="hidden" value="<?=$lx?>">
      <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
      <h3 class="form-title">
        <?=$headtitle?>
      </h3>
      
      
      
      
      
      
      
      <!-------------------------------------------第一步---------------------------------------------------->
      <?php if($lx=='steps1'){?>
      <div class="form-group"> 
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9"><?=$LG['getpassword.username']//登录账号?></label>
       <div class="input-icon"> <i class="icon-user" <?=$ism?'style="display:none"':''?>></i><!--手机显示会换行,所以隐藏-->
          <input class="form-control placeholder-no-fix" type="text"  placeholder="<?=$LG['getpassword.username']//登录账号?>" name="username" autocomplete="off"  maxlength="50" required/>
        </div>
      </div>


      <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9"><?=$LG['getpassword.getmobile']//用手机找回?></label>
        <div class="input-icon"> 
            <i class="icon-eye-open" <?=$ism?'style="display:none"':''?>></i>
            
            <select name="getPwdTyp" class="form-control placeholder-no-fix"  required style="padding-left:33px;" onChange="PwdTyp();">
              <?php if($off_sms){?><option value="mobile"><?=$LG['getpassword.getmobile']//用手机找回?></option><?php }?>
              <?php if($ON_WX){?><option value="wx"><?=$LG['getpassword.getwx']//用微信找回?></option><?php }?>
              <option value="email"><?=$LG['getpassword.getemail']//用邮箱找回?></option>
            </select>
            
        </div>
      </div>
<script>
function PwdTyp()
{ 
  var getPwdTyp=document.getElementsByName('getPwdTyp')[0].value;
 
  <?php if($off_sms){?>document.getElementById("mobile").style.display ='none';<?php }?>
  <?php if($ON_WX){?>document.getElementById("wx").style.display ='none';<?php }?>
  document.getElementById("email").style.display ='none';  
  if(getPwdTyp){document.getElementById(getPwdTyp).style.display ='block';}
}
$(function(){ PwdTyp(); });
</script>


      
      <?php if($off_sms){?>
      <div class="form-group" id="mobile">
        <label class="control-label visible-ie8 visible-ie9"><?=$LG['getpassword.getmobile']//用手机找回?></label>
        <div class="input-icon"> <i class="icon-phone" <?=$ism?'style="display:none"':''?>></i>
          <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?=$LG['getpassword.getmobilePpt1']//用手机找回密码?>" name="mobile"  maxlength="30" />
        </div>
      </div>
      <?php }?>
      
      <div class="form-group" id="email">
        <label class="control-label visible-ie8 visible-ie9"><?=$LG['getpassword.getemail']//用邮箱找回?></label>
        <div class="input-icon"> <i class="icon-envelope" <?=$ism?'style="display:none"':''?>></i>
          <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?=$LG['getpassword.getemailPpt1']//用邮箱找回密码?>" name="email" maxlength="50"/>
        </div>
      </div>
      
      
      
      <?php if($ON_WX){?>
      <div class="form-group" id="wx">
        <label class="control-label visible-ie8 visible-ie9"><?=$LG['getpassword.getwx']//用微信找回?></label>
        <div class="input-icon"> 
          <span class="help-block"><?=$LG['getpassword.getwxPPT']//会把校验码发送到您所绑定的微信号里 (如未绑定过,此方式无法使用)?></span>
        </div>
      </div>
      <?php }?>
      
      
      
      
      <!-------------------------------------------第二步---------------------------------------------------->
      <?php }elseif($lx=='steps2'){?>
      <div class="form-group">
        <?=$_SESSION['getpassword_username']?>
      </div>
      <div class="form-group">
        <?=cadd($_GET['ts'])?>
      </div>
      <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9"><?=$LG['getpassword.getchecked']//收到的校验码?></label>
        <div class="input-icon"> <i class="icon-envelope" <?=$ism?'style="display:none"':''?>></i>
          <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?=$LG['getpassword.getchecked']//收到的校验码?>" name="getpassword_yz" maxlength="10"   title="<?=$LG['getpassword.getchecked']//收到的校验码?>"/>
        </div>
      </div>
      
     		 <div class="form-group">
				<label class="control-label visible-ie8 visible-ie9" <?=$ism?'style="display:none"':''?>><?=$LG['getpassword.newPassword1']//重设登录密码?></label>
				<div class="input-icon">
					<i class="icon-lock"></i>
					<input class="form-control placeholder-no-fix" type="password" placeholder="<?=$LG['getpassword.newPassword1']//重设登录密码?>" name="password" autocomplete="off"  maxlength="50" onKeyUp="check_password('<?=$LG['getpassword.newPasswordPpt1']//请输入6到20个字?>');" required/>
                    <span class="help-block" id="msg_password"></span>
				</div>
			</div>
            
            <div class="form-group">
				<label class="control-label visible-ie8 visible-ie9"><?=$LG['getpassword.newPassword2']//重输登录密码?></label>
				<div class="input-icon">
					<i class="icon-lock" <?=$ism?'style="display:none"':''?>></i>
					<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="<?=$LG['getpassword.newPasswordPpt2']//再次输入登录密码?>" name="password2" maxlength="50" required onBlur="check_password2();"/>
                    <span class="help-block red" id="msg_password2"></span>
				</div>
			</div>
            
      <?php }?>
      
      <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9"><?=$LG['code']//验证码?></label>
        <div class="input-icon"> <i class="icon-qrcode" <?=$ism?'style="display:none"':''?>></i>
          <input name="code" id="code" type="text" class="form-control placeholder-no-fix input-small pull-left" placeholder="<?=$LG['code']//验证码?>" style="margin-right:10px;" autocomplete="off"  maxlength="10" required onkeyup="checkcode('getpassword');"  title="<?=$LG['codePpt1']//不分大小写?>"/>
          <span align="left"><span id="msg_code"></span> <img src="/images/code.gif" onclick="codeimg.src='/public/code/?v=getpassword&rm='+Math.random()" id="codeimg" title="<?=$LG['codePpt2']//看不清，点击换一张(不分大小写)?>"  width="100" height="35"/></span> </div>
      </div>
      <div class="form-actions">
        <?php if($lx=='steps2'){?>
        <button type="button" class="btn btn-default pull-left input-small" onClick="location.href='?lx=steps1';"><i class=" icon-arrow-left"></i> <?=$LG['lastStep']//上一步?> </button>
        <?php }?>
        <button type="submit"  class="btn btn-info pull-right input-small"><i class="icon-arrow-right"></i> <?=$LG['nextStep']//下一步?> </button>
      </div>
      <div class="forget-password" align="right">
        <p> <a href="/xamember/"> <span class="btn btn-xs btn-success"> <i class="icon-key"></i> <?=$LG['login.login']//登 录 会 员?> </span></a> <a href="reg.php" id="register-btn" ><span class="btn btn-xs btn-danger"> <i class="icon-user"></i> <?=$LG['reg']//注 册 会 员?> </span></a> </p>
      </div>
    </form>
    <!-- END LOGIN FORM --> 
  </div>
</div>
</div>
<div class="clear"></div>

<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/js/checkJS.php');//通用验证
require_once($_SERVER['DOCUMENT_ROOT'].$m.'/template/incluce/footer.php');
?>
