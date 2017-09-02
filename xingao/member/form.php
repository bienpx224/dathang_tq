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
$pervar='member_se,member_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="会员";
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

$member_cp=0;if(permissions('member_cp',0,'manage',1)){$member_cp=1;}//是否有会员高级管理权限
$member_co=0;if(permissions('member_co',0,'manage',1)||$member_cp){$member_co=1;}//是否有查看会员联系方式权限

//获取,处理
$lx=par($_GET['lx']);
$userid=par($_GET['userid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$userid){exit ("<script>alert('userid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('member',"*","userid='{$userid}'");
	if(!$rs['userid']){exit ("<script>alert('该会员已被删除!');goBack('uc');</script>");}
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("member{$userid}");
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
  <div><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
    <div class="col-md-12">
      <h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a> <small> <?=cadd($rs['username'])?> </small> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="userid" autocomplete="off"  type="hidden" value="<?=$rs['userid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>主要资料</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">所属分类</label>
                    <div class="col-md-0 has-error">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="groupid">
                        <?php
$query2="select groupid,groupname{$LT} from member_group where checked=1 order by  myorder desc,groupname{$LT} desc,groupid desc";
$sql2=$xingao->query($query2);
while($rs2=$sql2->fetch_array())
{
?>
                        <option value="<?=$rs2['groupid']?>" <?=$rs2['groupid']==$rs['groupid']?' selected':''?>> <?=$rs2['groupname'.$LT]?> </option>
                        <?php
}
?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">用户名</label>
                    <div class="col-md-10 has-error">
                      <input name="username_old" type="hidden" value="<?=cadd($rs['username'])?>">
                      <input type="text" class="form-control input-medium" name="xa_name"  maxlength="50" required value="<?=cadd($rs['username'])?>">
                      <?php if($rs['username']){?>
                      <span class="help-block">注意:修改用户名时,系统中所有旧用户名也会更新</span>
                      <?php }?> </div>
                  </div>
                  
                  <?php if($member_cp||$lx=='add'){?>
                  <div class="form-group">
                    <label class="control-label col-md-2">账户币种</label>
                    <div class="col-md-10 has-error">
<select name="currency" class="form-control input-small select2me" required data-placeholder="请选择">
<?=openCurrency($rs['currency'],4)?>
</select>
                      <span class="help-block">
                      <font class="red2">
                      &raquo; 警告：如果此账户已经费用记录(有过扣费、充值等)，将不可乱变更，否则将可能影响到账户相关记录，如非必须，建议重新注册账号<br>
                      &raquo; 变更时会自动按新币种兑换余额，其所用的相关操作也自动变更 (未处理的提现金额、代购单所有已扣费用)<br>
                      </font>
                      &raquo; 对于无费用记录的新会员可随意修改<br>
                      </span>
                    </div>
                  </div>
                  <?php }?>

                  <div class="form-group">
                    <label class="control-label col-md-2">入库码</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="useric"  maxlength="50" value="<?=cadd($rs['useric'])?>">
                      <span class="help-block">留空则自动生成 (如果非空勿修改，否则无法识别已使用该入库码的包裹)</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">登录密码</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="password" autocomplete="off"   maxlength="50" onKeyUp="check_password('<?=$lx=='add'?'请输入6到20个字':'留空则不修改，可输入6到20个字'?>');">
                      <span class="help-block" id="msg_password"></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">提现密码</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="tixianpassword"  maxlength="50" >
                      <span class="help-block">不能小于6位数,不修改请留空</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['checkedOn']//开通?></label>
                    <div class="col-md-10">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="checked" value="1"  <?php if($rs['checked']||$lx=='add'){echo 'checked';}?> />
                      </div>
                    </div>
                  </div>
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">解绑微信</label>
                    <div class="col-md-10">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="wx_del" value="1"/>
                      </div>
                      <a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="勾选提交后,将解绑与公众号的绑定 (是在本站解绑,不是在公众号删除)"> <i class="icon-info-sign"></i> </a>
                    </div>
                  </div>
                  
                </div>
              </div>
              <!---->
              
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>其他功能</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['CustomerService']?></label>
                    <div class="col-md-10">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="CustomerService">
                       <?=CustomerService($rs['CustomerService'],1)?>
                       
                      </select>
                      
					 <span class="help-block"> <?=CustomerService($rs['CustomerService'],2)?></span>

                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">受邀请的推广员ID</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small"  name="tg_userid" value="<?=cadd($rs['tg_userid'])?>">
                      <span class="help-block"> <?=$rs['tg_userid']?'此会员来自'.cadd($rs['tg_username']).'('.$rs['tg_userid'].')的推广':''?></span>
                    </div>
                  </div>
                  
                  <?php if($member_cp){?>
                  <div class="form-group">
                    <label class="control-label col-md-2">API接口</label>
                    <div class="col-md-10">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="api" value="1"  <?=$rs['api']?'checked':''?>/>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">API KEY</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium tooltips"  name="api_key" value="<?=cadd($rs['api_key'])?>"  data-container="body" data-placement="top" data-original-title="双击可全选再复制" readonly>
                      <input type="button" class="btn btn-default" value="随机生成" onClick="window.open('/public/random.php?lx=1&field=api_key&length=24');" >
                      <!--<span class="help-block">必须复杂否则网站会被入侵</span>--> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">API 权限</label>
                    <div class="col-md-10">
                      <input type="checkbox" name="api_yd_query" value="1" <?=$rs['api_yd_query']?'checked':''?> />
                      运单查询
                      <input type="checkbox" name="api_yd_add" value="1" <?=$rs['api_yd_add']?'checked':''?> />
                      下运单 </div>
                  </div>
                  
                 <?php }?>
                </div>
              </div>
              <!---->
              <?php if($member_co){?>
              <a name="certification"></a>
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>实名认证</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  
                  
                  
				<?php if($member_cp){?>
                   <div class="form-group">
                    <label class="control-label col-md-2">通过审核</label>
                    <div class="col-md-10">
                    
<div class="radio-list">
   <label class="radio-inline">
   <input type="radio" name="certification"  value="0"  <?php if(!$rs['certification']){echo 'checked';}?> onClick="$('#why_message_show').css('display','block')" > 未通过审核
   </label>  

   <label class="radio-inline">
   <input type="radio" name="certification" value="1"  <?php if($rs['certification']){echo 'checked';}?>  onClick="$('#why_message_show').css('display','none')" > 通过审核
   </label>

   <label class="radio-inline">
   <input type="radio" name="certification" value="2"  <?php if($rs['certification_for']){echo 'checked';}?>  onClick="$('#why_message_show').css('display','none')" > 未处理
   </label>
</div>

<?php if($lx!='add'){?>
<div id="why_message_show" style="display:none">
<br>原因 (发站内信):<br>
<textarea  class="form-control" rows="3" name="why_message"></textarea>
</div>
<?php }?>
               

                     <span class="help-block">
                     <?php if($lx!='add'){?>
                      &raquo; <?=$rs['certification_for']?'会员已申请审核 ':'会员未申请审核,请勿审核'?><br>
                      &raquo; <font class="red2">变更后，会员需要重新登录才生效</font>
                     <?php }?>
                     </span>
                    </div>
                  </div>
                  
				<?php }?>
                
                 
                  <div class="form-group">
                    <label class="control-label col-md-2">真实姓名</label>
                    <div class="col-md-10">
                       <?php if($member_cp){?>
                       <input type="text" class="form-control input-medium"  name="truename" value="<?=cadd($rs['truename'])?>">
					   <?php }else{echo cadd($rs['truename']);}?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">英文名/拼音</label>
                    <div class="col-md-10">
                       <?php if($member_cp){?>
                      <input type="text" class="form-control input-medium"  name="enname" value="<?=cadd($rs['enname'])?>" style="float:left; margin-right:10px;">
                      <button type="button" class="btn btn-default" onClick="window.open('/public/AutoInput.php?typ=py&space=1&case=3&content='+document.xingao.truename.value+'&returnform=opener.document.xingao.enname.value','','width=100,height=100');"  style="float:left;">生成拼音 </button>
					   <?php }else{echo cadd($rs['enname']);}?>
                      
                    </div>
                  </div>
                  
                    
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.nickname'];//昵称?></label>
                    <div class="col-md-10 <?=$ON_nickname&&1==2?'has-error':''?>">
                      <input type="text" class="form-control input-medium" maxlength="50" name="nickname" value="<?=cadd($rs['nickname'])?>" <?=$ON_nickname&&1==2?'required':''?>>
                    </div>
                  </div>

                  
                  <div class="form-group">
                    <label class="control-label col-md-2">手机地区/号码</label>
                    <div class="col-md-0">
                       <?php if($member_cp){?>
                      <select  class="form-control input-small select2me" data-placeholder="Select..." name="mobile_code">
                        <?php mobileCountry($rs['mobile_code'],1)?>
                      </select>
                      <input type="text" class="form-control input-medium"  name="mobile" value="<?=cadd($rs['mobile'])?>" placeholder="手机号码">
                      <span class="help-block"> 请选择正确，否则可能无法发送短信!</span>
 					   <?php }else{echo $rs['mobile_code'].' '.cadd($rs['mobile']);}?>
                   </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">E-mail</label>
                    <div class="col-md-10">
                       <?php if($member_cp){?>
                      <input type="text" class="form-control input-medium"  name="email" value="<?=cadd($rs['email'])?>">
 					   <?php }else{echo cadd($rs['email']);}?>
                   </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">性别</label>
                    <div class="col-md-10">
                       <?php if($member_cp){?>
                      <select name="gender">
                        <?=Gender($rs['gender'],1)?>
                      </select>
					   <?php }else{echo Gender($rs['gender']);}?>
                    </div>
                  </div>
                   
                  <div class="form-group">
                    <label class="control-label col-md-2">生日</label>
                    <div class="col-md-10">
                       <?php if($member_cp){?>
                    	<input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="birthday" value="<?=DateYmd($rs['birthday'],2)?>">
					   <?php }else{echo DateYmd($rs['birthday'],2);}?>
                      
                    </div>
                  </div>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                 <?php if($member_cp){?>
                  <div class="form-group">
                    <label class="control-label col-md-2">身份证号码</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="shenfenhaoma" value="<?=cadd($rs['shenfenhaoma'])?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">身份证正面</label>
                    <div class="col-md-10">
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
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">身份证背面</label>
                    <div class="col-md-10">
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
                    </div>
                  </div>
                  
                 <div class="form-group">
                    <label class="control-label col-md-2">手持证件</label>
                    <div class="col-md-10">
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
                    </div>
                  </div>
                <?php }?>     
                     
                     
                     
                     
                                   
                </div>
              </div>
               <?php }?>
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>基本资料</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->

                
                 <?php if($member_co){?>
                  <div class="form-group">
                    <label class="control-label col-md-2">QQ</label>
                    <div class="col-md-10">
                       <?php if($member_cp){?>
                       <input type="text" class="form-control input-medium"  name="qq" value="<?=cadd($rs['qq'])?>">
					   <?php }else{echo cadd($rs['qq']);}?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">微信</label>
                    <div class="col-md-10">
                      <?php if($member_cp){?>
                      <input type="text" class="form-control input-medium"  name="wx" value="<?=cadd($rs['wx'])?>">
					  <?php }else{echo cadd($rs['wx']);}?>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">邮编</label>
                    <div class="col-md-10">
                       <?php if($member_cp){?>
                     <input type="text" class="form-control input-medium"  name="zip" value="<?=cadd($rs['zip'])?>">
 					  <?php }else{echo cadd($rs['zip']);}?>
                   </div>
                  </div>
                    
                  <div class="form-group">
                    <label class="control-label col-md-2">微博</label>
                    <div class="col-md-10">
                      <?php if($member_cp){?>
                      <input type="text" class="form-control input-medium"  name="weibo" value="<?=cadd($rs['weibo'])?>">
					  <?php }else{echo cadd($rs['weibo']);}?>
                    </div>
                  </div>
                 
                  <div class="form-group">
                    <label class="control-label col-md-2">网店</label>
                    <div class="col-md-10">
                       <?php if($member_cp){?>
                       <input type="text" class="form-control input-medium"  name="store" value="<?=cadd($rs['store'])?>">
 					  <?php }else{echo cadd($rs['store']);}?>
                   </div>
                  </div>
                   <?php }?>
                 
                  <div class="form-group">
                    <label class="control-label col-md-2">头像</label>
                    <div class="col-md-10"> 
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='img';//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">备注</label>
                    <div class="col-md-10">
                      <textarea  class="form-control" rows="3" name="content"><?=cadd($rs['content'])?></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>企业资料</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">公司所属国家</label>
                    <div class="col-md-10">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="company_countries">
                        <?php Country($rs['company_countries'],1)?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">公司电话</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="company_tel" value="<?=cadd($rs['company_tel'])?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">公司名称</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="company_name" value="<?=cadd($rs['company_name'])?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">公司执照</label>
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
?> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">公司地址</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="company_add" value="<?=cadd($rs['company_add'])?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">公司业务</label>
                    <div class="col-md-10">
                      <textarea  class="form-control" rows="3" name="company_business"><?=cadd($rs['company_business'])?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">


		  <?php if(permissions('member_ed',0,$qh='manage',1)){?>
          <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled  style="margin-left:30px;"> <i class="icon-ok"></i> <?=$LG['submit']?> </button>
          <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
          <?php }?>
        <button type="button" class="btn btn-danger" onClick="goBack('c');"  style="margin-left:30px;"><i class="icon-remove"></i> 关闭窗口 </button>
        </div>
      </div>
    </div>
  </form>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/js/checkJS.php');//通用验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?> 