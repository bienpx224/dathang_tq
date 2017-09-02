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
$pervar='manage_sy';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="仓库编辑";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');



//获取,处理
$lx=par($_GET['lx']);
$whid=par($_GET['whid']);
if(!$lx){$lx='add';}

if($lx=='edit'){if(!$whid){exit ("<script>alert('whid{$LG['pptError']}');goBack();</script>");}}
if($whid){$rs=FeData('warehouse','*',"whid='{$whid}'");}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token('warehouse');

?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > 
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
         </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
  <input name="lx" type="hidden" value="<?=add($lx)?>">
  <input name="whid" type="hidden" value="<?=$rs['whid']?>">
  <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
    
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">主要设置</a></li>
<?php 
//语言字段处理--
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		?>
        <li><a href="#tab_<?=$language?>" data-toggle="tab"><?=languageType($language)?></a></li>
		<?php 
	}
}
?>
      </ul>
    
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
            <div class="portlet">
              
              <div class="portlet-body form" style="display: block;"> 
                <!--表单内容-->
              

                <div class="form-group">
                  <label class="control-label col-md-2">所在国家</label>
                  <div class="col-md-10 has-error">
<select class="form-control input-medium select2me" name="country" data-placeholder="请选择" required>
<?=Country($rs['country'],1)?>
</select>
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
                  <label class="control-label col-md-2">排序</label>
                  <div class="col-md-10">
                    <input type="text" class="form-control input-xsmall"  name="myorder" value="<?=cadd($rs['myorder'])?>"><span class="help-block">越大越排前</span>
                  </div>
                </div>
                
            	<div class="form-group">
                  <label class="control-label col-md-2">仓库标识</label>
                  <div class="col-md-10">
                    <input type="text" class="form-control input-medium"  name="sign" value="<?=cadd($rs['sign'])?>"><span class="help-block">少数用于打印、导出（如：Invoice打印面单）</span>
                  </div>
                </div>
                
            	<div class="form-group">
                  <label class="control-label col-md-2">出库重量复验相差值</label>
                  <div class="col-md-10">
                    <input type="text" class="form-control input-small"  name="weightRepeat" value="<?=spr($rs['weightRepeat'])?>"><?=$XAwt?>
                    <span class="help-block">
                    &raquo; 用于【扫描预出库】时复称并与原重量进行比较，当超过该值时则提示或禁止出库<br>
                    &raquo; 作用是确保包裹完整,防止工作人员计费称重后进行的其他操作引起的物品丢失问题
                    </span>
                  </div>
                </div>
                
            	<div class="form-group">
                  <label class="control-label col-md-2">区域数量</label>
                  <div class="col-md-10">
                    <input type="text" class="form-control input-xsmall"  name="area" value="<?=cadd($rs['area'])?>"><span class="help-block">会员组在设置价格时显示的区域数量,用于设置各国家不同的价格</span>
                  </div>
                </div>
                
				           
				<div class="form-group">
				<label class="control-label col-md-2">运输渠道</label>
				<div class="col-md-10">
                <table class="table table-striped table-bordered table-hover">
<?php 
//渠道多值字段处理:转数组
$arr=ToArr('weight_limit,customs_types_limit,customs_weight_limit,customs_DutyFree,signday,shenfenzheng,JPChannel,customs,baoxian_1,baoxian_2,baoxian_3,baoxian_4,baoxian_5,insuranceFormula,insuranceFormulaType,ON_op_bgfee1,ON_op_bgfee2,ON_op_wpfee1,ON_op_wpfee2,ON_op_ydfee1,ON_op_ydfee2,ON_op_free,ON_op_freearr');
if($arr)
{
	foreach($arr as $arrkey=>$value)
	{
		if(!$rs[$value]){continue;}
		$joint=$value;
		$$joint=explode(':::',$rs[$value]);
	}
}



//输出渠道-开始------------------------------------------------------------------------
for ($i=0; $i<=20; $i++) {
?>
                  <tr class="odd gradeX" style=" <?=$i==0?'display:none;':''?>"><!--0渠道不使用-->
                    <td align="right"  width="100"><strong>渠道ID <?=$i?></strong>：</td>
                    <td align="left">
    
<style>
 .xa_sep{
	color: #DDDDDD;
	margin-left: 10px;
	margin-right: 10px;
}
</style>

<script>
//显示清关设置表单
function customs_<?=$i?>(v)
{
	document.getElementById("gd_mosuda_<?=$i?>").style.display = (v=='gd_mosuda') ? "block" : "none";
	document.getElementById("customs_<?=$i?>").style.display = (v) ? "block" : "none";
}
</script>
                    
<table width="100%">
  <tbody>
  
    <tr>
      <td>基本属性：</td>
      <td>
     	
        <select name="customs[]" class="popovers" data-trigger="hover" data-placement="top"  data-content="清关资料" style="height:27px;" onChange="customs_<?=$i?>(this.value)">
          <?=CustomsType($customs[$i],1)?>
          </select>
        <span class="xa_sep"> | </span>

          
        <input name="weight_limit[]" type="text" value="<?=spr($weight_limit[$i],0,0)?>" size="5" maxlength="8" class="popovers" data-trigger="hover" data-placement="top"  data-content="包裹限重(填0或空则不限)：按预估重量计算，超过时会提示，可强行下单" onkeyup="value=value.replace(/[^\d.]/g,'')" /><?=$XAwt?>
       <span class="xa_sep"> | </span>

        <select name="shenfenzheng[]" class="popovers" data-trigger="hover" data-placement="top"  data-content="是否要上传证件 (要上传时还需要在系统配置》运单里开通上传功能)" style="height:27px; margin-top:5px;">
          <option value="1" <?=spr($shenfenzheng[$i])?'selected':''?>>要上传证件</option>
          <option value="0" <?=!spr($shenfenzheng[$i])?'selected':''?>>不用上传证件</option>
          </select> 
       <span class="xa_sep"> | </span>

		<input name="signday[]" type="text" value="<?=spr($signday[$i],0,0)?>" size="5" maxlength="3" class="popovers" data-trigger="hover" data-placement="top"  data-content="出库多少天后会员可自行设置运单为已签收 (0或空则不可自行设置)<br>也可以不用设置,一般会调用快递公司API自动更新" onkeyup="value=value.replace(/[^\d.]/g,'')"/>天
         <span class="xa_sep"> | </span>

       <select name="JPChannel[]" class="popovers" data-trigger="hover" data-placement="top"  data-content="如果是日本渠道请正确选择,导出和打印时将按该选择自动配置" style="height:27px; margin-top:5px;">
          <option value="" <?=!$JPChannel[$i]?'selected':''?>>非日本渠道</option>
          <option value="1" <?=$JPChannel[$i]==1?'selected':''?>>日本渠道-EMS</option>
          <option value="2" <?=$JPChannel[$i]==2?'selected':''?>>日本渠道-空运</option>
          <option value="3" <?=$JPChannel[$i]==3?'selected':''?>>日本渠道-SAL</option>
          <option value="4" <?=$JPChannel[$i]==4?'selected':''?>>日本渠道-船运</option>
        </select>      
       </td>
      </tr>
    

 
    <tr>
    <td></td>
      <td>
<div id="gd_mosuda_<?=$i?>" style="display:<?=$customs[$i]=='gd_mosuda'?'block':'none'?>;">
 	商品类型限制:<br>
    <select name="gd_mosuda_types_limit<?=$i?>[]" size="10" multiple="multiple">
    <?php 
    	$query_gd="select types,gdid from gd_mosuda where record in (0,2)  group by types  order by types asc";
		$sql_gd=$xingao->query($query_gd);
		while($gd=$sql_gd->fetch_array())
		{
			echo '<option value="'.cadd($gd['types']).'"  '.(have(ToArr($customs_types_limit[$i],','),$gd['types'])?'selected':'').'>'.cadd($gd['types']).'</option>';
		}
	?>
    </select>
    <span class="help-block">
    &raquo; 选择/取消:按CTRL+鼠标点击 (可多选)，不选则不限<br>
    &raquo; 请先完善商品资料库，才会显示完整的分类<br>
    &raquo; 会员申请的商品备案和未设置分类的商品资料，不受以上限制
    </span>
    
    <span class="xa_border"></span>
    
    商品净重限制:
    <input name="gd_mosuda_weight_limit<?=$i?>[]" type="text" value="<?=spr($customs_weight_limit[$i],0,0)?>" size="5" maxlength="8" class="popovers" data-trigger="hover" data-placement="top"  data-content="商品净重限制(填0或空则不限)：按商品资料内的重量*商品数量计算，超过时不可下单"/>KG

</div>

<!--通用清关公司-->
<div id="customs_<?=$i?>" style="display:<?=$customs[$i]?'block':'none'?>;">
    <span class="xa_border"></span>
    
    物品价值低于<input name="customs_DutyFree<?=$i?>[]" type="text" value="<?=spr($customs_DutyFree[$i])?>" size="5" class="popovers" data-trigger="hover" data-placement="top"  data-content="(商品数量*备案价格*税率)低于 CNY时免税<br>填0全部收税,填-1全部免税"/>CNY时免税
</div>

      </td>
      </tr>
                   
                        

   <tr>
    <td>保险收费：</td>
      <td>
     <select class="popovers" data-trigger="hover" data-placement="top"  data-content="如果需要自定义税收公式请下拉选择自定义" style="height:27px; margin-top:5px;" onChange="insuranceFormula(<?=$i?>,this.value);" name="insuranceFormulaType[]">
      <option value="0" <?=!spr($insuranceFormulaType[$i])?'selected':''?>>保险率固定公式</option>
      <option value="1" <?=spr($insuranceFormulaType[$i])?'selected':''?>>保险率自定公式</option>
    </select></br>
    
    	<div id="insuranceFormulaType_0<?=$i?>" style="display:<?=!spr($insuranceFormulaType[$i])?'':'none'?>">
         物品价值在
          <input name="baoxian_1[]" type="text" value="<?=spr($baoxian_1[$i])?>" size="5" onkeyup="value=value.replace(/[^\d.]/g,'')"/><?=$XAsc?>
          之内保险率为
          <input name="baoxian_2[]" type="text" value="<?=spr($baoxian_2[$i])?>" size="3" onkeyup="value=value.replace(/[^\d.]/g,'')"/>
          %，超过则为
          <input name="baoxian_3[]" type="text" value="<?=spr($baoxian_3[$i])?>" size="3" onkeyup="value=value.replace(/[^\d.]/g,'')"/>
          %； </br>
         </div>
         
         <div id="insuranceFormulaType_1<?=$i?>" style="display:<?=spr($insuranceFormulaType[$i])?'':'none'?>">
          <!--运单保险：基本险2017.6.10 -->
          <textarea class="form-control input-medium" name="insuranceFormula[]" rows="8" ><?=$insuranceFormula[$i]?></textarea>
<span class="help-block">               
    一行一个，顺序必须从小到大，否则计算错误(单位:<?=$XAsc?>)<br>
    不同的物品价值可以设置不同的公式(低于X有固定费用;高于X1收X%费用; 高于X2收X%费用....不加%则按固定收费)<br>
    格式：物品价值金额=收取的税费<br> 
    如：<br>
    1000=10<br>
    2000=5%<br>
    3000=10%<br>
    <br>以上表示：1000<?=$XAsc?>以下收10<?=$XAsc?>，2000<?=$XAsc?>以下收5%，3000<?=$XAsc?>以下收10%
</span>
		</div>

      </td>
      </tr>
      
    <tr>
    <td>保价限制：</td>
      <td>
<input  class="tooltips" data-container="body" data-placement="top" data-original-title="保价区间都填写0时,表示不支持购买保险" name="baoxian_4[]" type="text" value="<?=spr($baoxian_4[$i])?>" size="5" onkeyup="value=value.replace(/[^\d.]/g,'')"/><?=$XAsc?>
～
<input  class="tooltips" data-container="body" data-placement="top" data-original-title="保价区间都填写0时,表示不支持购买保险" name="baoxian_5[]" type="text" value="<?=spr($baoxian_5[$i])?>" size="5" onkeyup="value=value.replace(/[^\d.]/g,'')"/><?=$XAsc?>
      </td>
      </tr>
      
    
    <tr>
    <td>增值服务：</td>
      <td>
		<?php  $joint='op_bgfee1_name'.$LT;if($$joint){?>
        <select name="ON_op_bgfee1[]">
          <option value="0" <?=!spr($ON_op_bgfee1[$i])?'selected':''?>>关闭 <?=$$joint?></option>
          <option value="1" <?=spr($ON_op_bgfee1[$i])?'selected':''?>>开通 <?=$$joint?></option>
        </select>
        <?php }?>
      
      <?php  $joint='op_bgfee2_name'.$LT;if($$joint){?>
        <select name="ON_op_bgfee2[]">
          <option value="0" <?=!spr($ON_op_bgfee2[$i])?'selected':''?>>关闭 <?=$$joint?></option>
          <option value="1" <?=spr($ON_op_bgfee2[$i])?'selected':''?>>开通 <?=$$joint?></option>
        </select>
      <?php }?>
      
      <?php  $joint='op_wpfee1_name'.$LT;if($$joint){?>
        <select name="ON_op_wpfee1[]">
          <option value="0" <?=!spr($ON_op_wpfee1[$i])?'selected':''?>>关闭 <?=$$joint?></option>
          <option value="1" <?=spr($ON_op_wpfee1[$i])?'selected':''?>>开通 <?=$$joint?></option>
        </select>
      <?php }?>
      
      <?php  $joint='op_wpfee2_name'.$LT;if($$joint){?>
        <select name="ON_op_wpfee2[]">
          <option value="0" <?=!spr($ON_op_wpfee2[$i])?'selected':''?>>关闭 <?=$$joint?></option>
          <option value="1" <?=spr($ON_op_wpfee2[$i])?'selected':''?>>开通 <?=$$joint?></option>
        </select>
      <?php }?>
      
      <?php  $joint='op_ydfee1_name'.$LT;if($$joint){?>
        <select name="ON_op_ydfee1[]">
          <option value="0" <?=!spr($ON_op_ydfee1[$i])?'selected':''?>>关闭 <?=$$joint?></option>
          <option value="1" <?=spr($ON_op_ydfee1[$i])?'selected':''?>>开通 <?=$$joint?></option>
        </select>
      <?php }?>
      
      <?php  $joint='op_ydfee2_name'.$LT;if($$joint){?>
        <select name="ON_op_ydfee2[]">
          <option value="0" <?=!spr($ON_op_ydfee2[$i])?'selected':''?>>关闭 <?=$$joint?></option>
          <option value="1" <?=spr($ON_op_ydfee2[$i])?'selected':''?>>开通 <?=$$joint?></option>
        </select>
      <?php }?>
      
      <?php  $joint='op_free_name'.$LT;if($$joint){?>
         <select name="ON_op_free[]">
          <option value="0" <?=!spr($ON_op_free[$i])?'selected':''?>>关闭 <?=$$joint?></option>
          <option value="1" <?=spr($ON_op_free[$i])?'selected':''?>>开通 <?=$$joint?></option>
        </select>
       <?php }?>
      
      <?php  $joint='op_freearr_name'.$LT;if($$joint){?>
         <select name="ON_op_freearr[]">
          <option value="0" <?=!spr($ON_op_freearr[$i])?'selected':''?>>关闭<?=$$joint?></option>
          <option value="1" <?=spr($ON_op_freearr[$i])?'selected':''?>>开通 <?=$$joint?></option>
        </select>
      <?php }?>
      </td>
    </tr>
  </tbody>
</table>

                    
                        
                        
                    
					</td>
                  </tr>
 <?php  }
//输出渠道-结束------------------------------------------------------------------------
 
 ?>
                  
                    </tbody>
                </table>
                     <span class="help-block">
                     &raquo; 证件上传，需要在<a href="/xingao/config/form.php"  target="_blank">系统配置》运单</a>里开通上传功能<br>

                    &raquo;  价格请在<a href="/xingao/member_group/list.php" target="_blank">会员组设置</a>
                     </span>
				  </div>
				</div>  
                
              </div>
            </div>
          </div>
          
          </div>
        </div>
        
        
        
        <!---->
        
        
 
<?php 
//语言字段处理--
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	$LG_i=0;
	foreach($LGList as $arrkey=>$language)
	{
		$LG_i+=1;
		?>
        
 		<!---------------------------------<?=$language?>--------------------------------->
         <div class="tab-pane" id="tab_<?=$language?>">
          <div class="form">
            <div class="form-body"> 
            
            <div>
               <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->

                 <div class="form-group">
                    <label class="control-label col-md-2">仓库名称</label>
                    <div class="col-md-10 <?=$LG_i==1?'has-error':''?>">
                      <input type="text" class="form-control" name="name<?=$language?>"  value="<?=cadd($rs['name'.$language])?>" <?=$LG_i==1?'required':''?>>
                    </div>
                  </div>
                  
                
				<div class="form-group">
				<label class="control-label col-md-2">仓库地址</label>
				<div class="col-md-10">
				  <textarea class="form-control" name="address<?=$language?>" rows="8" ><?=cadd($rs['address'.$language])?></textarea>
				  
					<span class="help-block">
					支持HTML代码<br><br>
					</span>	
                    <?=Label('',1)//显示信息标签说明?> 
                    			   
				  </div>
				</div>  


				<div class="form-group">
				<label class="control-label col-md-2">运输渠道</label>
				<div class="col-md-10">
                <table class="table table-striped table-bordered table-hover" >
                 <style>input{margin-top:5px;}</style>
                 <?php 
//转数组
if($rs['channel'.$LT]){$channel=explode(':::',$rs['channel'.$LT]);}
if($rs['weight_limit_ppt'.$LT]){$weight_limit_ppt=explode(':::',$rs['weight_limit_ppt'.$LT]);}
if($rs['content'.$LT]){$content=explode(':::',$rs['content'.$LT]);}
				 for ($i=0; $i<=20; $i++) {
				 ?>
                  <tr class="odd gradeX" style=" <?=$i==0?'display:none;':''?>"><!--0渠道不使用-->
                    <td align="right"  width="100"><strong>渠道ID <?=$i?></strong>：</td>
                    <td align="left">
                    
                    <input name="channel<?=$language?>[]" type="text" value="<?=cadd($channel[$i])?>" size="40" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="渠道名称"/><br>
                    
                    <input name="weight_limit_ppt<?=$language?>[]" type="text"  class="popovers" data-trigger="hover" data-placement="top"  data-content="限重提示内容：提示开头已默认有“预估重量已超过该渠道限重XX<?=$XAwt?>，确定提交吗？”" style="width:100%;" value="<?=cadd($weight_limit_ppt[$i])?>" maxlength="255"/><br>
                    
                    <input name="content<?=$language?>[]" type="text"  class="tooltips" data-container="body" data-placement="top" data-original-title="渠道说明" style="width:100%;" value="<?=cadd($content[$i])?>" maxlength="255"/><br>
					</td>
                  </tr>
                  <?php  }?>
                  
                    </tbody>
                </table>
                     
				  </div>
				</div>  





                  
                  


                  
                  
                  <!--内容结束-->
                  
                </div>
              </div>
             
        </div>
      </div>
      </div>
		<?php 
	}
}
?>        
        
                
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
 <script type="text/javascript">
 function insuranceFormula(i,insuranceFormulaType)
 {
	if(insuranceFormulaType==0){
		document.getElementById("insuranceFormulaType_0"+i).style.display ='block';
		document.getElementById("insuranceFormulaType_1"+i).style.display ='none';
	}else{
		document.getElementById("insuranceFormulaType_0"+i).style.display ='none';
		document.getElementById("insuranceFormulaType_1"+i).style.display ='block';
	}
 }
</script>
