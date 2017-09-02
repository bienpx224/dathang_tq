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
$pervar='coupons';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="优惠券/折扣券";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$cpid=par($_GET['cpid']);
if(!$lx){$lx='add';}


//注意:字段有修改时,也要同步修改xingao\config\form.php  推广赠送功能


//不用修改功能
/*if($lx=='edit')
{
	if(!$cpid){exit ("<script>alert('cpid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('coupons','*',"cpid='{$cpid}'");
	if(!$rs['cpid']){exit ("<script>alert('找不到信息！');goBack();</script>");}
}
*/

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("coupons");

?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="cpid" type="hidden" value="<?=$rs['cpid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">

              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>优惠券/折扣券 设置</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">券类型</label>
                    <div class="col-md-4 has-error">
                     <select  class="form-control input-small select2me" name="types" data-placeholder="类型" required>
                 	 <?=Coupons_Types('',1)?>
                  	 </select>
                    </div>
                  
                    <label class="control-label col-md-2">可使用系统</label>
                    <div class="col-md-4 has-error">
                     <select  class="form-control input-small select2me" name="usetypes" data-placeholder="可使用类型" required>
                 	 <?=Coupons_usetypes('',1)?>
                  	 </select>
                    </div>
                  </div>
                
                  <div class="form-group">
                    <label class="control-label col-md-2">价值</label>
                    <div class="col-md-4 has-error">
                   <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="面值金额或折扣额" name="value" required><?=$XAmc?>/折
                    </div>

                    <label class="control-label col-md-2">最低消费金额</label>
                    <div class="col-md-4 has-error">
                   <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="多少<?=$XAmc?>消费金额才能使用" name="limitmoney" required value="100"><?=$XAmc?>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">兑换码</label>
                    <div class="col-md-4 has-error">
                   <input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="生成多少个兑换码(数量:1-1000个)" name="code_number" required value="1">个
                   <input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="兑换码位数:8-30位" name="code_digits" required value="8" style="margin-left:20px;">位
                   <span class="help-block">
                    <font class="red2">注意:如果下面设置分配给会员,是表示每个会员获得的兑换码数量</font>
                   </span>
                    </div>
                  
                  
                    <label class="control-label col-md-2">张数</label>
                    <div class="col-md-4 has-error">
                   <input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="1-100之间" name="number" required value="1">张
                
					<span class="help-block">
                    该券可以使用多少次<br>
                    <font class="red2">注意:如果下面设置分配给会员,是表示每个会员获得的张数</font>
                    </span>
                    </div>
                  </div>
                   
                  <div class="form-group">
                    <label class="control-label col-md-2">过期时间</label>
                    <div class="col-md-10">
                      	<input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="duetime1">
					
						<input type="text" id="clockface_2"  name="duetime2" class="form-control input-xsmall" readonly style="margin-right:0px;">
						<button class="btn btn-default" type="button" id="clockface_2_toggle"><i class="icon-time"></i></button> <span class="help-block">留空则永久有效</span>
                    </div>
                  </div>
                 
                
                  <div class="form-group">
                    <label class="control-label col-md-2">内容</label>
                    <div class="col-md-10">
                      <textarea  class="form-control" rows="5" name="content" placeholder="优惠券/折扣券内容备注"></textarea>
                    </div>
                  </div>
                  
                </div>
              </div>
              
              <!---->
              <div class="portlet">
      <div class="portlet-title">
        <div class="caption"><i class="icon-reorder"></i>
          <strong>分配给会员</strong> (以下可以不设置,会员就可以用兑换码自行兑换)
        </div>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
      </div>
      <div class="portlet-body form" style="display: block;"> 
        <!--表单内容-->
        <div class="form-group">
          <label class="control-label col-md-2">按组分配</label>
          <div class="col-md-10">
            <select multiple="multiple" class="multi-select" id="my_multi_select2" name="groupid[]">
              <?php
$query2="select groupid,groupname{$LT} from member_group where checked=1 order by  myorder desc,groupname{$LT} desc,groupid desc";
$sql2=$xingao->query($query2);
while($rs2=$sql2->fetch_array())
{
?>
              <option value="<?=$rs2['groupid']?>" <?php if($groupid_arr){if (in_array($rs2['groupid'],$groupid_arr)){echo "selected";}}?>><?=$rs2['groupname'.$LT]?></option>
              <?php
}
?>
            </select>
            <span class="help-block">将会分配给该组里的所有会员</span>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2">按会员ID分配</label>
          <div class="col-md-10">
            <input type="text" class="form-control" name="userid" autocomplete="off"  value="<?=$userid?>">
            <span class="help-block">多个会员“,”隔开，如果选择了按组发送，此项设置无效</span> </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2">发送通知</label>
          <div class="col-md-10"> 
          <!---->
          站内信
            <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="send_msg" value="1" checked />
            </div>
             <!---->
            &nbsp;&nbsp;            
            邮件
            <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="send_mail" value="1" />
            </div>
             <!---->
            <?php if($off_sms){?>
            &nbsp;&nbsp;
            短信
            <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="send_sms" value="1" />
            </div>
            <?php }?>
            <span class="help-block">&raquo; 发邮件时速度较慢，请耐心等待不要反复点击 <br>
&raquo; 微信不支持此类信息的推送</span>
            
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-2">通知标题</label>
          <div class="col-md-10">
              <input type="text" class="form-control" name="send_title" value="恭喜:您获得了优惠券/折扣券">
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-2">通知内容</label>
          <div class="col-md-10">
           <textarea  class="form-control" rows="5" name="send_content">您好,工作人员赠给您优惠券/折扣券,具体请登录[<?=$sitename?>]查看!</textarea>
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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
