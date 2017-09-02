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
$noper='member_de';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="会员扣费";
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$new_money=spr($_POST['new_money']);
$type=par($_POST['type']);
$fromtable=add($_POST['fromtable']);
$fromid=par($_POST['fromid']);
$title=add($_POST['title']);
$content=add($_POST['content']);
$lx=par($_POST['lx']);
$username=par($_REQUEST['username']);
$tokenkey=par($_POST['tokenkey']);
$currency=par($_POST['currency']);

if ($lx=='tj')
{ 
	$token=new Form_token_Core();
	$token->is_token('money_kf',$tokenkey); //验证令牌密钥
	if (!$type){exit ("<script>alert('请选择扣费类型！');goBack();</script>");}	
	if (!$username){exit ("<script>alert('请输入会员名或会员ID！');goBack();</script>");}	
	if ($new_money<=0){exit ("<script>alert('扣费必须大于0！');goBack();</script>");}	
	if (!$currency){exit ("<script>alert('请选择币种！');goBack();</script>");}	

	//获取用户名和用户ID
	if($username)
	{
		$query="select username,userid,money,groupid,currency from member  where userid='".$username."' or username='".$username."'  {$myMember}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{ 
			 if($currency==1){$currency=$rs['currency'];}
			 
			 //扣费
			 MoneyKF($rs['userid'],$fromtable,$fromid,$fromMoney=$new_money,$fromCurrency=$currency,
			 $title,$content,$type,$tally=0,$operator=$Xuserid);
			 
			 $mr=FeData('member','money,currency',"userid='{$rs['userid']}'");
			 
			 $ts= "扣费成功，已扣<font class=red>".$new_money."</font>".$XAmc."，目前账户有".spr($mr['money']).$mr['currency'];
			 if(MemberSettlement('',$rs['groupid'])){
				 $ts.="，会员是月结类型";
			 }
			 $ts.= ' <a href="money_kfbak.php?so=1&key='.$rsusername.'" target="_blank">扣费记录</a>';
			 $token->drop_token("money_kf"); //处理完后删除密钥
		}
		if(mysqli_affected_rows($xingao)<=0){
			echo "<script>alert('会员名或会员ID错误！');</script>";
		}
	}
	
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("money_kf");

?>
<script type="text/javascript">
function checkname(username) 
{
	var xmlhttp_checkname=createAjax(); 
	if (xmlhttp_checkname) 
	{  
		var span=document.getElementById('check');
		xmlhttp_checkname.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp_checkname.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp_checkname.send('lx=cz&username='+username+'');
	
		xmlhttp_checkname.onreadystatechange=function() 
		{  
			if (xmlhttp_checkname.readyState==4 && xmlhttp_checkname.status==200) 
			{ 
				span.innerHTML=unescape(xmlhttp_checkname.responseText); 
			}
		}
	}
}
</script>

<script type="text/javascript">
	window.onload=function()
	{ 
		if(document.readyState=="complete")
		{
		  	document.getElementById("username").focus(); 
			document.getElementById("username").select(); 
		}
	}
</script>

<div class="alert alert-block fade in alert_cs col-md-5" style="margin-top:30px;">
  <h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
    <?=$headtitle?>
    </a> </h3>
  <form action="?" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="tj">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="portlet">
      <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>
          <?=$ts?>
        </div>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
      </div>
      <div class="portlet-body form" style="display: block;"> 
        <!--表单内容-->
        
        <div class="form-group">
          <label class="control-label col-md-3">会员名或ID</label>
          <div class="col-md-9 has-error">
            <input type="text" class="form-control input-medium" name="username" autocomplete="off" required value="<?=$username?>" id="username" onkeyup="checkname(this.value);" onafterpaste="checkname(this.value);">
            <span id="check" class="help-block"></span> </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-3">扣费</label>
          <div class="col-md-9  has-error">
<input name="new_money" class="form-control  input-small"  maxlength="8" type="text" onkeyup="value=value.replace(/[^\d.]/g,'')" required  style="float:left;"/>
            
<select name="currency" class="form-control input-msmall select2me" required data-placeholder="请选币种"  style="float:left;">
<?=openCurrency('',2,1)?>
</select>
           
            
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-3">扣费类型</label>
          <div class="col-md-9  has-error">
            <select name="type"  class="form-control input-small select2me" required>
              <option value="" selected> </option>
               <?php money_kf('',1)?>
            
            </select>
          </div>
        </div>
               
         <div class="form-group">
          <label class="control-label col-md-3">扣费原因</label>
          <div class="col-md-9">
             <select  class="form-control input-small select2me" name="fromtable" data-placeholder="用途" >
             <option></option>
             <?=fromtableName(par($_GET['fromtable']),1)?>
             </select>
            <input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="左侧所选的信息ID" name="fromid" size="6" value="<?=par($_GET['fromid'])?>">
            
            <span class="help-block">如果有请认真填写,此填写将影响月结金额和数据统计结果</span>
          </div>
        </div>
       <div class="form-group">
          <label class="control-label col-md-3">原因</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="title" value="<?=par($_GET['title'])?>">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-3">备注</label>
          <div class="col-md-9">
            <textarea type="text" class="form-control" name="content"><?=cadd(BrToTextarea($_GET['content']))?></textarea>
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
</div>
</div>
<script>
window.onload=function(){
	checkname(document.getElementById('username').value);
}
</script>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
