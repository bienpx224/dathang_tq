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
$pervar='tixian';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="提现";
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


//获取,处理
$lx=par($_GET['lx']);
$txid=par($_GET['txid']);
if(!$lx){$lx='add';}

$token=new Form_token_Core();
$tokenkey= $token->grante_token("tixian".$txid); //生成令牌密钥

if($lx=='edit')
{
	if(!$txid){exit ("<script>alert('txid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('tixian','*',"txid={$txid}");
}
	
if(spr($rs['status'])!=1)
{
	exit ("<script>alert('该申请已经处理过！');goBack('c');</script>");
}

//查询验证及查询会员余额
$mr=mysqli_fetch_array($xingao->query("select money,money_lock,currency from member where userid='{$rs[userid]}'"));
		
if($mr['currency']!=$rs['currency']){exit( "<script>alert('提现币种与账户币种不相同，无法处理！只能从数据库修复。');goBack();</script>");}

if($mr['money_lock']<$rs['money']){$err=1;};
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class=""><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> <small> 余额: <font class="red">
        <?=spr($mr['money'])?>
        <?=$mr['currency']?>
        </font> <font title="可能是在申请提现或其他操作中">冻结余额:</font> <font class="red">
        <?=spr($mr['money_lock'])?>
        <?=$rs['currency']?>
        </font> </small> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="txid" type="hidden" value="<?=$rs['txid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
            
           
              
              <div class="portlet">
              <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>管理操作</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
              <div class="portlet-body form" style="display: block;"> 
                <!--表单内容-->
              
                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['status']//状态?></label>
                  <div class="col-md-10 has-error">
 
<?php if($err){?>
    <input type="hidden" name="status" value="3"><?=tixian_Status(3)?>
<?php }else{?>
    <select  class="form-control input-small select2me" name="status" data-placeholder="状态">
    <?=tixian_Status(spr($rs['status']),1)?>
    </select>
<?php }?>              
                
                 <span class="help-block">
<?php 
if($err){
	echo "冻结中金额错误,无法处理 (冻结只有".$mr['money_lock'].$mr['currency'].",而申请提现需要".$rs['money'].$rs['currency']."),出现该问题可能是变更过账户币种，只能设为【".tixian_Status(3)."】！";
}else{
	echo '注意选择,此修改会影响会员金额状态;<br>提现完成:扣除冻结金额; 拒绝提现:解除冻结金额;';
}
?>              
                 </span>
                  </div>
                </div>
                 
                 
                 <div class="form-group">
                  <label class="control-label col-md-2">回复</label>
                  <div class="col-md-10">
                   <textarea name="old_reply" style="display:none;"><?=cadd($rs['reply'])?></textarea>
                   <textarea  class="form-control" rows="3" name="reply"><?=cadd($rs['reply'])?></textarea>
                  </div>
                </div>
                
              </div>
            </div>
            
            <!---->
            
            <?php if(!$err){?>
              <div class="portlet">
              <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>提现资料</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                      
                  <div class="form-group">
                    <div class="control-label col-md-2 right">提现</div>
                    <div class="col-md-10">
                      <?=$rs['money']?> <?=$rs['currency']?>
                    </div>
                  </div>
                  
                  
                  
                  <div class="form-group">
                    
                  </div>
                  
                  <div class="form-group">
                    <div class="control-label col-md-2 right">银行</div>
                    <div class="col-md-10">
                      <?=cadd($rs['bank'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">姓名</div>
                    <div class="col-md-10">
                      <?=cadd($rs['name'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">账号</div>
                    <div class="col-md-10">
                      <?=cadd($rs['account'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">开户行</div>
                    <div class="col-md-10">
                      <?=cadd($rs['address'])?>
                    </div>
                  </div>
                 
             
                  <div class="form-group">
                    <label class="control-label col-md-2">会员备注</label>
                    <div class="col-md-10">
                      <?=cadd($rs['content'])?>
                    </div>
                  </div>
                </div>
              </div>
              <?php }?>
              
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
      </div>
    </div>
  </form>

	<div class="xats">
	<strong>操作流程:</strong><br>
	&raquo; 检查信息错误 > 修改此页的状态为(<?=tixian_Status(3)?>) > 完成<br>
	&raquo; 检查信息正确 > 转账给会员银行账号 > 修改此页的状态为(<?=tixian_Status(2)?>) > 完成<br>
	
	</div>
</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
