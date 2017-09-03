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

if($Muserid&&$_COOKIE['member_cookie'])
{
	echo '<script language=javascript>';
	echo 'location.href="main.php";';
	echo '</script>';
	XAtsto('main.php');
}


//获取,处理
$apptype=par($_GET['apptype']);
$lx=par($_GET['lx']);
if(!$lx){$lx='login';}
$headtitle=$LG['login.headtitle'];//会员登录

//获取上一页
if(!$_SESSION['member']['prevurl'])
{
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	$nowurl = $_SERVER['HTTP_HOST'];
	if($prevurl&&stristr($prevurl,$nowurl)&&!stristr($prevurl,'login_save.php?lx=logout')&&stristr($prevurl,'/xamember/')){$_SESSION['member']['prevurl']=$prevurl;}
}


//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("memberlogin");
?>
<!--[if lt IE <?=spr($uretion)+spr($derstan)+spr($mprehen)+spr($bottion)?>]><?=$LG['pptexplorer']?><![endif]-->  

<link href="/bootstrap/css/pages/login.css" rel="stylesheet" type="text/css"/>
<link href="/css/member.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/themes/<?=$theme_member?>" rel="stylesheet" type="text/css" id="style_color"/>
<?php 
if($ism)
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');
	//require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');
	echo '<link href="/css/xingao_m.css" rel="stylesheet" type="text/css"/>';
}else{
	require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/nav.php');
	echo '<style>.gb_member {background-image: url(/images/gb_member_login_'.rand(1,3).'.jpg);}</style>';
}?>


<?php
if (!$ism) {
?>
<!--内容开始-->
<div class="gb_member">
    <div class="login">
        <div class="content fr">
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form" action="login_save.php" method="post">
            <input name="lx" type="hidden" value="<?=$lx?>">
            <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
                <h3 class="form-title"><?=$headtitle?></h3>
    <?php
        if($_SESSION['connect']['img'])
        {
            $_SESSION['member']['prevurl']='/xamember/connect/list.php';
			
			echo '<h5 class="form-title">';
            echo '<img src="'.$_SESSION['connect']['img'].'" width="30">';
            echo ' '.$_SESSION['connect']['nickname'];
            echo '</h5>';
			
            //echo '<br><font class="gray2">(该账号未绑定,请登录后绑定)</font>';
            echo '<br><font class="gray2">'.$LG['login.pptNoAccount'].' </font>';
			echo '<a href="reg.php"><span class="btn btn-xs btn-danger"> <i class="icon-user"></i> '.$LG['login.reg'].' </span></a>';
            echo '<br><font class="gray2">'.$LG['login.pptHaveAccount'].'</font><br><br>';
        }
    ?>			
    
                
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                  <label class="control-label visible-ie8 visible-ie9"><?=$LG['login.username'];//登录账号?></label>
                    <div class="input-icon">
                        <i class="icon-user" <?=$ism?'style="display:none"':''?>></i>
                        <input class="form-control placeholder-no-fix" type="text" placeholder="<?=$LG['login.username'];//登录账号?>" name="username" autocomplete="off"  maxlength="50" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9"><?=$LG['login.password'];//登录密码?></label>
                    <div class="input-icon">
                        <i class="icon-lock" <?=$ism?'style="display:none"':''?>></i>
                        <input class="form-control placeholder-no-fix" type="password" placeholder="<?=$LG['login.password'];//登录密码?>" name="password" autocomplete="off"  maxlength="50" required/>
                    </div>
                </div>
                
                <?php if( $off_code_login && $_SESSION['member_codeshow'] ){?>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9"><?=$LG['code'];//验 证 码?></label>
                    <div class="input-icon">
                        <i class="icon-qrcode" <?=$ism?'style="display:none"':''?>></i>
                        <input name="code" id="code" type="text" class="form-control placeholder-no-fix input-small pull-left" placeholder="<?=$LG['code'];//验 证 码?>" style="margin-right:10px;" autocomplete="off"  maxlength="10" required onkeyup="checkcode('login');"  title="<?=$LG['codePpt1'];//不分大小写?>"/>
                        <span align="left"><span id="msg_code"></span>
                        
                    <img src="/images/code.gif" onclick="codeimg.src='/public/code/?v=login&rm='+Math.random()" id="codeimg" title="<?=$LG['codePpt2'];//看不清，点击换一张(不分大小写)?>"  width="100" height="35"/></span>
                    </div>
                    
                </div>
                <?php }?>
                
               <?php if($ON_MemberAutoLogin){?>
                <div class="form-group">
                    <div class="input-icon gray_prompt">
                        <input type="checkbox" name="MemberAutoLogin" value="1" style="width:15px; height:15px;">
                        <?=$LG['login.MemberAutoLogin'];//7天内自动登录?> <font class="gray_prompt2">(<?=$LG['login.MemberAutoLoginPpt'];//公共电脑时切勿勾选?>) </font>
                        
                    </div>
                </div>
               <?php }?>
                 
                <div class="form-actions">
                 <button type="submit"  class="btn btn-info pull-right input-small"><i class="icon-key"></i> <?=$LG['login'];//登  录?></button>
                
				<?php if($off_connect_weixin&&!$m){?>         
                <a href="/api/login/weixin/" target="_blank" ><img src="/images/login_weixin.gif" style="margin-bottom:5px; margin-top:5px;"/></a>
				<?php }?>
                         
                <?php if($off_connect_qq){?>         
                <a href="/api/login/qq/" target="_blank" ><img src="/images/login_qq.gif" style="margin-bottom:5px; margin-top:5px;"/></a>
				<?php }?> 
     
                <?php if($off_connect_alipay){?>   
                <a href="/api/login/alipay/" target="_blank" ><img src="/images/login_alipay.gif" style="margin-bottom:5px; margin-top:5px;"/></a>
                 <?php }?>         
                </div>
                
                            
                <div class="forget-password" align="right">
                    <p>
                        <?php if(!$_SESSION['connect']['img']){?>
                        <a href="reg.php" id="register-btn" ><span class="btn btn-xs btn-danger"> <i class="icon-user"></i> <?=$LG['login.reg'];//注 册 会 员?> </span></a>
                        <?php }?>
                        
                        <a href="getpassword.php"><span class="btn btn-xs btn-default"> <i class="icon-question-sign"></i> <?=$LG['login.getpassword'];//忘 记 密 码?> </span></a>
                    </p>
                </div>
                
    
    
    
        <!--[if lt IE 9]><br>
        <font style="color:#F00">
        <?=$LG['pptBrowserHTML']?><br>
        </font>
        <![endif]-->  
                
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
<IFRAME src="/public/cache.html" name="cache" width="0" height="0" border=0  marginWidth=0 frameSpacing=0 marginHeight=0  frameBorder=0 noResize scrolling=no vspale="0" style="display:none"></IFRAME>


<?php } else {?>
<style>
.login-form{
	width:70%;
	margin:0 auto;
}

</style>
        
        <div class="bc-bg" tabindex="0" data-control="PAGE" id="Page">
            <div class="uh cc-head  ubb bc-border" data-control="HEADER" id="Header">
                <div class="ub">
                    <div class="nav-btn" id="nav-left">
                        <div class="fa fa-1g ub-img1">
                        </div>
                    </div>
                    <h1 class="ut ub-f1 ulev-3 ut-s tx-c" tabindex="0">登录</h1>
                    <div class="nav-btn" id="nav-right">
                        
                    </div>
                </div>
                
                
            </div>
            
            
            
            <!--content开始-->
            <div class="uf sc-bg  bc-border" id="content" style="top:4rem;">
                
				<form class="login-form" action="login_save.php" method="post">
            <input name="lx" type="hidden" value="<?=$lx?>">
            <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
                
                
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                  
                    <div class="input-icon">
                       
                        <input class="form-control placeholder-no-fix" placeholder="登录账号" name="username" autocomplete="off" maxlength="50" required="" type="text">
                    </div>
                </div>
                <div class="form-group">
                    
                    <div class="input-icon">
                        
                        <input class="form-control placeholder-no-fix" placeholder="登录密码" name="password" autocomplete="off" maxlength="50" required="" type="password">
                    </div>
                </div>
                
                                
                
                                
                <div class="form-actions">
                 <button type="submit" class="btn-block btn btn-danger pull-right input-small"><i class="icon-key"></i> 登  录</button>
                
				         <a type="button" href="/api/login/weixin/?ish5=1" style="color:#fff;margin-top.5rem;" class="btn-block btn btn-info pull-right input-small"><i class="icon-comments-alt"></i> 微信登录</a>
						 
                
                <!--<a href="/api/login/qq/" target="_blank"><img src="/images/login_qq.gif" style="margin-bottom:5px; margin-top:5px;"></a>-->
				 
     
                         
                </div>
                
                            
                <div style="padding-bottom:20rem;font-size:.8rem;width:100%;clear:both;">
					<br/>
					
                    <div style="float:left;width:40%;">
                        <a href="reg.php"> <i class="icon-user"></i> 注册会员 </span></a>
					</div>
                    <div style="float:right;width:40%;">
                        <a href="getpassword.php"><span class="pull-left"> <i class="icon-question-sign"></i> 忘记密码 </span></a>
                    </div>
                </div>
                
    
    

                
            </form>
				
            </div>
            <!--content结束-->
			
        </div>
<?php }?>
