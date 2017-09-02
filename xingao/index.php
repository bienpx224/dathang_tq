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
$headtitle="后台登录";
$noper=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$alonepage=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/copyright.php');
$url=ToArr($url,1);$err=1;
if($url)
{
	foreach($url as $key=>$value)
	{
		if($value&&$_SERVER['HTTP_HOST']==$value){$err=0;break;}
	}
}

if($err){
	echo '
	<strong>--------------------盗版警告:该网址未授权,请联系我们授权才可使用！--------------------</strong><br><br>
	软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0<br>
	著作权人：广西兴奥网络科技有限责任公司<br>
	软件登记号：2016SR041223<br>
	网址：www.xingaowl.com<br>
	本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！<br>
	版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！';
	exit;
}


//安全入口验证
if($_SESSION['entrance']!='xazy')
{
	echo '<script language=javascript>';
	echo 'alert("来自登录页提示:请从安全入口进入!\\n如果不知道入口,请联系兴奥转运系统项目负责人!");goBack("c");';
	echo '</script>';
	exit;
}

//已登录
if($Xuserid&&$_COOKIE['manage_cookie'])
{
	echo '<script language=javascript>';
	echo 'location.href="main.php";';
	echo '</script>';
	XAtsto('main.php');
}

//获取上一页
if(!$_SESSION['manage']['prevurl'])
{
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	$nowurl = $_SERVER['HTTP_HOST'];
	if($prevurl&&stristr($prevurl,$nowurl)&&!stristr($prevurl,'login_save.php?lx=logout')&&stristr($prevurl,'/xingao/')){$_SESSION['manage']['prevurl']=$prevurl;}
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("managelogin");
?>
<link href="/bootstrap/css/pages/login.css" rel="stylesheet" type="text/css"/>

<style>
.login {
background-image: url(/images/gb_manage<?=rand(1,3)?>.jpg);
background-position: center center;
}
</style>


</head>
<!-- BEGIN BODY  -->
<body class="login">
	<!-- BEGIN LOGO -->
	<div class="logo">
		<!--<img src="/images/logo.png"/>-->
	</div>
	<!-- END LOGO -->
	<!-- BEGIN LOGIN -->
	<div class="content">
		<!-- BEGIN LOGIN FORM -->
		<form class="login-form" action="login_save.php" method="post">
        <input name="lx" type="hidden" value="login">
        <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
			<h3 class="form-title" style="font-size:20px"><?=cadd($sitename)?></h3>
			
			<div class="form-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">用户名</label>
				<div class="input-icon">
					<i class="icon-user"></i>
					<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="用户名" name="username" autocomplete="off"  maxlength="50" required/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">密 码</label>
				<div class="input-icon">
					<i class="icon-lock"></i>
					<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="密 码" name="password" autocomplete="off"  maxlength="50" required/>
				</div>
			</div>
            <?php if($manage_login_yz){?>
            <div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">认证码</label>
				<div class="input-icon">
					<i class="icon-foursquare"></i>
					<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="认证码" name="yz" required/>
				</div>
			</div>
            <?php }?>
            
              <?php if( $off_code_managelogin && $_SESSION['manage_codeshow']){?>
            <div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">验证码</label>
			  <div class="input-icon">
					<i class="icon-qrcode"></i>
					<input name="code" id="code" type="text" class="form-control placeholder-no-fix input-small pull-left" placeholder="验证码" style="margin-right:10px;" autocomplete="off"  maxlength="10"  required onkeyup="checkcode('login');"  title="不分大小写"/>
                    <span align="left"><span id="msg_code"></span>
                    
                <img src="/images/code.gif" onclick="codeimg.src='/public/code/?v=login&rm='+Math.random()" id="codeimg" title="看不清，点击换一张(不分大小写)"  width="100" height="35"/></span>
				</div>
                
			</div>
             <?php }?>
             
			<div class="form-actions">
				
				<button type="submit" class="btn btn-info pull-right input-small"><i class="icon-key"></i>
				登 录 后 台
				</button>            
			</div>
			<div class="forget-password">
				
				<p>
	<!--[if lt IE 9]>
    <font style="color:#F00">
    <?=$LG['pptBrowserHTML']?><br>
    </font>
    <![endif]-->  
				</p>
			</div>
			
		</form>
		<!-- END LOGIN FORM -->        
	</div>
	<!-- END LOGIN -->
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
   
 <?php if($off_jishu){?>
  <a href="http://www.xingaowl.com" target="_blank">技术支持：<?=$jishu?> <?=$banben?></a>
 <?php }?>
 
		  
	</div>
    
    
<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/js/checkJS.php');//通用验证
$login=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>

<IFRAME src="/public/cache.html" name="cache" width="0" height="0" border=0  marginWidth=0 frameSpacing=0 marginHeight=0  frameBorder=0 noResize scrolling=no vspale="0" style="display:none"></IFRAME>
