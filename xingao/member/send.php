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
$noper='member_le';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="发信息";
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


/*
手工发信:微信需要指定模板,因此不支持
运行超时设置,0为不限(单位为秒)
windows系统时可能无效，只能修改php.ini里的max_execution_time
*/
@set_time_limit(0);//批量处理时,速度慢,设为永不超时


//获取,处理
$lx=par($_POST['lx']);
$userid=$_REQUEST['userid'];
$groupid=$_REQUEST['groupid'];
$issys=(int)$_POST['issys'];
$file=par($_POST['file'],'',1);
$send_msg=(int)$_POST['send_msg'];
$send_mail=(int)$_POST['send_mail'];
$send_sms=(int)$_POST['send_sms'];
$title=trim($_POST['title']);
$content=trim($_POST['content']);
$tokenkey=par($_POST['tokenkey']);
$popup=spr($_POST['popup']);

if (is_array($userid)){$userid=implode(',',$userid);}
if (is_array($groupid)){$groupid=implode(',',$groupid);}
if(!is_array($groupid)&&$groupid){$groupid_arr=explode(",",$groupid);}
if ($lx=="tj")
{
	$token=new Form_token_Core();
	$token->is_token("send",$tokenkey); //验证令牌密钥
	
	if (!$userid&&!$groupid){exit ("<script>alert('请选择/填写要发送的会员组或会员ID!');goBack();</script>");}	
	if (!$send_msg&&!$send_mail&&!$send_sms){exit ("<script>alert('请选择发送类型！');goBack();</script>");}
	if (!$title){exit ("<script>alert('请填写标题!');goBack();</script>");}	
	if (!$content){exit ("<script>alert('请填写内容!');goBack();</script>");}	

	//获取发送账号
	$rc=0;$rc_msg=0;$rc_mail=0;$rc_sms=0;
	$where=" 1=1 ";
	if($groupid)	{$where.=" and groupid in ({$groupid})";}
	elseif($userid)	{$where.=" and userid in ({$userid})";}
	$query="select username,userid,email,mobile_code,mobile from member  where {$where} {$myMember}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{ 
		//单条发送:因为上万会员批量发送时可能会卡死
		$rsuserid=$rs['userid'];$rsusername=$rs['username'];
		if($rs['email']){$rsemail=$rs['email'];}else{$rsemail='';}
		if($rs['mobile']){$rsmobile=SMSApiType($rs['mobile_code'],$rs['mobile']);}else{$rsmobile='';}
		
		
		//发站内信息
		if($send_msg&&$rsuserid)
		{
			$from_userid=$Xuserid;
			$from_username=$Xusername;
			SendMsg($rsuserid,$rsusername,add($title),html($content),$file,$from_userid,$from_username,$new=1,$status=0,$issys,$xs=0,$popup);
			$Send=1;$rc_msg+=1;
		}
		
		//发短信
		if($send_sms&&$rsmobile)
		{
			SendSMS($rsmobile,$content,$xs=0);
			$Send=1;$rc_sms+=1;
		}

		//发邮件(该发送较慢所以放最后,以提高前面的发送成功率)
		if($send_mail&&$rsemail)
		{
			SendMail($rsemail,$title,$content,$file,$issys,$xs=0);
			$Send=1;$rc_mail+=1;
		}
	}
	$rc=mysqli_affected_rows($xingao);
	DelFile($file);//删除原文件
	if(!$rc)
	{
		exit ("<script>alert('没有找到会员！');goBack();</script>");
	}
	
   //返回
   if($Send)
   {
	  $ts="发送成功：共有{$rc}个会员\\n发站内信：{$rc_msg}条\\n发邮件：{$rc_mail}条\\n发短信：{$rc_sms}条";
	  $alert_color='info';
   }else{
	  $ts="发送失败：共有{$rc}个会员，没有符合发送条件！(可能是邮箱、电话未填写正确)";
	  $alert_color='danger';
   }
   
   echo "<script>alert('".$ts."');</script>";
   $token->drop_token("send"); //处理完后删除密钥
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("send");
?>

<div class="alert alert-block fade in alert_cs col-md-7" style="margin-top:0px;">
  <h3 class="page-title">
  <a href="../msg/list.php" class="gray">站内信管理</a> > 
   <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
    <?=$headtitle?>
    </a> </h3>
<?php 
XAalert(str_ireplace('\\n','；',$ts),$alert_color);
?>
  <form action="?" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="tj">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>
          发送设置
        </div>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
      </div>
      <div class="portlet-body form" style="display: block;"> 
        <!--表单内容-->
        <div class="form-group">
          <label class="control-label col-md-3">按组发送</label>
          <div class="col-md-9">
            <select multiple="multiple" class="multi-select" id="my_multi_select2" name="groupid[]">
              <?php
$query2="select groupid,groupname{$LT} from member_group where checked=1 order by  myorder desc,groupname{$LT} desc,groupid desc";
$sql2=$xingao->query($query2);
while($rs2=$sql2->fetch_array())
{
?>
              <option value="<?=$rs2['groupid']?>" <?php if($groupid_arr){if (in_array($rs2['groupid'],$groupid_arr)){echo "selected";}}?>>
              <?=$rs2['groupname'.$LT]?>
              </option>
              <?php
}
?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-3">按会员ID发送</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="userid" autocomplete="off"  value="<?=$userid?>">
            <span class="help-block">多个会员“,”隔开，如果选择了按组发送，此项设置无效</span> </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-3">发送方式</label>
          <div class="col-md-9"> 
          <!---->
          站内信
            <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="send_msg" value="1"  <?php if($send_msg||!$lx){echo 'checked';}?> />
            </div>
             <!---->
            &nbsp;&nbsp;            
            邮件
            <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="send_mail" value="1"  <?php if($send_mail){echo 'checked';}?> />
            </div>
             <!---->
            <?php if($off_sms){?>
            &nbsp;&nbsp;
            短信
            <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="send_sms" value="1"  <?php if($send_sms){echo 'checked';}?> />
            </div>
            <?php }?>
            <span class="help-block">&raquo; 发邮件时速度较慢，请耐心等待不要反复点击 <br>
&raquo; 微信不支持此类信息的推送</span>
            
          </div>
        </div>
         
       <div class="form-group">
          <label class="control-label col-md-3">自动弹出站内信</label>
          <div class="col-md-9"> 
          
            <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="popup" value="1"/>
            </div>
            <span class="help-block">除非点确认，否则每30分钟弹出一次</span>
            
          </div>
        </div>
       
        <div class="form-group">
          <label class="control-label col-md-3">发送形式</label>
          <div class="col-md-9">
          
           <select  class="form-control input-small" data-placeholder="Select..." name="issys">
             <option value="0">人工形式</option>
            <option value="1">系统形式</option>
            </select>  
            <span class="help-block">系统形式时会员不能回复</span>
          </div>
        </div>
               
        <div class="form-group">
          <label class="control-label col-md-3">标题</label>
          <div class="col-md-9 has-error">
 				<input type="text" class="form-control" name="title" value="<?=$title?>" required>

          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-3">内容</label>
          <div class="col-md-9 has-error">
           <textarea  class="form-control" rows="5" name="content" required><?=$content?></textarea>
           
          </div>
        </div>
        
        
        <div class="form-group">
                  <label class="control-label col-md-3">附件</label>
                  <div class="col-md-9">

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
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>


                   
                  </div>
                </div>
                
                
      </div>
    </div>
        
        
                
        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">





      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
      <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
      <button type="button" class="btn btn-danger" onClick="goBack('c');"  style="margin-left:30px;"><i class="icon-remove"></i> <?=$LG['close']?> </button>
    </div>
  </form>
<div class="xats">
	<strong>提示:</strong><br />
	&raquo; 发送邮件或短信时需要较慢，请等待，不要反复提交或刷新<br />
	&raquo; 批量发送时视会员数量定，可能需要花很长时间，不要关闭本页面，可以一边操作其他页面<br />
</div>

</div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
