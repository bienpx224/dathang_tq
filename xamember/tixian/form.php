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
$pervar='off_tixian';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['name.nav_47'];//提现
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');


//获取,处理
$lx=par($_GET['lx']);
$txid=par($_GET['txid']);
if(!$lx){$lx='add';}



//验证,查询
if(!$txid&&$lx=='edit')
{
	exit ("<script>alert('txid{$LG['pptError']}');goBack();</script>");
}

//验证提现数量
$count_tixian1=mysqli_num_rows($xingao->query("select userid from tixian  where  addtime>=".strtotime('-1 Month')."  and status='1' {$Mmy} "));
$count_tixian2=mysqli_num_rows($xingao->query("select userid from tixian  where  addtime>=".strtotime('-1 Month')."  and status='2' {$Mmy} "));
$count_tixian3=mysqli_num_rows($xingao->query("select userid from tixian  where  addtime>=".strtotime('-1 Month')."  and status='3' {$Mmy} "));

$tixian_sl=$member_per[$Mgroupid]['tixian_sl'];
$tixian_sl_now=RepPIntvar($tixian_sl-$count_tixian2-$count_tixian1);

$ts_tixian=LGtag($LG['tixian.form_7'],
	'<tag1>=='.$tixian_sl.'||'.
	'<tag2>=='.$count_tixian2.'||'.
	'<tag3>=='.$count_tixian1.'||'.
	'<tag4>=='.$count_tixian3.'||'.
	'<tag5>=='.$tixian_sl_now
 );
 
if($tixian_sl_now<=0){
 exit ("<script>alert('".$ts_tixian."');goBack();</script>");
}

//查询会员余额
$tixian_xiao=$member_per[$Mgroupid]['tixian_xiao'];
$mr=FeData('member','money,money_lock',"1=1 {$Mmy}");
if($mr['money']<$tixian_xiao)
{
	exit ("<script>alert('{$LG['tixian.form_1']}');goBack();</script>");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("tixian");
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a>   
             
    <small> 
        <?=$LG['money']?>:<font class="red"><?=spr($mr['money'])?> <?=$Mcurrency?></font> 
        <?php if($mr['money_lock']>0){?>
        <font class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['tixian.form_2'];//可能是在申请提现或其他操作中?>">
        <?=$LG['money_lock']?>:<font class="red"><?=spr($mr['money_lock'])?> <?=$Mcurrency?></font> 
        </font> 
        <?php }?>
    </small>
        
</h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="txid" type="hidden" value="<?=$rs['txid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['tixian.tixian_zhid'];//提现帐号?></label>
                    <div class="col-md-10 has-error">
                      <select name="tixian_zhid" class="form-control input-xlarge select2me" data-placeholder="Select...">
                        <?php
$query2="select * from tixian_zh where checked='1' {$Mmy} order by bank desc";
$sql2=$xingao->query($query2);
while($rs2=$sql2->fetch_array())
{
	echo '<option value="'.$rs2['txzhid'].'" >'.$rs2['bank'].' ('.$LG['tixian.form_8'].$rs2['name'].' '.$LG['tixian.form_9'].$rs2['account'].')</option>';
}
?>
                      </select>
                      <span class="help-block">
                      &raquo; <a href="../tixian_zh/form.php"><?=$LG['tixian.form_3']?></a><br>
                      &raquo; <?=LGtag($LG['tixian.form_11'],'<tag1>=='.$Mcurrency);?><br>
                      </span> 
                      
                      </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['name.nav_47'];//提现?></label>
                    <div class="col-md-10 has-error">
                      <input type="text" class="form-control input-small" name="money"  required>
                      <?=$Mcurrency?>
                    </div>
                  </div>
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['tixian.form_6'];//提现密码?></label>
                    <div class="col-md-10 has-error">
                      <input name="tixianpassword" type="password" autocomplete="off" required class="form-control input-medium"/> <input type="password" style="display:none" /> <!--这个只是为了防止某些浏览器自动填写表单-->
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['note']?></label>
                    <div class="col-md-10">
                      <textarea  class="form-control" rows="3" name="content"><?=cadd($rs['content'])?>
</textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>        
        <!--提交按钮固定--> 
		<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
 
        <div align="center" class="fixed_btn" id="Autohidden">
        
        
<button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
          <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
        </div>
      </div>
      
    </div>
  </form>

<div class="xats"> &raquo;
        <?=$ts_tixian?> <br />
       
        &raquo;  
        <?=LGtag($LG['tixian.form_12'],
			'<tag1>=='.$tixian_xiao.'||'.
			'<tag2>=='.$Mcurrency
		 );?> <br />


        
        &raquo; <?=$LG['tixian.form_10'];//提交后将不可修改，请检查好?><br />
         </div>
</div>
<script>
/*js保留2位小数*/
changeTwoDecimal= function changeTwoDecimal(floatvar)
{
var f_x = parseFloat(floatvar);
if (isNaN(f_x))
{
alert('function:changeTwoDecimal->parameter error');
return false;
}
var f_x = Math.round(floatvar*100)/100;
return f_x;
}

</script>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
