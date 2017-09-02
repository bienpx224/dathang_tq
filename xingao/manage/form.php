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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="后台用户";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$my=par($_GET['my']);
$lx=par($_GET['lx']);
$userid=par($_GET['userid']);
if(!$lx){$lx='add';}

if($my)
{
	$lx='edit';
	$userid=$Xuserid;
}else{
	permissions('manage_ma','','manage','');//权限验证
}

if($lx=='edit')
{
	if(!$userid){exit ("<script>alert('userid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('manage','*',"userid='{$userid}'");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("manage");


?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > 
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
        <small>
        <?=cadd($rs['username'])?>
        </small> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
  <input name="lx" type="hidden" value="<?=add($lx)?>">
  <input name="my" type="hidden" value="<?=add($my)?>">
  <input name="userid" autocomplete="off"  type="hidden" value="<?=$rs['userid']?>">
  <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
            <div class="portlet">
              
              <div class="portlet-body form" style="display: block;"> 
                <!--表单内容-->
                <?php if(permissions('manage_ma','','manage',1) ){?>
                <div class="form-group">
                  <label class="control-label col-md-2">所属分类</label>
                  <div class="col-md-0 has-error">
                  <select  class="form-control input-medium select2me" data-placeholder="Select..." name="groupid">
<?php
$query2="select groupid,groupname from manage_group order by  myorder desc,groupname desc,groupid desc";
$sql2=$xingao->query($query2);
while($rs2=$sql2->fetch_array())
{
?>
            <option value="<?=$rs2['groupid']?>" <?=$rs2['groupid']==$rs['groupid']?' selected':''?>>
            <?=$rs2['groupname']?>
            </option>
            <?php
}
?>
                  </select>
              </div>
                </div>
                  <?php }?>
                
                <div class="form-group">
                  <label class="control-label col-md-2">用户名</label>
                  <div class="col-md-10 has-error">
                    <input name="username_old" type="hidden" value="<?=cadd($rs['username'])?>">
                    <input type="text" class="form-control input-medium" name="xa_name" maxlength="50" required value="<?=cadd($rs['username'])?>"><!--防自动显示-->
                    <span class="help-block">不能小于2位数,支持中文<?php if($rs['username']){?><br>注意:修改用户名时,所有系统中的旧用户名也会更新<?php }?></span>
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="control-label col-md-2">登录密码</label>
                  <div class="col-md-10">
                    <input type="text" class="form-control input-medium"  name="password" autocomplete="off"   maxlength="50" onKeyUp="check_password('留空则不修改，可输入6到20个字');">
                    <span class="help-block" id="msg_password"></span>
                    
                  </div>
                  
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-2">重复密码</label>
                  <div class="col-md-10">
				
                    <input type="text" class="form-control input-medium"  name="password2"  maxlength="50" onBlur="check_password2();"> <span class="help-block red" id="msg_password2"></span>
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
                  <label class="control-label col-md-2">姓名</label>
                  <div class="col-md-10">
                    <input type="text" class="form-control input-medium"  name="truename" value="<?=cadd($rs['truename'])?>">
                  </div>
                </div>
                
             <div class="form-group">
                  <label class="control-label col-md-2">资料</label>
                  <div class="col-md-10">
                    <textarea  class="form-control" rows="3" name="content"><?=cadd($rs['content'])?></textarea>
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





      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
      <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
    </div>
      </div>
      
      
    
    </div>
    
  </form>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/js/checkJS.php');//通用验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
