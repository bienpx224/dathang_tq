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
$pervar='member_ma';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="会员权限";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$groupid=spr($_GET['groupid']);//必须spr
if(!$lx){$lx='add';}
if($lx=='edit')
{
	if(!$groupid){exit ("<script>alert('groupid{$LG['pptError']}');goBack();</script>");}
}
if($groupid)
{
	$rs=FeData('member_group','*',"groupid='{$groupid}'");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("member_group");

?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> <small>
        <?=cadd($rs['groupname'.$LT])?>
        </small> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="groupid" type="hidden" value="<?=$rs['groupid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>基本资料</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                      <tr class="odd gradeX">
                        <td width="150" align="right">组/分类/等级名称</td>
                        <td>
<?php 
//语言字段处理++
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		?>
        <input name="groupname<?=$language?>" class="form-control input-medium input_txt_red tooltips" data-container="body" data-placement="top" data-original-title="<?=languageType($language)?> 名称" type="text" value="<?=cadd($rs['groupname'.$language])?>" size="50" required style="margin-top:10px;" ><br>

		<?php 
	}
}
?>
                          
                          
                          </td>
                      </tr>
                      
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">排序</td>
                        <td><input type="text" class="form-control input-xsmall" name="myorder" value="<?=cadd($rs['myorder'])?>">
                        <span class="help-block">越大越排前</span></td>
                      </tr>
                      
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">开通使用</td>
                        <td><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="checked" value="1"  <?php if($rs['checked']||$lx=='add'){echo 'checked';}?> />
                          </div></td>
                      </tr>
                      <tr class="odd gradeX">
                        <td width="150" align="right">开通注册</td>
                        <td><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="regchecked" value="1"  <?php if($rs['regchecked']||$lx=='add'){echo 'checked';}?> />
                          </div></td>
                      </tr>
                      <tr class="odd gradeX">
                        <td width="150" align="right">企业类型</td>
                        <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="off_company" value="1" <?=$rs['off_company']?'checked':''?> />
                          </div>
						  <span class="help-block">有企业资料表单</span>
						  </td>
                      </tr>
                      <tr class="odd gradeX">
                        <td width="150" align="right">月结</td>
                        <td align="left">
                        <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="off_settlement" value="1" <?=$rs['off_settlement']?'checked':''?> />
                       		</div>
						  <span class="help-block">先记账到月底再结算</span>
						  </td>
                      </tr>
                      
                    <tr class="odd gradeX">
                        <td width="150" align="right">包裹系统</td>
                        <td align="left">
                           <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                       		<input type="checkbox" class="toggle" name="ON_Mbaoguo" value="1" <?=$lx=='add'||$rs['ON_Mbaoguo']?'checked':''?> />
                       		</div>
						  </td>
                      </tr>
                      
                     <tr class="odd gradeX">
                        <td width="150" rowspan="2" align="right">直接下单/批量导入</td>
                        <td align="left"><div class="make-switch tooltips" data-container="body" data-placement="top" data-original-title="是否开启【直接下单/批量导入】功能" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="off_zjxd" value="1"  <?=$rs['off_zjxd']?'checked':''?> />
                          </div>
                          <span class="help-block">一般用于企业类型会员 自送、寄送、申请上门取件时下单发货</span></td>
                      </tr>
                     <tr class="odd gradeX">
                       <td align="left">
                       <div class="make-switch tooltips" data-container="body" data-placement="top" data-original-title="直接下单/批量导入时 是否按预估重量计费" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="off_zjxd_calc" value="1"  <?=$rs['off_zjxd_calc']?'checked':''?> />
                          </div>
                          <span class="help-block">
&raquo; 按预估重量自动计费，方便会员预先支付。待包裹入库后再次称重，如重量有误差则再补扣差费或退差费<br>
<!--&raquo; 计费成功时状态将变成为【<?=status_name(3)?>】 -->                   
                          </span>
                       
                       </td>
                     </tr>
                      
                     
					  
					  <tr class="odd gradeX">
					    <td align="right">运单打印功能</td>
					    <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
					      <input type="checkbox" class="toggle" name="off_print" value="1" <?=$rs['off_print']?'checked':''?> />
					      </div></td>
				      </tr>
					  <tr class="odd gradeX">
                        <td width="150" align="right">上门取件功能</td>
                        <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="off_qujian" value="1" <?=$rs['off_qujian']?'checked':''?> />
                          </div></td>
                      </tr>
                      
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">申请理赔功能</td>
                        <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="off_lipei" value="1" <?=$rs['off_lipei']?'checked':''?> />
                          </div></td>
                      </tr>
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">申请提现功能</td>
                        <td align="left"><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="off_tixian" value="1" <?=$rs['off_tixian']?'checked':''?> />
                          </div>
						  &nbsp;&nbsp;
						  每次最少要提现<input type="text" name="tixian_xiao" value="<?=cadd($rs['tixian_xiao'])?>" size="5"/>
                          (会员各自币种)
						  ；
						  每月可提现次数<input type="text" name="tixian_sl" value="<?=cadd($rs['tixian_sl'])?>" size="5"/>
                          次 
						  </td>
                      </tr>
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">充值赠送</td>
                        <td align="left">
<textarea class="form-control input-medium" name="zengsong" rows="8" ><?=cadd($rs['zengsong'])?></textarea>
<span class="help-block">               
    一行一个，顺序必须从小到大，否则计算错误(单位:<?=$XAmc?>)<br>
    格式：充值数额=赠送数额<br> 
    如：<br>
    100=10<br>
    200=25<br>
</span>
						</td>
                      </tr>
                      
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">可以自行升级到会员组</td>
                        <td align="left">

<select multiple="multiple" class="multi-select" id="my_multi_select2" name="up_groupid[]">
<!--id="my_multi_select2" 不能改-->
<?php
$up_groupid=$rs['up_groupid'];
if(!is_array($up_groupid)&&$up_groupid){$up_groupid=explode(",",$up_groupid);}//转数组

$query_up="select groupid,groupname{$LT} from member_group where checked=1 and groupid<>'{$groupid}' order by  myorder desc,groupname{$LT} desc,groupid desc";
$sql_up=$xingao->query($query_up);
while($up=$sql_up->fetch_array())
{
?>
	<option value="<?=$up['groupid']?>" <?php if($up_groupid&&in_array($up['groupid'],$up_groupid)){echo 'selected';}?>><?=$up['groupname'.$LT]?></option>
<?php
}
?>
</select>

						  </td>
                      </tr>
                      <tr class="odd gradeX">
                        <td width="150" align="right">升级方式</td>
                        <td align="left">
							用积分购买<input type="text" name="up_groupid_integral" value="<?=spr($rs['up_groupid_integral'])?>" size="5"/>分；
							单次充值数额<input type="text" name="up_groupid_max_cz_once" value="<?=spr($rs['up_groupid_max_cz_once'])?>" size="5"/><?=$XAmc?> <span class="gray2">(后台充值也算)</span>；
							累计充值数额<input type="text" name="up_groupid_max_cz_more" value="<?=spr($rs['up_groupid_max_cz_more'])?>" size="5"/><?=$XAmc?> <span class="gray2">(后台充值也算)</span>	
							
							<span class="help-block">&raquo; 用其中一种方式即可升级，填写-1表示不使用该方式 <br>
							&raquo; 在后台转移会员组后，会员充值数额的记录会重新统计 <br>
</span>						
						  </td>
                      </tr>

                    </tbody>
                  </table>
                </div>
              </div>
              <!---->
              
              
            <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>代购</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                      
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">代购系统</td>
                        <td align="left"><div class="make-switch tooltips" data-container="body" data-placement="top" data-original-title="关闭后该会员组则不可使用代购系统" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="daigou" value="1" <?=$rs['daigou']?'checked':''?> />
                          </div></td>
                      </tr>
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">代购手续费率（线上电商）</td>
                        <td align="left">
                        <input name="dg_serviceRateWeb" type="text" value="<?=spr($rs['dg_serviceRateWeb'])?>" size="5" class="tooltips" data-container="body" data-placement="top" data-original-title="0-100" onBlur="limitNumber(this,'0,100','2');">%
                        </td>
                      </tr>
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">代购手续费率（线下专柜）</td>
                        <td align="left">
                        <input name="dg_serviceRateShop" type="text" value="<?=spr($rs['dg_serviceRateShop'])?>" size="5" class="tooltips" data-container="body" data-placement="top" data-original-title="0-100" onBlur="limitNumber(this,'0,100','2');">%
                        </td>
                      </tr>
                      
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">其他服务收费</td>
                        <td align="left">
<?php for ($i=1; $i<=4; $i++){?>
    <?=daigou_memberStatus($i)?><input name="dg_serviceFee_<?=$i?>" type="text" value="<?=spr($rs['dg_serviceFee_'.$i])?>" size="5" onBlur="limitNumber(this,'0,100','2');"><?=$XAmc?>
<span class="xa_sep"> | </span>
<?php }?>                       
                       
                        <span class="help-block"> 会员主动申请时扣费，无法处理时自动退费。</span>
                       </td>
                      </tr>
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">寄库运费</td>
                        <td align="left">
<table>
    <tr>
      <td valign="top">
<textarea class="form-control input-medium" name="dg_freightFee" rows="10" ><?php
$dg_freightFee=cadd($rs['dg_freightFee']);

$arr=ToArr($dg_openCurrency);
if($arr)
{
	foreach($arr as $arrkey=>$value)
	{
		$r=GetArrVar($value,$dg_freightFee);
		echo $value."=".spr($r[1]).'
';
	}
}

?></textarea>
      </td>
      <td valign="top">&nbsp;</td>
      <td valign="top">
<span class="help-block"> 
	&raquo; 代购价格用哪种币种,就自动用哪种币种的运费<br>
	&raquo; 此运费是默认运费，会员还可自行修改 (按实际收费)<br>
    &raquo; 前面内容“币种=”不可更改，如要修改或添加币种请在系统设置中操作 (币种修改后,所有会员组的此处也要修改)<br>  
    
    <br>
    正常设置，如：<br>
    CNY=10<br>
    USD=5<br>
    JPY=100<br>
    
</span>
      
      </td>
    </tr>
</table>                        
                        
                    
                        </td>
                      </tr>
                      
                      <tr class="odd gradeX">
                        <td width="150" align="right">品牌折扣</td>
                        <td align="left">
<table>
    <tr>
      <td valign="top">
<textarea class="form-control input-medium" name="dg_brandDiscount" rows="10" ><?php

$dg_brandDiscount=cadd($rs['dg_brandDiscount']);

$query_cf="select classid,name{$LT} from classify where classtype='6' and checked=1 and bclassid<>0";
$sql_cf=$xingao->query($query_cf);
while($cf=$sql_cf->fetch_array())
{
	$r=GetArrVar($cf['classid'],$dg_brandDiscount);

	$name=str_ireplace('=','',cadd($cf['name'.$LT]));
	echo $cf['classid']."={$name}=".spr($r[2]).'
';
	
}

?></textarea>
      </td>
      <td valign="top">&nbsp;</td>
      <td valign="top">
<span class="help-block"> 
    &raquo; 前面内容“id=名称=”不可更改，如要修改或添加请在【<a href="/classify/list.php?so=1&classtype=6" target="_blank">分类管理</a>】操作<br>  
	&raquo; 只可修改折扣，数值0-10(不可超过10，否则是提高费用)，比如打98折则写9.8；10表示无折扣；0表示禁用该品牌<br>
    
    <br>
    正常设置，如：<br>
    10=三星=9<br>
    11=华为=9.8<br>
    12=苹果=10<br>
    <br>

    禁用某品牌，如：<br>
    10=三星==0<br>
    11=华为==0<br>
    
</span>
      
      </td>
    </tr>
    
    
</table>
                        
                        
                        
						</td>
                      </tr>
                      
                      <?php if ($ON_ware){ ?>
                      <tr class="odd gradeX">
                        <td width="150" align="right">仓储</td>
                        <td><strong>免费
                          <input name="dg_ware_freeDays" type="text"  value="<?=$rs['dg_ware_freeDays']?>" size="5">
                          天，超过后按以下公式收费：</strong>
                          <a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="全部入库后开始算时间，对部分入库代购单的商品发货时不收仓储费<br>使用优先从前到后，在下单发货时才算仓储费"> <i class="icon-info-sign"></i> </a>
                          
                          <div class="xa_border"></div>

                          
                          单件物品体积超过<input name="dg_ware_volumeLimit" type="text"  value="<?=spr($rs['dg_ware_volumeLimit'])?>" size="5"><?=$XAsz?>(立方)时,按体积收<input name="dg_ware_volumePrice" type="text"  value="<?=spr($rs['dg_ware_volumePrice'])?>" title="填0则不按此公式收费" size="5"><?=$XAmc?>/1<?=$XAsz?>(立方)/天
                         <div class="xa_border"></div>

                          
                          单件物品重量超过<input name="dg_ware_weightLimit" type="text"  value="<?=spr($rs['dg_ware_weightLimit'])?>" size="5"><?=$XAwt?>时,按重量收<input name="dg_ware_weightPrice" type="text"  value="<?=spr($rs['dg_ware_weightPrice'])?>" title="填0则不按此公式收费" size="5"><?=$XAmc?>/1<?=$XAwt?>/天
                         <div class="xa_border"></div>
                          
                          以上都不超过则按数量收<input name="dg_ware_numberPrice" type="text"  value="<?=spr($rs['dg_ware_numberPrice'])?>" title="填0则不按此公式收费" size="5"><?=$XAmc?>/1个/天
						  
						  </td>
                      </tr>
                      <?php }?>
                      
                      
					<tr class="odd gradeX">
                        <td colspan="2" align="right"> </td>
                      </tr>
					
                      <tr class="odd gradeX">
                        <td width="160" align="right">合箱下单发货种数限制</td>
                        <td>
                            <input name="dg_DeliveryLimitNumber" type="text" value="<?=spr($rs['dg_DeliveryLimitNumber'])?>" size="5" title="0则不限"/>
                        
                          种&nbsp;&nbsp; <span class="gray2">一次最多可以多少种商品合箱发货</span></td>
                      </tr>
                      
                      <tr class="odd gradeX">
                        <td width="160" align="right">合箱下单发货重量限制</td>
                        <td><input name="dg_DeliveryLimitWeight" type="text" value="<?=spr($rs['dg_DeliveryLimitWeight'])?>" size="5" title="0则不限"/>
                          <?=$XAwt?></td>
                      </tr>

                    </tbody>
                  </table>
                </div>
              </div>              
              
              
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>包裹</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                    <!--固定显示-开始 (与运单设置相同,所以显示出来)-->
                      <tr class="odd gradeX">
                        <td width="160" align="right">单合箱</td>
                        <td>在
                          <input name="Price_hxsl" type="text"  value="<?=$rs['Price_hxsl']?>" size="5">
                          个内
                          <input name="Price_hx1" type="text"  value="<?=$rs['Price_hx1']?>" size="5">
                          <font class="gray2">
                          <?=$XAmc?>
                          /个包裹</font>，超过为
                          <input name="Price_hx2" type="text"  value="<?=$rs['Price_hx2']?>" size="5">
                          <font class="gray2">
                          <?=$XAmc?>
                          /个包裹</font> &nbsp;
                          </td>
                      </tr>
					   <!--固定显示-结束-->
					   
                      <?php if ($off_fx){ ?>
                      <tr class="odd gradeX">
                        <td width="160" align="right">分箱</td>
                        <td>在
                          <input name="Price_fxsl" type="text"  value="<?=$rs['Price_fxsl']?>" size="5">
                          个内
                          <input name="Price_fx1" type="text"  value="<?=$rs['Price_fx1']?>" size="5">
                          <font class="gray2">
                          <?=$XAmc?>
                          /个包裹</font>，超过为
                          <input name="Price_fx2" type="text"  value="<?=$rs['Price_fx2']?>" size="5">
                          <font class="gray2">
                          <?=$XAmc?>
                          /个包裹</font></td>
                      </tr>
                      <?php }?>
                      <?php if ($off_baoguo_op_02){ ?>
                      <tr class="odd gradeX">
                        <td width="160" align="right">验货</td>
                        <td><input name="Price_02" type="text"  value="<?=$rs['Price_02']?>" size="10">
                          <font class="gray2">
                          <?=$XAmc?>
                          /包裹 </font></td>
                      </tr>
                      <?php }?>
                      <?php if ($off_baoguo_op_09){ ?>
                      <tr class="odd gradeX">
                        <td width="160" align="right">清点</td>
                        <td><input name="Price_09" type="text"  value="<?=$rs['Price_09']?>" size="10">
                          <font class="gray2">
                          <?=$XAmc?>
                          /包裹 </font></td>
                      </tr>
                      <?php }?>
                      <?php if ($off_baoguo_op_06){ ?>
                      <tr class="odd gradeX">
                        <td width="160" align="right">拍照</td>
                        <td><input name="Price_06" type="text"  value="<?=$rs['Price_06']?>" size="10">
                          <font class="gray2">
                          <?=$XAmc?>
                          /包裹 </font></td>
                      </tr>
                      <?php }?>
                      <?php if ($off_baoguo_op_10){ ?>
                      <tr class="odd gradeX">
                        <td width="160" align="right">复称</td>
                        <td><input name="Price_10" type="text"  value="<?=$rs['Price_10']?>" size="10">
                          <font class="gray2">
                          <?=$XAmc?>
                          /包裹 </font></td>
                      </tr>
                      <?php }?>
                      <?php if ($off_baoguo_th){ ?>
                      <tr class="odd gradeX">
                        <td width="160" align="right">退货手续费</td>
                        <td><input name="Price_th" type="text"  value="<?=$rs['Price_th']?>" size="10">
                          <font class="gray2">
                          <?=$XAmc?>
                          /包裹 (其他费用如邮费,由后台单独扣除)</font></td>
                      </tr>
                      <?php }?>
                      <?php if ($off_baoguo_op_07){ ?>
                      <tr class="odd gradeX">
                        <td width="160" align="right">减重</td>
                        <td><input name="Price_07" type="text"  value="<?=$rs['Price_07']?>" size="10">
                          <font class="gray2">
                          <?=$XAmc?>
                          /包裹 </font></td>
                      </tr>
                      <?php }?>
                      <?php if ($off_baoguo_op_04){ ?>
                      <tr class="odd gradeX">
                        <td width="160" align="right">转移仓库</td>
                        <td><input name="Price_04" type="text"  value="<?=$rs['Price_04']?>" size="10">
                          <font class="gray2">
                          <?=$XAmc?>
                          / <?=$XAwt?> (没有重量时,按首重收费)</font></td>
                      </tr>
                      <?php }?>
                      <?php if ($off_baoguo_op_11){ ?>
                      <tr class="odd gradeX">
                        <td width="160" align="right">填空隙</td>
                        <td><input name="Price_11" type="text"  value="<?=$rs['Price_11']?>" size="10">
                          <font class="gray2">
                          <?=$XAmc?>
                          /包裹 </font></td>
                      </tr>
                      <?php }?>
                      <?php if ($ON_ware){ ?>
                      <tr class="odd gradeX">
                        <td width="160" align="right">仓储</td>
                        <td>免费
                          <input name="bg_ware_freeDays" type="text"  value="<?=$rs['bg_ware_freeDays']?>" size="5">
                          <font class="gray2">天</font>，超过后 每
						  <input name="bg_ware_weight" type="text"  value="<?=spr($rs['bg_ware_weight'])?>" size="5" title="写0则不按重量收费">
                          <?=$XAwt?>

                          <input name="bg_ware_price" type="text"  value="<?=spr($rs['bg_ware_price'])?>" size="10"><?=$XAmc?>/天
                       
						  </td>
                      </tr>
                      <?php }?>
                      
					<tr class="odd gradeX">
                        <td colspan="2" align="right"> </td>
                      </tr>
					<tr class="odd gradeX">
                        <td width="160" align="right">分箱下单发货数量限制</td>
                        <td>
                            <input name="baoguo_fx" type="text" value="<?=$rs['baoguo_fx']?>" size="5" title="0则不限"/>
                        
                          个&nbsp;&nbsp; <span class="gray2">每个包裹最多可以分多少个包发货</span></td>
                      </tr>
                      <tr class="odd gradeX">
                        <td width="160" align="right">合箱下单发货数量限制</td>
                        <td>
                            <input name="baoguo_fh" type="text" value="<?=spr($rs['baoguo_fh'])?>" size="5" title="0则不限"/>
                        
                          个&nbsp;&nbsp; <span class="gray2">一次最多可以多少个包裹合箱发货</span></td>
                      </tr>
                      <tr class="odd gradeX">
                        <td width="160" align="right">合箱下单发货重量限制</td>
                        <td><input name="baoguo_fh2" type="text" value="<?=spr($rs['baoguo_fh2'])?>" size="5" title="0则不限"/>
                          <?=$XAwt?>&nbsp;&nbsp; <span class="gray2">包裹发货最大重量</span></td>
                      </tr>
					  
                      
                    </tbody>
                  </table>
                </div>
              </div>
              <!---->
              
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>运单</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
                    <tbody>
                    
                    
                    <tr class="odd gradeX">
                        <td width="160" rowspan="2" align="right">服务手续费</td>
                        <td>
<strong>按包裹数量收费：</strong>
<input name="Price_fh_feesl" type="text"  value="<?=$rs['Price_fh_feesl']?>" size="5">个以上才收费，
在<input name="Price_fh_hxsl" type="text"  value="<?=$rs['Price_fh_hxsl']?>" size="5">个内

<input name="Price_fh_hx_fee1" type="text"  value="<?=spr($rs['Price_fh_hx_fee1'])?>" size="5">
<select name="Price_fh_hx_fee1_way" style="height:25px" class=" tooltips" data-container="body" data-placement="top" data-original-title="收费方式">
  <option value="0" <?=!$rs['Price_fh_hx_fee1_way']?'selected':''?>><?=$XAmc?>/个包裹</option>
  <option value="1" <?=$rs['Price_fh_hx_fee1_way']==1?'selected':''?>><?=$XAmc?></option>
</select>
，超过为
<input name="Price_fh_hx_fee2" type="text"  value="<?=spr($rs['Price_fh_hx_fee2'])?>" size="5">
<select name="Price_fh_hx_fee2_way" style="height:25px" class=" tooltips" data-container="body" data-placement="top" data-original-title="收费方式">
  <option value="0" <?=!$rs['Price_fh_hx_fee2_way']?'selected':''?>><?=$XAmc?>/个包裹</option>
  <option value="1" <?=$rs['Price_fh_hx_fee2_way']==1?'selected':''?>><?=$XAmc?></option>
</select>
                      
                           
<span class="help-block">
&raquo; 在本站商城购买的不收费；包裹发货时才收费；
<br>
&raquo;  包裹下单(会员对包裹下单发货的运单)才收费；
<br>
&raquo;  从包裹下单时按所选的包裹数量计算；
<br>
&raquo;  如果渠道里的价格表里已设有服务费，此设置对该渠道无效；(不按此设置收取)
</span>
                          </td>
                      </tr>
                      
                      
                      
                    <tr class="odd gradeX">
                        <td>
                        <strong>按重量收费：</strong> 
                           <select name="Price_fh_wg_type">
                           <option value="1" <?=$rs['Price_fh_wg_type']==1?'selected':''?>>包裹下单才收费</option>
                           <option value="2" <?=$rs['Price_fh_wg_type']==2?'selected':''?>>非包裹下单才收费</option>
                           <option value="3" <?=$rs['Price_fh_wg_type']==3?'selected':''?>>都收费</option>
                           </select>
                           
<span class="xa_border"></span>


<div class="radio-list">
<label>
    <input type="radio" name="Price_fh_wg_formula" value="0" <?=!$rs['Price_fh_wg_formula']?'checked':''?>>
    不收费                   
</label>

<label>
    <input type="radio" name="Price_fh_wg_formula" value="1" <?=$rs['Price_fh_wg_formula']==1?'checked':''?>>
    固定方式：                        
    <input name="Price_fh_wg_fee" type="text" value="<?=spr($rs['Price_fh_wg_fee'])?>" size="5"/>
    <?=$XAmc?>/<input name="Price_fh_wg" type="text" value="<?=spr($rs['Price_fh_wg'])?>" size="5"/><?=$XAwt?>
</label>


<label>
    <input type="radio" name="Price_fh_wg_formula" value="2" <?=$rs['Price_fh_wg_formula']==2?'checked':''?>>
    阶梯方式：                        
    <textarea  rows="5" name="Price_fh_wg_fee2"><?=cadd($rs['Price_fh_wg_fee2'])?></textarea>

<span class="gray_prompt2" style="padding-left:100px;"><br>
一行一个，顺序必须从小到大，否则计算错误 (单位:重量<?=$XAwt?>、收费<?=$XAmc?>)
</span>
<span class="gray_prompt2" style="padding-left:100px;"><br>
格式：重量=收费，如10=1 表示当重量小于或等于10<?=$XAwt?>时收费1<?=$XAmc?>
</span>

</label>
</div>                      
                         
                         
<span class="xa_border"></span>
<span class="help-block">
说明:<br>
&raquo; <font class="red2">在本站商城购买的也收费；</font>
<br>
&raquo; 按实际称重的重量计算；
<br>
&raquo;  非包裹下单：直接下单、批量导入、后台下单等的运单；包裹下单：会员对包裹下单发货的运单；
<br>
&raquo;  如果渠道里的价格表里已设有服务费，此设置对该渠道无效；(不按此设置收取)
</span>


                        </td>
                      </tr>
                      
                      
                      
                      
                      
                      
                      
					<tr class="odd gradeX">
					  <td align="right">购买保险</td>
					  <td><div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                            <input type="checkbox" class="toggle" name="off_insurance" value="1"  <?php if($rs['off_insurance']||$lx=='add'){echo 'checked';}?> />
                          </div></td>
					  </tr>
                      
                      
                      <?php $name=yundan_service('op_bgfee1','name');	if($name){?>
                      <tr class="odd gradeX">
                        <td width="150" align="right"><?=$name?></td>
                        <td>
						  <?php config_yundan_serviceVal('op_bgfee1_val_fee');?>
                        </td>
                      </tr>
                      <?php }?>
                      
                      <?php $name=yundan_service('op_bgfee2','name');	if($name){?>
                      <tr class="odd gradeX">
                        <td width="150" align="right"><?=$name?></td>
                        <td>
						  <?php config_yundan_serviceVal('op_bgfee2_val_fee');?>
                        </td>
                      </tr>
                      <?php }?>
                      
                       
                     
                      <?php $name=yundan_service('op_wpfee1','name');	if($name){?>
                      <tr class="odd gradeX">
                        <td width="150" align="right"><?=$name?></td>
                        <td>
						  <?php config_yundan_serviceVal('op_wpfee1_val_fee');?>
                        </td>
                      </tr>
                      <?php }?>
                      
                      <?php $name=yundan_service('op_wpfee2','name');	if($name){?>
                      <tr class="odd gradeX">
                        <td width="150" align="right"><?=$name?></td>
                        <td>
						  <?php config_yundan_serviceVal('op_wpfee2_val_fee');?>
                        </td>
                      </tr>
                      <?php }?>
                      
                      
                      <?php $name=yundan_service('op_ydfee1','name');	if($name){?>
                      <tr class="odd gradeX">
                        <td width="150" align="right"><?=$name?></td>
                        <td>
						  <?php config_yundan_serviceVal('op_ydfee1_val_fee');?>
                        </td>
                      </tr>
                      <?php }?>
                      
                      <?php $name=yundan_service('op_ydfee2','name');	if($name){?>
                      <tr class="odd gradeX">
                        <td width="150" align="right"><?=$name?></td>
                        <td>
						  <?php config_yundan_serviceVal('op_ydfee2_val_fee');?>
                        </td>
                      </tr>
                      <?php }?>
                
<?php 
//此页配置调用
function config_yundan_serviceVal($field)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $rs;
	for ($i_val=1; $i_val<=10; $i_val++)
	{
		if($field=='op_bgfee1_val_fee'){$name=yundan_service('op_bgfee1',$i_val);		$unit="{$XAmc}/包裹；";}
		elseif($field=='op_bgfee2_val_fee'){$name=yundan_service('op_bgfee2',$i_val);	$unit="{$XAmc}/包裹；";}
		elseif($field=='op_wpfee1_val_fee'){$name=yundan_service('op_wpfee1',$i_val);	$unit="{$XAmc}/物品；";}
		elseif($field=='op_wpfee2_val_fee'){$name=yundan_service('op_wpfee2',$i_val);	$unit="{$XAmc}/物品；";}
		elseif($field=='op_ydfee1_val_fee'){$name=yundan_service('op_ydfee1',$i_val);	$unit="{$XAmc}/运单；";}
		elseif($field=='op_ydfee2_val_fee'){$name=yundan_service('op_ydfee2',$i_val);	$unit="{$XAmc}/运单；";}
		
		if(!$name){continue;}
		?>
			<?=$name?><input name="<?=$field.$i_val?>" type="text"  value="<?=spr($rs[$field.$i_val],2,0)?>" size="5"><?=$unit?>
		<?php 
	}
}
?>
	  
                    </tbody>
                  </table>
                </div>
              </div>
              
              <!----> 
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>仓库与渠道
				  
                   
                    </strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
<script>
    var handleMultiSelect = function () {
        $('#my_multi_select11').multiSelect();
        $('#my_multi_select21').multiSelect({
            selectableOptgroup: true
        });

        $('#my_multi_select31').multiSelect({
            selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
            selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
            afterInit: function (ms) {
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function (e) {
                        if (e.which === 40) {
                            that.$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function (e) {
                        if (e.which == 40) {
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function () {
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function () {
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    }
</script>				  
				  
				  
<?php 
$query_wh="select * from warehouse where checked='1' order by myorder desc,whid desc";//更改时也要同步更改save.php
$sql_wh=$xingao->query($query_wh);
while($wh=$sql_wh->fetch_array())
{
	$wh_name='warehouse_'.$wh['whid'];
?> 
	<script>
	//显示仓库渠道
	function hide_<?=$wh_name?>()
	{
	  document.getElementById("<?=$wh_name?>").style.display = (document.getElementsByName("<?=$wh_name?>_checked")[0].checked == true) ? "block" : "none";
	}
	</script>
	<div class="form-group">
		<div class="col-md-12" style="background-color:#ccc;">
			<input name="<?=$wh_name?>_checked" type="checkbox" onClick="hide_<?=$wh_name?>();" value="1" <?=$member_warehouse[$groupid][$wh['whid']]['checked']?'checked':''?>/>
			<strong><?=cadd($wh['name'.$LT])?></strong>
		</div>
	</div>
				  	
	
	<div class="form-group">
		<div class="col-md-12">
        
        
        
        
        
        
        
        
        
        
	<!--可随便增加修改,统一保存到一个字段里,不用在数据库做修改-->
	<div id="<?=$wh_name?>" style="display:<?=$member_warehouse[$groupid][$wh['whid']]['checked']?'block':'none'?>;">
    
<table class="table table-striped table-bordered" >
  <tbody>
  
<?php 
for($area=1;$area<=$wh['area'];$area++){
	$areaAllNumber++;
	$wh_name_area=$wh_name.'_'.$area;
?>    
    <tr class="odd gradeX">
        <td align="center" valign="top" width="400">
        
<!--国家-开始-->
<!--
必须有id="my_multi_select数字"
my_multi_select1: name="可不加[]" 
my_multi_select2: name="必须加[]" 
my_multi_select3: 带有搜索功能
-->
<strong>区域</strong>
<select multiple="multiple" class="multi-select" id="my_multi_select3_<?=$areaAllNumber?>" name="<?=$wh_name_area?>_country[]">
<?php
$country=$member_warehouse[$groupid][$wh['whid']][$area]['country'];
if(!$ON_country){$country=$openCountry;}
Country($country,2)
?>
</select>
<span class="help-block">在同一个仓库，同一个国家只能在一个区域<br>
（有相同时系统只能按其他一个区域来计费）</span>
<!--国家-结束-->

        </td>

        <td align="center" valign="top">
        
        
        
    	    <!--渠道-开始-->
            <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
            <tbody>

            <?php 
            $rs_channel=$wh['channel'.$LGDefault];
			if(!is_array($rs_channel)&&$rs_channel){$rs_channel=explode(':::',$rs_channel);}
            for ($i=1; $i<=20; $i++) 
            {
                $channel='channel_'.$i;
                if($rs_channel[$i])
                {
                ?>
                <script>
				//显示渠道公式
				function hide_<?=$wh_name_area?>_<?=$channel?>()
				{
				  document.getElementById("<?=$wh_name_area?>_<?=$channel?>").style.display = (document.getElementsByName("<?=$wh_name_area?>_<?=$channel?>")[0].checked == true) ? "block" : "none";
				}
			
                //显示默认公式
                function <?=$wh_name_area?>_<?=$channel?>_fee_default(v)
                {
                    document.getElementById("<?=$wh_name_area?>_<?=$channel?>_show_fee_default").style.display = (v=='') ? "block" : "none";
                    document.getElementById("<?=$wh_name_area?>_<?=$channel?>_show_fee_other").style.display = (v=='other') ? "block" : "none";
                }
				
               </script>
                  <tr>
                    <td align="center" width="50">
                    
                    <input name="<?=$wh_name_area?>_<?=$channel?>" type="checkbox" value="1" <?=$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_checked']?'checked':''?>  onClick="hide_<?=$wh_name_area?>_<?=$channel?>();"/>
                    
                    </td>
                    <td align="left" width="100"><strong><?=cadd($rs_channel[$i])?></strong></td>
                    <td>
        
                          
                  <div id="<?=$wh_name_area?>_<?=$channel?>" style="display:<?=$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_checked']?'block':'none'?>;">
 
                    价格公式：
                    <select data-placeholder="请选择渠道价格"  name="<?=$wh_name_area?>_<?=$channel?>_formula" onChange="<?=$wh_name_area?>_<?=$channel?>_fee_default(this.value)">
                    <?php fee_gongshi($member_warehouse[$groupid][$wh['whid']][$area][$channel.'_formula'],1)?>
                    </select>
                   
                    
                    <span class="xa_border"></span>
                    
                    <div id="<?=$wh_name_area?>_<?=$channel?>_show_fee_default" style="display:<?=!$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_formula']?'block':'none'?>;">
                        <a class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=$moren_gongshi_name?>">默认公式</a>：
                        首重
                        <input name="<?=$wh_name_area?>_<?=$channel?>_sz_weight" type="text" value="<?=$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_sz_weight']?>" size="5">
                        <?=$XAwt?>
                        收费
                        <input name="<?=$wh_name_area?>_<?=$channel?>_sz_price" type="text" value="<?=$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_sz_price']?>" size="5">
                        <?=$XAmc?>；
                        
                        续重 每
                        <input name="<?=$wh_name_area?>_<?=$channel?>_xz_weight" type="text" value="<?=$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_xz_weight']?>" size="5">
                        <?=$XAwt?>
                        收费 
                        <input name="<?=$wh_name_area?>_<?=$channel?>_xz_price" type="text" value="<?=$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_xz_price']?>" size="5">
                        <?=$XAmc?>；
                        
                        重量取整：
                        <select name="<?=$wh_name_area?>_<?=$channel?>_weight_int">
                        <?php weight_int(3,$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_weight_int'])?>
                        </select>
                        
                        <span class="xa_border"></span>
                    </div>
                    
                    
                    <div id="<?=$wh_name_area?>_<?=$channel?>_show_fee_other" style="display:<?=$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_formula']=='other'?'block':'none'?>;">
                    
                        价格表币种：
                        <select name="<?=$wh_name_area?>_<?=$channel?>_fee_other_currency" style="margin-bottom:10px;">
                        <?php openCurrency($member_warehouse[$groupid][$wh['whid']][$area][$channel.'_fee_other_currency'],2)?>
                        </select>
                        
                        价格表重量：
                        <select  name="<?=$wh_name_area?>_<?=$channel?>_fee_other_weight" style="margin-bottom:10px;">
                       		<option value="0" <?=!spr($member_warehouse[$groupid][$wh['whid']][$area][$channel.'_fee_other_weight'])?'selected':''?>><?=$XAwt?></option>
                            
                       		<option value="1" <?=spr($member_warehouse[$groupid][$wh['whid']][$area][$channel.'_fee_other_weight'])?'selected':''?>>KG</option>
                        </select>
       					 <br>

                        价格表：
                        <textarea  rows="10" name="<?=$wh_name_area?>_<?=$channel?>_fee_other"><?=cadd($member_warehouse[$groupid][$wh['whid']][$area][$channel.'_fee_other'])?></textarea>
                    
                        <span class="gray_prompt2">
                        <br>
                        一行一个，顺序必须从小到大，否则计算错误(单位:重量<?=$XAwt?>)<br>
                        格式：重量=运费=手续费，如100=10=5 表示当重量小于或等于100<?=$XAwt?>时,运费为10,手续费为5<br>
                       如果不收手续费则写0，如果按上面的【服务手续费】处收取则直接写如：100=10
                        </span>
                        
                        <span class="xa_border"></span>
                    </div>
                    
                    
                    
                    
                  
                    
                    
                    <div>
                        体积重计算：
                        超过
                        <input name="<?=$wh_name_area?>_<?=$channel?>_cc_exceed" type="text" value="<?=$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_cc_exceed']?>" size="5" class=" tooltips" data-container="body" data-placement="top" data-original-title="0或空则不收">立方<?=$XAsz?>时收体积费；
                        公式
                        <select data-placeholder="请选择渠道价格"  name="<?=$wh_name_area?>_<?=$channel?>_cc_formula" style="margin-right:20px;">
                        <?php cc_formula($member_warehouse[$groupid][$wh['whid']][$area][$channel.'_cc_formula'],1)?>
                        </select>
                        
                    </div>
        
        
                    <span class="xa_border"></span>
                    
                    运费折扣 
                    <input name="<?=$wh_name_area?>_<?=$channel?>_discount" type="text" value="<?=$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_discount']?>" size="5" class="tooltips" data-container="body" data-placement="top" data-original-title="比如打98折,则写9.8" onkeyup="value=value.replace(/[^\d.]/g,'')">
                    折；
                    
                    
                    税额
                    <input name="<?=$wh_name_area?>_<?=$channel?>_tax" type="text" value="<?=$member_warehouse[$groupid][$wh['whid']][$area][$channel.'_tax']?>" size="5" class="tooltips" data-container="body" data-placement="top" data-original-title="固定收取，可以多次收取，次数在运单计费页填写">
                    <?=$XAmc?>；
                    
                    
        				</div>
                      </td>
                  </tr>
                <?php 
                }
            }//for ($i=1; $i<=20; $i++) {
            ?>
                </tbody>
            </table>
    	    <!--渠道-结束-->
            
            
            
        </td>
    </tr>
<?php }?>    
  </tbody>
</table>
    
    
    
    
    
    
    
	</div>
			
		</div>
		</div>
<?php }//while($wh=$sql_wh->fetch_array())?>	
<div class="xats"><br>
&raquo; 勾选仓库或渠道该会员组才能使用该仓库或该渠道<br>
&raquo; 除了默认公式价格外,其他价格在 public/function_price.php 文件中设置<br>
&raquo; 国家区域:左边表示未选择,右边表示已选择<br>
</div>



					
				  

                </div>
              </div>
              <!---->
              
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
