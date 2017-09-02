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
$headtitle=$LG['data.headtitle'];//资料修改
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

$tab=par($_GET['tab']);
if(!$tab){$tab=0;}

$rs=FeData('member','*',"1=1 {$Mmy}");

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("member");
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> 
      <!--有选项卡返回，只能用这种-->
     <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a> <small>
        <?=cadd($rs['username'])?>
        </small> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="tabbable tabbable-custom boxless">
    <ul class="nav nav-tabs">
      <li class="<?=$tab==0?'active':''?>"><a href="?tab=0"><?=$LG['data.form_2'];//基本资料?></a></li>
      <li class="<?=$tab==1?'active':''?>"><a href="?tab=1"><?=$LG['data.form_3'];//登录密码?></a></li>
      <?php if($member_per[$Mgroupid]['off_tixian']){?>
      <li class="<?=$tab==2?'active':''?>"><a href="?tab=2"><?=$LG['data.form_4'];//提现密码?></a></li>
      <?php }?>
      <li class="<?=$tab==3?'active':''?>"><a href="?tab=3"><?=$LG['data.form_5'];//手机/邮箱/微信?></a></li>
      <li class="<?=$tab==4?'active':''?>"><a href="?tab=4"><?=$LG['data.form_7'];//实名认证?></a></li>
    </ul>
    <div class="tab-content">
    
 <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
  <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
   <?php if($tab==0){?>
      <input name="lx" type="hidden" value="basic">

      <div class="tab-pane active">
        <div class="form">
          <div class="form-body">
              <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                 
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['CustomerService']?></label>
                    <div class="col-md-10 gray">
                     <?php if(!$rs['CustomerService']){?>
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="CustomerService">
                       <?=CustomerService($rs['CustomerService'],1)?>
                      </select>
                      <span class="help-block"><?=$LG['member.10']//提交后将不可更改?></span>
                      <?php }else{?>
                     	  <?=CustomerService($rs['CustomerService'],2);?>
                      <?php }?>
                    </div>
                  </div>

                  
                 <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.qq'];//QQ?></label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="qq" value="<?=cadd($rs['qq'])?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.wx'];//微信?></label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="wx" value="<?=cadd($rs['wx'])?>">
                      <span class="help-block"><?=$LG['data.form_36']//只在某些情况下,我们用来联系您,不用于发通知用途?></span>
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.zip'];//邮编?></label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="zip" value="<?=cadd($rs['zip'])?>">
                    </div>
                  </div>
                   
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.weibo'];//微博?></label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="weibo" value="<?=cadd($rs['weibo'])?>">
                    </div>
                  </div>
                   
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.store'];//网店?></label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="store" value="<?=cadd($rs['store'])?>">
                    </div>
                  </div>
                  
                 <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.form_8'];//头像?></label>
                    <div class="col-md-10">
                      <?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='1';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='img';//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['note'];//内容?></label>
                    <div class="col-md-10">
                      <textarea  class="form-control" rows="3" name="content"><?=cadd($rs['content'])?>
</textarea>
                    </div>
                  </div>

                </div>
              </div>

			  
              <?php if(permissions('off_company','','member',1) ){?>
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['data.form_9'];//企业资料?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.company_countries'];//公司所属国家?></label>
                    <div class="col-md-10">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="company_countries">
                        <?php Country($rs['company_countries'],1)?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.company_tel'];//公司电话?></label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="company_tel" value="<?=cadd($rs['company_tel'])?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.company_name'];//公司名称?></label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="company_name" value="<?=cadd($rs['company_name'])?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.form_10'];//公司执照?></label>
                    <div class="col-md-10">
                      <?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='company_license';//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
$img_w=1500;$img_h=1500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
                    </div>
                  </div>
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.company_add'];//公司地址?></label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="company_add" value="<?=cadd($rs['company_add'])?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.company_business'];//公司业务?></label>
                    <div class="col-md-10">
                      <textarea  class="form-control" rows="3" name="company_business"><?=cadd($rs['company_business'])?>
</textarea>
                    </div>
                  </div>
                  
                 <!--表单内容-->  
                </div>
              </div>
               <?php }?>
          </div>
        </div>
      </div>
       <?php }?>
      <!---->
  
   <?php if($tab==1){?>
      <input name="lx" type="hidden" value="login_pwd">

      <div class="tab-pane active">
        <div class="form">
          <div class="form-body">
              <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.password'];//目前登录密码?></label>
                    <div class="col-md-9">
                      <input type="password" class="form-control input-medium"  name="password" autocomplete="off"   maxlength="50" required >
                     </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.new_password'];//新登录密码?></label>
                    <div class="col-md-9">
                      <input type="password" class="form-control input-medium"  name="new_password"  maxlength="50" autocomplete="off" required onKeyUp="check_password('<?=$LG['data.form_11']?>');" >
                      <span class="help-block" id="msg_password"></span>
                      </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.new_password2'];//确认新登录密码?></label>
                    <div class="col-md-9">
                      <input type="password" class="form-control input-medium"  name="new_password2"  maxlength="50" autocomplete="off" required  onBlur="check_password2();">
                      <span class="help-block red" id="msg_password2"></span> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['code'];//验 证 码?></label>
                    <div class="col-md-9">
                      <input name="code" id="code" type="text" class="form-control placeholder-no-fix input-small pull-left" placeholder="<?=$LG['codeShort'];//验证码?>" style="margin-right:10px;" autocomplete="off"  maxlength="10" required onkeyup="checkcode('safety');"  title="<?=$LG['codePpt1'];//不分大小写?>"/>
                      <span align="left"><span id="msg_code"></span> <img src="/images/code.gif" onclick="codeimg.src='/public/code/?v=safety&rm='+Math.random()" id="codeimg" title="<?=$LG['codePpt2'];//看不清，点击换一张(不分大小写)?>"  width="100" height="35"/></span> </div>
                  </div>
                </div>
              </div>
              
            
          </div>
        </div>
      </div>
       <?php }?>
      <!---->
       <?php if($tab==2){?>
       <input name="lx" type="hidden" value="tixian_pwd">

     <div class="tab-pane active">
        <div class="form">
          <div class="form-body">
              <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <?php if(cadd($rs['tixianpassword'])){?>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.tixianpassword'];//目前提现密码?></label>
                    <div class="col-md-9">
                      <input type="password" class="form-control input-medium"  name="tixianpassword"  maxlength="50" autocomplete="off" required >
                       <font color="red">&nbsp;&nbsp; 提现初始密码为空，为了您的资金安全请及时修改。</font>
                      </div>
                  </div>
                  <?php }?>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.new_tixianpassword'];//新提现密码?></label>
                    <div class="col-md-9">
                      <input type="password" class="form-control input-medium"  name="new_tixianpassword"  maxlength="50" autocomplete="off" required >
                      <span class="help-block"><?=$LG['data.form_13'];//不能小于6位数?></span> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.new_tixianpassword2'];//确认新提现密码?></label>
                    <div class="col-md-9">
                      <input type="password" class="form-control input-medium"  name="new_tixianpassword2"  maxlength="50" autocomplete="off" required >
                      <span class="help-block"><?=$LG['data.form_13'];//不能小于6位数?></span> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['code'];//验 证 码?></label>
                    <div class="col-md-9">
                      <input name="code" id="code" type="text" class="form-control placeholder-no-fix input-small pull-left" placeholder="<?=$LG['codeShort'];//验证码?>" style="margin-right:10px;" autocomplete="off"  maxlength="10" required onkeyup="checkcode('safety');"  title="<?=$LG['codePpt1'];//不分大小写?>"/>
                      <span align="left"><span id="msg_code"></span> <img src="/images/code.gif" onclick="codeimg.src='/public/code/?v=safety&rm='+Math.random()" id="codeimg" title="<?=$LG['codePpt2'];//看不清，点击换一张(不分大小写)?>"  width="100" height="35"/></span> </div>
                  </div>
                  
                </div>
              </div>
              
            
          </div>
        </div>
      </div>
       <?php }?>
      <!---->
       <?php if($tab==3){?>
              <input name="lx" type="hidden" value="contact">


      <div class="tab-pane active">
        <div class="form">
          <div class="form-body">
               <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.mobile_code'];//手机地区/号码?></label>
                    <div class="col-md-0">
                      <select  class="form-control input-small select2me" data-placeholder="Select..." name="mobile_code" required >
                        <?php mobileCountry($rs['mobile_code'],1)?>
                      </select>
                      <input type="text" class="form-control input-medium"  name="mobile" value="" placeholder="<?=$LG['data.form_14'];//新手机号码?>">
                      <span class="help-block"> 
                      <?=$LG['data.form_21']?><br>
                      <?=$LG['data.form_22']?> (<?=$LG['data.form_23']?>:<?=substr_cut($rs['mobile'],$length=3)?>)
                      </span> 
                      </div>
                  </div>
                  
                  
                  
                  
                  
      <?php if($off_sms&&cadd($rs['mobile'])){?>            
      <div class="form-group">
        <label class="control-label col-md-2"><?=$LG['data.mobile_yz'];//手机验证?></label>
        <div class="col-md-9">
          <input type="text" class="form-control input-small" name="mobile_yz" maxlength="10"  placeholder="<?=$LG['data.form_15'];//收到验证码?>">
          <button type="button" class="btn btn-warning" onClick="SendMobile_yz();" ><i class="icon-rss"></i> <?=$LG['data.form_17'];//获取验证码?> </button>
          <span class="help-block" id="msg_mobile_yz"></span>
        </div>
      </div>
     <?php }?>
       
       
        </div>
      </div>
      
      
               <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->

                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.email'];//新E-mail?></label>
                    <div class="col-md-9">
                      <input type="text" class="form-control input-medium"  name="email" value="">
                      
                      <span class="help-block"><?=$LG['data.form_22']?> (<?=$LG['data.form_24']?>:<?=substr_cut($rs['email'],$length=3)?>)</span></div>
                  </div>
                   <?php if(cadd($rs['email'])){?>       
                  <div class="form-group">
        <label class="control-label col-md-2"><?=$LG['data.email_yz'];//邮箱验证?></label>
        <div class="col-md-9">
          <input type="text" class="form-control input-small" name="email_yz"    maxlength="10" placeholder="<?=$LG['data.form_15'];//收到验证码?>">
          <button type="button" class="btn btn-warning" onClick="SendEmail_yz();"><i class="icon-rss"></i> <?=$LG['data.form_17'];//获取验证码?> </button>
           <span class="help-block" id="msg_email_yz"></span>
        </div>
      </div>
        <?php }?>


       
       
        </div>
      </div>
      
      
      
      
      
 <?php if($ON_WX){?>     
<div class="portlet">
<div class="portlet-body form" style="display: block;"> 
  <!--表单内容-->
  
        <div class="form-group">
        <div class="control-label col-md-2 right"><?=$LG['data.form_37']//绑定微信?></div>
        <div class="col-md-10">
              <?php if($rs['wx_openid']){?>
                  <span id='wx_binding_tmp' class="red2">
                  <button type="button" class="btn btn-default" onClick="wx_binding_tmp('del')" ><?=$LG['data.form_41']//已绑定,我要解绑?></button>
                  </span>
             <?php }else{?>
                
                  
                  <span id='wx_binding_tmp' class="red2">
                    <button type="button" class="btn btn-info" onClick="wx_binding_tmp('add')" ><?=$LG['data.form_42']//我要绑定?></button><br>
                  </span>
             <?php }?>

             
            <span class="help-block">
                <?=$LG['data.form_38']//&raquo; 绑定后便于给您发送重要通知,如密码找回,包裹/运单状态等?>
                <br>
                <?=$rs['wx_openid']?$LG['data.form_39']:''//&raquo; 只能绑定一个微信号,如需要绑定其他账号请先解绑?>
                <?=$LG['data.form_40']//&raquo; 此项操作可以不用点击下面的[提交]?>
            </span>
            
 
<script>
function wx_binding_tmp(typ){
	if(typ=='del')
	{	
		if(confirm("<?=$LG['data.form_43']//确认要解绑吗??>")){}else{return false;}
	}
	
	$.ajax({
        type: "POST",
        cache: false,
        data: 'lx=wx_binding_tmp&typ='+typ,
        async: false,//true导步处理;false为同步处理
        url: "/public/ajax.php",
        success: function (data) 
		{
  		    document.getElementById('wx_binding_tmp').innerHTML= data;
		}
    });
}
</script>

            
        </div>
        </div>

 </div>
</div>
<?php }?>            
              
      
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['code'];//验 证 码?></label>
                    <div class="col-md-9">
                      <input name="code" id="code" type="text" class="form-control placeholder-no-fix input-small pull-left" placeholder="<?=$LG['codeShort'];//验证码?>" style="margin-right:10px;" autocomplete="off"  maxlength="10" required onkeyup="checkcode('safety');"  title="<?=$LG['codePpt1'];//不分大小写?>"/>
                      <span align="left"><span id="msg_code"></span> <img src="/images/code.gif" onclick="codeimg.src='/public/code/?v=safety&rm='+Math.random()" id="codeimg" title="<?=$LG['codePpt2'];//看不清，点击换一张(不分大小写)?>"  width="100" height="35"/></span> </div>
                  </div>
                  <br>

                  
              
           
          </div>
        </div>
      </div>
       <?php }?>
       
      <!---->
        
       <?php if($tab==4){?>
       
		<?php 
		$ok=1;
		if($off_certification)
		{
			 if(!$rs['certification'])
			 {
				 if($rs['certification_ct1']==1&&(!$rs['mobile']&&!$rs['email'])){$ok=0;$ts=$LG['data.form_18'];}//请先验证手机或邮箱才能进行实名认证!
				 elseif($rs['certification_ct1']==2&&(!$rs['mobile']||!$rs['email'])){$ok=0;$ts=$LG['data.form_25'];}//请先验证手机和邮箱才能进行实名认证!
				 elseif($rs['certification_ct1']==3&&!$rs['mobile']){$ok=0;$ts=$LG['data.form_20'];}//请先验证手机才能进行实名认证!
				 elseif($rs['certification_ct1']==4&&!$rs['email']){$ok=0;$ts=$LG['data.form_26'];}//请先验证邮箱才能进行实名认证!
			 }
		}
		
        if(!$ok){
			echo '<font class="red">'.$ts.'</font>';
       }else{?>
     <input name="lx" type="hidden" value="certification">
     <div class="tab-pane active">
        <div class="form">
          <div class="form-body">
              <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                   
                  <?php if($off_certification&&!$rs['certification']){?>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.certification_for'];//申请审核?></label>
                    <div class="col-md-10">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="certification_for" value="1"  <?php if($rs['certification_for']){echo 'checked';}?> />
                      </div>
                     <span class="help-block">
                      &raquo; <?=$LG['data.form_27']//开启申请审核时我们才会帮您审核，通过审核后不可再修改，请在未审核前认真检查?><br>
                     </span>
                      
                    </div>
                  </div>
                  <?php }?>

                 <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.truename'];//真实姓名?></label>
                    <div class="col-md-10">
                    <?php if($rs['certification']){?><?=substr_cut($rs['truename'])?><?php }else{?>
                      <input type="text" class="form-control input-medium"  name="truename" value="<?=cadd($rs['truename'])?>" required>
                    <?php }?>
					 <font color="red">&nbsp;&nbsp;&nbsp;&nbsp;（您的真实姓名将是您认领包裹的依据，我们将严格为您保密！）</font> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.enname'];//英文名/拼音?></label>
                    <div class="col-md-10">
                     <?php if($rs['certification']){?><?=substr_cut($rs['enname'])?><?php }else{?>
                     <input type="text" class="form-control input-medium"  name="enname" value="<?=cadd($rs['enname'])?>" required style="float:left; margin-right:10px;">
                      <button type="button" class="btn btn-default" onClick="window.open('/public/AutoInput.php?typ=py&space=1&case=3&content='+document.xingao.truename.value+'&returnform=opener.document.xingao.enname.value','','width=100,height=100');"  style="float:left;">生成拼音 </button>
					 <?php }?>
					  <font color="red">&nbsp;&nbsp;&nbsp;&nbsp;（您的英文名必须和您护照上的名字保持一致，国外将凭护照领取包裹！）</font>   
                    </div>
                  </div>
                  

                 <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.nickname'];//昵称?></label>
                    <div class="col-md-10 <?=$ON_nickname?'has-error':''?>">
                      <?php if($rs['certification']){?><?=cadd($rs['nickname'])?><?php }else{?>
                      <input type="text" class="form-control input-medium popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['data.save_35']?>" maxlength="50" <?=$ON_nickname?'required':''?>  name="nickname" value="<?=cadd($rs['nickname'])?>">
                       <?php }?>
                       <font color="red">&nbsp;&nbsp;&nbsp;&nbsp;（您的昵称将是您的专属仓库名，请务必认真填写！）</font> 
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.gender'];//性别?></label>
                    <div class="col-md-10">
                    <?php if($rs['certification']){?><?=Gender($rs['gender'])?><?php }else{?>
                      <select name="gender" required>
                        <?=Gender($rs['gender'],1)?>
                      </select>
                    <?php }?>
                    </div>
                  </div>
                    
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.birthday'];//生日?></label>
                    <div class="col-md-10">
                    <?php if($rs['certification']){?><?=DateYmd($rs['birthday'],2)?><?php }else{?>
                    	<input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="birthday" value="<?=DateYmd($rs['birthday'],2)?>" required>
                        <span class="help-block"><?=$integral_MemberBirthday>0?LGtag($LG['data.form_28'],'<tag1>=='.$integral_MemberBirthday):''?></span>
                    <?php }?>  
                    </div>
                  </div>
                 
   
               
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.shenfenhaoma'];//身份证号码?></label>
                    <div class="col-md-10">
                    <?php if($rs['certification']){?><?=substr_cut($rs['shenfenhaoma'])?><?php }else{?>
                      <input type="text" class="form-control input-medium"  name="shenfenhaoma" value="<?=cadd($rs['shenfenhaoma'])?>" <?=$certification_ct2?'required':''?>>
                    <?php }?>
					 <font color="red">&nbsp;&nbsp;&nbsp;&nbsp;（可不填写！）</font>   
                    </div>
                  </div>
				  
			  
				  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.form_29']?>(<?=$certification_ct3?$LG['data.form_30']:$LG['data.form_31']?>)</label>
                    <div class="col-md-10">
                    <?php if($rs['certification']){?>
						<?=$rs['shenfenimg_z']?$LG['data.form_32']:$LG['data.form_33']?>
					<?php }else{?>
                      <?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='1';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='shenfenimg_z';//保存字段名，多个时加[]
$Pathname='card';//存放目录分类

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定

include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
                    <?php }?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.form_34']?>(<?=$certification_ct3?$LG['data.form_30']:$LG['data.form_31']?>)</label>
                    <div class="col-md-10">
                    <?php if($rs['certification']){?>
						<?=$rs['shenfenimg_b']?$LG['data.form_32']:$LG['data.form_33']?>
					<?php }else{?>
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='1';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='shenfenimg_b';//保存字段名，多个时加[]
$Pathname='card';//存放目录分类

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
                    <?php }?>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.form_35']?>(<?=$certification_ct3?$LG['data.form_30']:$LG['data.form_31']?>)</label>
                    <div class="col-md-10">
                    <?php if($rs['certification']){?>
						<?=$rs['handCert']?$LG['data.form_32']:$LG['data.form_33']?>
					<?php }else{?>
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='1';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='handCert';//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定

include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>

                    <span class="help-block" style="clear:both;">
                    <img src="/images/handCert.jpg"/>
                    </span>

                    <?php }?>
                    
                    
                    </div>
                  </div>
                  
                  
                </div>
              </div>
              
            
          </div>
        </div>
        
        
      
      </div>
     
 	  <?php }//if(!$rs['mobile']||!$rs['email']){?>
      <?php }?>
      <!---->
      
      
       
       <div align="center">
                <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled  style="margin-left:30px;"> <i class="icon-ok"></i> <?=$LG['submit']?> </button>
                <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
              </div>
              
    </div>
  </div>
   </form>
</div>

<!-------------------------------------------手机验证-------------------------------------------->
<?php if($off_sms){?>
<SCRIPT type=text/javascript>
function SendMobile_yz() {

 var xmlhttp_sj=createAjax(); 
 if (xmlhttp_sj) {  
  var span=document.getElementById('msg_mobile_yz');  // 获取显示节点
		xmlhttp_sj.open('POST','/xamember/data/yz.php?n='+Math.random(),true); 
		xmlhttp_sj.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp_sj.send('lx=mobile');
		
  xmlhttp_sj.onreadystatechange=function() {  
   if (xmlhttp_sj.readyState==4 && xmlhttp_sj.status==200) { 
    span.innerHTML=unescape(xmlhttp_sj.responseText); 
    }
  }
 }
}
</SCRIPT>
<?php }?>

<!-------------------------------------------邮箱验证-------------------------------------------->
<SCRIPT type=text/javascript>
function SendEmail_yz() {

 var xmlhttp_em=createAjax(); 
 if (xmlhttp_em) {  
  var span=document.getElementById('msg_email_yz');  // 获取显示节点
		xmlhttp_em.open('POST','/xamember/data/yz.php?n='+Math.random(),true); 
		xmlhttp_em.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp_em.send('lx=email');

  xmlhttp_em.onreadystatechange=function() {  
   if (xmlhttp_em.readyState==4 && xmlhttp_em.status==200) 
   { 
    span.innerHTML=unescape(xmlhttp_em.responseText); 
   }
  }
 }
}
</SCRIPT>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/js/checkJS.php');//通用验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
