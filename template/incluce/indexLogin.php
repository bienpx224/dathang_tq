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
//不能有require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$noper=1;
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');



//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("memberlogin");


//没登录-----------------------------------------------------------------
if(!$Muserid){
?>

    document.writeln("<div class=\"xa_container\">");
    document.writeln("<div class=\"xa_login\">");
    document.writeln("<form class=\"xa_login-form\" action=\"/xamember/login_save.php\" method=\"post\">");
    document.write("<input name=\"lx\" type=\"hidden\" value=\"login\">");
    document.write("<input name=\"tokenkey\" type=\"hidden\" value=\"<?=$tokenkey?>\">");
    document.writeln("  <h3 class=\"xa_login-form_title\"><?=$LG['login.headtitle'];//会员登录?></h3>");
    document.writeln("  <div class=\"xa_form-group\">");
    document.writeln("    <label class=\"xa_control-label\"><?=$LG['login.username'];//登录账号?></label>");
    document.writeln("    <div class=\"xa_input-icon\"> <i class=\"icon-user \"></i>");
    document.writeln("      <input class=\"xa_form-control\" type=\"text\" placeholder=\"<?=$LG['login.username'];//登录账号?>\" name=\"username\" maxlength=\"50\" autocomplete=\"off\" required>");
    document.writeln("    </div>");
    document.writeln("  </div>");
    document.writeln("  <div class=\"xa_form-group\">");
    document.writeln("    <label class=\"xa_control-label\"><?=$LG['login.password'];//登录密码?></label>");
    document.writeln("    <div class=\"xa_input-icon\"> <i class=\"icon-lock\"></i>");
    document.writeln("      <input class=\"xa_form-control\" type=\"password\" placeholder=\"<?=$LG['login.password'];//登录密码?>\" name=\"password\" maxlength=\"50\" autocomplete=\"off\" required>");
    document.writeln("    </div>");
    document.writeln("  </div>");
    
    <?php if( $off_code_login && $_SESSION['member_codeshow']){?>
    document.writeln("  <div class=\"xa_form-group\">");
    document.writeln("    <label class=\"xa_control-label\"><?=$LG['codeShort'];//验证码?></label>");
    document.writeln("    <div class=\"xa_input-icon\"> <i class=\"icon-lock\"></i>");
    document.writeln("      <input class=\"xa_form-control xa_form-control_yzm\" type=\"text\" placeholder=\"<?=$LG['codeShort'];//验证码?>\" name=\"code\" id=\"code\" maxlength=\"10\"autocomplete=\"off\" required  onkeyup=\"checkcode(\'login\');\" >");
    document.writeln("      <span class=\"xa_yzm\">");
    document.writeln("      <span id=\"msg_code\"></span> <img src=\"/images/code.gif\" onclick=\"codeimg.src=\'/public/code/?v=login&rm=\'+Math.random()\" id=\"codeimg\" title=\"<?=$LG['codePpt2'];//看不清，点击换一张(不分大小写)?>\"  width=\"100\" height=\"35\"/>");
    document.writeln("      </span>");
    document.writeln("    </div>");
    document.writeln("  </div>");
    <?php }?>
    
	<?php if($ON_MemberAutoLogin){?>   
    document.write("<div class=\"xa_form-group\">");
    document.write("<div class=\"xa_input-icon\">");
    document.write("<input type=\"checkbox\" name=\"MemberAutoLogin\" value=\"1\" class=\"AutoLogin_b\">");
    document.write("<font class=\"AutoLogin_t\"><?=$LG['login.MemberAutoLogin'];//7天内自动登录?></font> <font class=\"AutoLogin_p\">(<?=$LG['front.53']?>)</font>");
    document.write("</div>");
    document.write("</div>");
    <?php }?> 
   
    document.writeln("  <button type=\"submit\" class=\"xa_btn_login\"><i class=\"icon-key\"></i> <?=$LG['login'];//登  录?> </button>");
     
    <?php if($off_connect_weixin){?>
    document.writeln("  <a href=\"/api/login/weixin/\" target=\"_blank\"><img src=\"/images/login_weixin.gif\"></a>");
    <?php }?>
   
    <?php if($off_connect_qq){?>
    document.writeln("  <a href=\"/api/login/qq/\" target=\"_blank\"><img src=\"/images/login_qq.gif\"></a>");
    <?php }?>
    
    <?php if($off_connect_alipay){?>
    document.writeln("  <a href=\"/api/login/alipay/\" target=\"_blank\"><img src=\"/images/login_alipay.gif\"></a>");
    <?php }?>
    
    document.writeln(" <br> <div class=\"xa_forget-password\" align=\"right\">");
    document.writeln("    <p> <a href=\"/xamember/getpassword.php\"> <span class=\"xa_label-default\"> <i class=\"icon-question-sign\"></i> <?=$LG['login.getpassword'];//忘 记 密 码?> </span></a> <a href=\"/xamember/reg.php\"> <span class=\"xa_label-danger\"> <i class=\"icon-user\"></i> <?=$LG['login.reg'];//注 册 会 员?> </span></a> </p>");
    document.writeln("  </div>");
    document.writeln("</form>");
    
    document.writeln("</div>");
    document.writeln("</div>");
<?php }?>
