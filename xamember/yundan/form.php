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
$groupid=$Mgroupid;require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/get_price.php');
$headtitle=$LG['name.nav_67'];//运单
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$ydid=par($_GET['ydid']);
$addSource=spr($_GET['addSource']);
$bg_zxyd=par($_GET['bg_zxyd']);
$fx=spr($_GET['fx']);
$fx_total=spr($_GET['fx_total']);
$fx_number=spr($_GET['fx_number']);
$bgid=par(ToStr($_REQUEST['bgid']));
$goid=par(ToStr($_REQUEST['goid']));
$warehouse=spr($_GET['warehouse']);
$country=spr($_GET['country']);
$channel=spr($_GET['channel']);
$callFrom='member';//manage member

if($_GET['tag']){$_SESSION[$_GET['tag']]='';}
if($fx_total){$fx_count=$fx_total-$fx_number+1;}

if(!$lx){$lx='add';}


//修改==============
if($lx=='edit')
{
	if(!$ydid){exit("<script>alert('ydid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('yundan','*',"ydid='{$ydid}' {$Mmy}");
	if(spr($rs['status'])>1){exit ("<script>alert('{$LG['yundan.form_1']}');goBack();</script>");}	
	
	$bgid=cadd($rs['bgid']);
	$goid=cadd($rs['goid']);
	$bg_number=arrcount($rs['bgid']);
	$go_number=arrcount($rs['goid']);
	$addSource=spr($rs['addSource']);
	$weightEstimate=spr($rs['weightEstimate']);
	
	//必须优先用所传参数,否则无法修改这3个
	if(!$warehouse){$warehouse=$rs['warehouse'];}
	if(!$country){$country=$rs['country'];}
	if(!$channel){$channel=$rs['channel'];}
	
	//备案渠道:新渠道商品分类限制是否支持旧渠道的商品分类限制:检查新渠道是否可以保留旧渠道的物品
	$customs_types_limit_old=channelPar($rs['warehouse'],$rs['channel'],'customs_types_limit');	
	$customs_types_limit_new=channelPar($_GET['warehouse'],$_GET['channel'],'customs_types_limit');
	if($customs_types_limit_new&&$_GET['warehouse']&&$_GET['channel'])
	{
		if(!have($customs_types_limit_new,$customs_types_limit_old)){$wupinNotKeep=1;}//$wupinNotKeep=1 清空物品
	}

	//包裹/代购下单时
	$customs_old=channelPar($rs['warehouse'],$rs['channel'],'customs');	
	$customs_new=channelPar($_GET['warehouse'],$_GET['channel'],'customs');
	if(($addSource==1||$addSource==7)&&$customs_new!=$customs_old&&$_GET['warehouse']&&$_GET['channel'])
	{
		exit ("<script>alert('{$LG['yundan.31']}');goBack();</script>");//只能选择同类型渠道,如要选择该渠道,请删除该运单并重新下单
	}
	

}




//下单==============
elseif($lx=='add')
{
	//直接下单----------------------------------------------------------------------------------
	if(!$addSource)
	{
		$addSource=2;
		if(!$member_per[$Mgroupid]['off_zjxd']){exit ("<script>alert('{$LG['yundan.form_2']}');goBack();</script>");}	
		
	//包裹下单----------------------------------------------------------------------------------
	}elseif($addSource==1){
		
		if(!$off_baoguo||!$member_per[$Mgroupid]['ON_Mbaoguo']){exit ("<script>alert('{$LG['baoguo.add_form_2']}');goBack();</script>");}
		if(!$bgid){exit("<script>alert('{$LG['yundan.form_3']}');goBack();</script>");}

		$r			=	baoguo_deliveryCHK($bgid);
		$warehouse	=	$r['warehouse'];
		$bgid		=	$r['bgid'];
		$weightEstimate=	$r['weightEstimate'];		//if($ON_yundan_prepay&&$weightEstimate){$weightEstimate+=0.5;}//多加点重量,减少后期补款流程
		$addid		=	$r['addid'];
		$bg_number	=	arrcount($r['bgid']);
		
	//代购下单----------------------------------------------------------------------------------
	}elseif($addSource==7){
		
		if(!$ON_daigou||!$member_per[$Mgroupid]['daigou']){ exit("<script>alert('{$LG['daigou.45']}');goBack('c');</script>"); }
		if(!$goid){exit("<script>alert('{$LG['daigou.187']}');goBack('c');</script>");}
		
		//获取代购单资料
		$gd=FeData('daigou_goods','dgid,addid',"goid in ({$goid})");
		$dg=FeData('daigou','status,warehouse',"dgid='{$gd['dgid']}'");
		
		$warehouse	=	$dg['warehouse'];
		$goid		=	$goid;
		//$weightEstimate=	$r['weightEstimate'];//在wupin_from_general( 有全局返回
		$addid		=	$gd['addid'];
		$go_number	=	arrcount($goid);
	}
	
}


//变更渠道时,显示不同物品表单
$status="lx={$lx}&ydid={$ydid}&addSource={$addSource}&bg_zxyd={$bg_zxyd}&fx={$fx}&fx_total={$fx_total}&fx_number={$fx_number}&bgid={$bgid}&goid={$goid}&warehouse={$warehouse}&country={$country}&channel={$channel}";

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey=$token->grante_token("yundan");
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
            <h3 class="page-title"> 
                <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> 
                <a href="list.php" class="gray"><?=$LG['backList']?></a> > 
                <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><font id="title_name" class="red"></font></a>
                <small><?=cadd($rs['ydh'])?></small>
            </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
	<form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
	<input name="lx" type="hidden" value="<?=add($lx)?>">
	<input name="ydid" type="hidden" value="<?=$rs['ydid']?>">
	<input name="bgid" type="hidden" value="<?=$bgid?>">
	<input name="goid" type="hidden" value="<?=$goid?>">
	<input name="bg_zxyd" type="hidden" value="<?=$bg_zxyd?>" />
	<input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
	
	 <!-- 用于获取计算费用-->
	<input name="bg_number" type="hidden" value="<?=$bg_number?>" />
	<input name="go_number" type="hidden" value="<?=$go_number?>" />
	
 	<input name="addSource" type="hidden" value="<?=$addSource?>" />
  <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
			
            <!--版块-->
			 <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['yundan.form_9'];//基本信息?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a></div><!--默认关闭:class="expand"-->
                </div>
                <div class="portlet-body form" style="display: block;"> <!--默认关闭:display: none;-->
                  <!--表单内容-->
                  
                  <?php if(spr($rs['status'])==1){?>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['yundan.status'];//申请重新审核?></label>
                    <div class="col-md-10">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="status" value="0"  <?php if(spr($rs['status'])==0){echo 'checked';}?> />
                      </div>
                    </div>
                  </div>
                  <?php }?>
   
   
   
   
   
                  
				  
<!--是包裹下单-------------------------------------------------------------------->
<?php if($addSource==1){?>
    <input type="hidden" name="warehouse" value="<?=$warehouse?>">
    
    <?php if($lx=='add'){?>
    <div class="form-group">
      <label class="control-label col-md-2"><?=$LG['yundan.fx'];//是否分包?></label>
      <div class="col-md-10 has-error">
          <div class="radio-list">
          
               <label class="radio-inline red" style="float:left">
               <input type="radio" name="fx"  value="0" onclick="dis('0');divide();" required <?=!$fx_number&&!$fx?'checked':'';?>> <?=$LG['yundan.form_10'];//不分包?>
               <a class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['yundan.form_12'];//将包裹全部或剩余物品打包到该运单发货?>"><font class="gray2">(?)</font></a>
               </label>
               
               <label class="radio-inline red" style="float:left">
               <input name="fx" type="radio" required onclick="dis('1');divide();" value="1" <?=$fx_number||$fx?'checked':'';?>> <?=$LG['yundan.form_13'];//分包?>
               <a class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['yundan.form_15'];//将包裹拆分发货?>"><font class="gray2">(?)</font></a>
              </label>
              
              
          <div id="hide2" style="display:<?=$fx_number>0||$fx?'block':'none';?>; float:left">
              <label class="radio-inline" >
                  <?=$fx_number>0?$LG['yundan.form_54']:$LG['yundan.form_55'];?>:
                  <select name="fx_number" class="input_txt_red" onChange="divide();">
                  <?php 
                      $i_small=2;
                      if($fx_number>0){$i_max=$fx_number;$i_small=1;}
                      elseif($member_per[$Mgroupid]['baoguo_fx']>0){$i_max=$member_per[$Mgroupid]['baoguo_fx'];}
                      else{$i_max=10;}
                      
                      for ($i=$i_max; $i>=$i_small; $i--) {
                  ?>	
                  <option value="<?=$i?>"><?=$i?></option>
                  <?php }?>
                  </select>                                 
              </label>
              
            <label class="radio-inline">
              <font class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['yundan.form_16'];//勾选时只需下一个运单，将自动分成多个分包运单?>">
              <input type="checkbox"  name="fx_same" value="1"><?=$LG['yundan.form_17'];//收件地址相同?>
              </font>
            </label>
            
           <input type="hidden" name="fx_total" value="<?=$fx_total?>">
       </div>	
              
            
            
           
            
          </div>		
      </div>
    </div>
    <?php }?>





<!--代购下单-------------------------------------------------------------------->
<?php }elseif($addSource==7){?>
   <input type="hidden" name="warehouse" value="<?=$warehouse?>">





<!--直接下单-------------------------------------------------------------------->
<?php }else{?>
   <div class="form-group" <?=$warehouse_more?'':'style="display:none"'?>>
      <label class="control-label col-md-2"><?=$LG['yundan.warehouse'];//所在仓库?></label>
      <div class="col-md-10 has-error">
       <select name="warehouse" class="form-control input-medium select2me" required  data-placeholder="<?=$LG['yundan.form_18'];//请选择?>" onChange="refresh_form();"><!--country_show('<?=$Mgroupid?>','<?=spr($country)?>');-->
       <?php warehouse($warehouse,1);?>
       </select>
      </div>
    </div>
<?php }?>




                          
                  <?php if($ON_country){?>
                     <div class="form-group">
                        <label class="control-label col-md-2"><?=$LG['yundan.country'];//寄往国家?></label>
                        <div class="col-md-10 has-error">
                       	   <span id="country"></span>
                        </div>
                      </div>
                  <?php }else{?>
                        <input type="hidden"  name="country" value="<?=$country?$country:$openCountry;?>">
                  <?php }?>
                          
				  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['yundan.form_19'];//运输渠道?></label>
                    <div class="col-md-10 has-error">
					<span id='channel'></span>
                    
				     <a href="/xamember/other/tab.php?classid=<?=$price_classid?>" target="_blank" class="btn btn-default tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['yundan.form_68']//如要了解各渠道的收费,请用【在线报价】工具查询。?>"><?=$LG['yundan.form_20'];//我的价格?></a>
                     
                      <span class="help-block">
                          <font class="red">&raquo; <?=$LG['yundan.form_69']//请先认真选择渠道再填写后面内容，变更渠道时清关要求也会变更，因此后面所填写内容可能会清空。?></font><br>
                          
                          <font id="channel_content"></font><br>

                      </span>
				     
                    </div>
                  </div>
				  
<?php if($addSource==2){?>
<div class="form-group">
	<label class="control-label col-md-2"><?=$LG['yundan.gwkdydh'];//寄库单号?></label>
	<div class="col-md-10">

<input type="text" class="form-control input-medium" name="gwkdydh" value="<?=cadd($rs['gwkdydh'])?>">
<a href="javascript:void(0)" onMouseOver="document.getElementById('hide_gwkdydh').style.display='block'" onMouseOut="document.getElementById('hide_gwkdydh').style.display='none'"> <i class="icon-info-sign"></i> </a>
<div id="hide_gwkdydh" style="display:none;"> 
    <span class="help-block">
        &raquo; <?=$LG['yundan.form_22'];//如果自送到我司仓库，有贴条码单时请填写条码上的号码，否则请记下一会生成的运单号，到仓库时提供该运单号?><br>
        &raquo; <?=$LG['yundan.form_23'];//如果寄送到我司仓库，请填写寄送单号?><br>
        <?php if($member_per[$Mgroupid]['off_qujian']){?>
        &raquo; <?=LGtag($LG['yundan.form_57'],'<tag1>==<a href="/xamember/qujian/form.php" target="_blank">'.$LG['name.nav_28'].'</a>')//如果是需要我们上门取件，提交运单后，再<tag1>，同时在备注里填写一会生成的运单号?><br>
        <?php }?>
    </span> 
</div>


	  
	 </div>
  </div>
<?php }?>



                </div>
              </div>

<?php if($addSource==1){?>
<!--版块-->
<div class="portlet">
  <div class="portlet-title">
	<div class="caption"><i class="icon-reorder"></i>
	<?=$LG['baoguo.show_2']//包裹信息?>
    <?=$bg_number?LGtag($LG['yundan.form_58'],'<tag1>=='.$bg_number):''//共<tag1>个包裹?> 
    </div>
	<div class="tools"> <a href="javascript:;" class="collapse"></a> </div><!--缩小expand 展开collapse-->
  </div>
  <div class="portlet-body form" style="display: block;"> <!--缩小none 展开block-->
	<!--表单内容-开始-->
	
<?php $yundan_bg=yundan_bg_list($bgid,$callFrom='member');?>

<span class="help-block" style="padding:10px; ">
<?php 
 //$bgid=$rs['bgid'];
 //$show_small=1;//简洁显示
 $notlist=1;//不输列表,需要带有$yundan_bg
 $groupid=$Mgroupid;//$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");
 require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/bg_hx_fh.php');
?>
 <input name="baoguo_hx_fee" type="hidden" value="<?=$baoguo_hx_fee?>"/>

 </span>				  
	
	<!--表单内容-结束-->
  </div>
</div>
<?php }?>



			  
            <!--物品信息版块-->
			 <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>
				  <?=$LG['yundan.form_24']//物品信息?> 
				  
				  <?=$go_number?LGtag($LG['yundan.form_58_1'],'<tag1>=='.$go_number):''//共<tag1>种商品?> 
                  </div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a></div>
                </div>
                <div class="portlet-body form" style="display: block;">
                  <!--表单内容-->
                  
                
<!--物品表单-开始-->
<?php 
$tag=make_password(20);
$customs=channelPar(spr($warehouse),spr($channel),'customs');



if($addSource==1&&$lx=='add'){
	$fromtable='baoguo';$fromid=$bgid;
}elseif($addSource==7&&$lx=='add'){
	$fromtable='daigou';$fromid=$goid;
}else{
	$fromtable='yundan';$fromid=spr($rs['ydid']); 
	if($lx=='edit'&&$wupinNotKeep){$fromid=0;}//新渠道不支持旧渠道的物品
}



if(!$customs){
	wupin_from_general($fromtable,$fromid,'',$addSource);//通用物品表单
}elseif($customs=='gd_mosuda'){
	if(!$ON_gd_mosuda){echo $customs.$LG['pptCloseGD'];}
	
	if($addSource==7){
		require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/gd_mosuda/call/wupin_LimitOP.php');//gd_mosuda物品表单
	}else{
		require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/gd_mosuda/call/wupin_form.php');//gd_mosuda物品表单
	}
}
?>
<input type="hidden" id="old_customs" value="<?=$customs?>">
<input type="hidden" name="tag" value="<?=$tag?>">
<!--物品表单-结束-->
                  
					
					<div class="form-group"><br></div>
					<div class="form-group" id="hide" style="display:<?=$fx_number>0?'block':'none';?>;">
						<label class="control-label col-md-2"><?=$LG['yundan.fx_content'];//分包要求?></label>
						<div class="col-md-10">
							<textarea  class="form-control" rows="3" name="fx_content"><?=cadd($rs['fx_content'])?></textarea>
						<?php if($addSource==1&&$member_per[$Mgroupid]['baoguo_fx']>0&&$lx=='add'){?>	
						<font class="red2">&raquo; 
                        <?=LGtag($LG['yundan.form_59'],
							'<tag1>=='.$member_per[$Mgroupid]['baoguo_fx'].'||'.
							'<tag2>=='.baoguo_Status(4));//每个包裹最多只能分包下单发货<tag1>次，达到数量时该包裹将自动变更状态为【<tag2>】，不可再下单?><br>
                            
						&raquo; <?=$LG['yundan.form_25'];//填写物品重量时则按重量分包，否则按所填数量分包?><br></font>
						<?php }?> 
						</div>
					</div>
					
					 <div class="form-group">
						<label class="control-label col-md-2"><?=$LG['yundan.weightEstimate'];//预估重量?></label>
						<div class="col-md-10 <?=$addSource==2||$ON_yundan_prepay?' has-error':''?>">
                        
<input type="text"  class="form-control input-small  popovers" data-trigger="hover" data-placement="top"  
    data-content="<?php if(have($addSource,'1,7')){echo $LG['yundan.form_26'];}elseif($addSource==2){echo $LG['yundan.form_26_1'];}?>"
    onafterpaste="value=value.replace(/[^\d\.]/g,'');" 
    onKeyUp="value=value.replace(/[^\d\.]/g,'');calc();" 
    <?=$addSource==2||$ON_yundan_prepay?' required ':''?> 
    <?=$ON_yundan_prepay&&have($addSource,'1,7')&&$weightEstimate>0?' readonly ':''?>
    name="weightEstimate" id="weightEstimate" value="<?=spr($weightEstimate,2,0)//要预付时,不能为0,需要手工填写重量?>"
/><?=$XAwt?>


<?php if($ON_yundan_prepay){?>                         
<a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?php if($member_per[$Mgroupid]['off_zjxd_calc']){echo $LG['yundan.form_60'];}//将按该重量预估费用，下单后您可以预先支付。待包裹入库后再次称重，如重量有误差则再补扣差费或退差费 ?>"> <i class="icon-info-sign"></i> </a>
<?php }?>                          

						   </div>
					  </div>

<?php if($addSource==2){?> 
    <div class="form-group">
        <label class="control-label col-md-2"><?=$LG['yundan.cc_chang'];//预估尺寸?></label>
        <div class="col-md-10">
        <?=$LG['length']?><input name="cc_chang" type="text"  value="<?=cadd($rs['cc_chang'])?>" class="form-control input-xsmall"  /><?=$XAsz?>
        *
        <?=$LG['width']?><input name="cc_kuan" type="text" value="<?=cadd($rs['cc_kuan'])?>" class="form-control input-xsmall" /><?=$XAsz?>
        *
        <?=$LG['high']?><input name="cc_gao" type="text" value="<?=cadd($rs['cc_gao'])?>" class="form-control input-xsmall" /><?=$XAsz?>
        </div>
    </div>
<?php }?>

					 		  				  
                </div>	
              </div>
			  
			
            <!--其他要求版块-->
			 <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['yundan.form_29'];//服务要求?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a></div>
                </div>
                <div class="portlet-body form" style="display: block;">
                  <!--表单内容-->
					
					 <div class="form-group">
						<label class="control-label col-md-2"><?=$LG['yundan.declarevalue'];//物品价值?></label>
						<div class="col-md-10">
						  <input type="text"  class="form-control input-small"  name="declarevalue" id="declarevalue" 
                          value="<?=$declarevalue?><?=spr($rs['declarevalue'])?>"  
                          onBlur="value=value.replace(/[^\d\.]/g,'');limitInput(this);calc_insurance();onlyNum();" 
                          readonly
                          /><?=$XAsc?>
						  <span class="help-block red" id="text"></span>
						  </div>
					  </div>
					  
                     
					 <div class="form-group" style=" <?=!$member_per[$Mgroupid]['off_insurance']?'display:none;':''?>">
						<label class="control-label col-md-2"><?=$LG['yundan.insureamount'];//物品保价?></label>
						<div class="col-md-10">
						  <input type="text"  class="form-control input-small" name="insureamount" id="insureamount" value="<?=$insureamount?><?=spr($rs['insureamount'])?>"  onafterpaste="value=value.replace(/[^\d\.]/g,'');" onKeyUp="value=value.replace(/[^\d\.]/g,'');calc_insurance();onlyNum();setTimeout('calc()','1000');"/><?=$XAsc?>
						  
						  (<?=$LG['yundan.form_61'];//需付保价费?>:
						  <font id="msg_insurevalue" class="red2"><?=$insurevalue?><?=spr($rs['insurevalue'])?></font>
						  <input type="hidden" class="form-control input-small" name="insurevalue"  id="insurevalue" value="<?=$insurevalue?><?=spr($rs['insurevalue'])?>" readonly/><?=$XAmc?>)
						  
						   <span class="help-block"> 
                               <span id="baoxian_ts"><?=$LG['yundan.form_30'];//不买保险请留空或填0，不能超过发票上的价值；?></span>
                               <?=LGtag($LG['yundan.form_63'],
								'<tag1>==<span id="baoxian_1">'.$baoxian_1.'</span>'.$XAsc.'||'.
								'<tag2>==<span id="baoxian_2">'.$baoxian_2.'</span>||'.
								'<tag3>==<span id="baoxian_3">'.$baoxian_3.'</span>');?>
                               
                               <?=$LG['yundan.form_62']?>:
                               <span id="baoxian_4"><?=$baoxian_4?></span>
                               ～
                               <span id="baoxian_5"><?=$baoxian_5?></span><!--渠道最大限制,物品最大金额-->
                               <?=$XAsc?>
                               
                              (<a href="<?php $xacd=ClassData($peifu_classid);echo pathLT($xacd['path']);?>" target="_blank"><?=cadd($xacd['name'])?></a>)
                           </span>
						    </div>
					  </div>
					

				<div class="form-group"><br></div>	
 
                
                
                <!--运单服务-->	
                <span id="yundan_service"></span>

				
				
				<div class="form-group">
					<label class="control-label col-md-2"><?=$LG['yundan.prefer'];//使用优惠?></label>
					<div class="col-md-10">
						<div class="radio-list">

<label class="radio-inline">
<input name="prefer" type="radio" value="0" <?=!$rs['prefer']?'checked':''?>/>
 <?=$LG['useNot'];//不使用?>
</label>  

<label class="radio-inline">
<input type="radio" name="prefer" value="1" <?=$rs['prefer']==1?'checked':''?> > <?=$LG['useCoupons'];//优先用优惠券?><br>
</label>  

<label class="radio-inline">
<input type="radio" name="prefer" value="2" <?=$rs['prefer']==2?'checked':''?>> <?=$LG['useDiscount'];//优先用折扣券?><br>
</label>  

<?php if($off_integral){?>
<label class="radio-inline">
<input type="radio" name="prefer" value="3" <?=$rs['prefer']==3||$lx=='add'?'checked':''?>> <?=$LG['useIntegral'];//积分抵消?>
&nbsp;&nbsp;<a onMouseOver="show('div1')" onMouseOut="hide('div1')"> <i class="icon-info-sign"></i> </a><br>
</label> 
 
<?php }?>

						</div>
                        
						<div id="div1" style="display:none;" class="help-block"> 
						<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/integral/ts_call.php');?>
						</div>
                        
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-2"><?=$LG['yundan.kffs'];//自动扣费?></label>
					<div class="col-md-10">
						<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="kffs" value="1"  <?php if($rs['kffs']||$lx=='add'){echo 'checked';}?> />
                      </div>
                      <a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['yundan.form_36'];//仓库称重算好费用后，自动从您的账户扣除费用(账户需有足够余额)?>"> <i class="icon-info-sign"></i> </a>
                      
					  
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-2"><?=$LG['yundan.content'];//备注说明?></label>
					<div class="col-md-10">
						<textarea  class="form-control" rows="3" name="content"><?=cadd($rs['content'])?></textarea>
					</div>
				</div>
				

			</div>
			</div>
			
<?php 
//地址资料-----------------------------------------------------------------------------------
if($lx=='add')
{
	if(spr($addid)){
		$mrs=FeData('member_address','*',"addid='{$addid}' {$Mmy}");
	}else{
		$mrs=FeData('member_address','*',"mrs='1' {$Mmy}");
	}
	
	$mrf=FeData('member_address','*',"mrf='1' {$Mmy}");
}
$call_table='yundan';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_form.php');
?>

			
			 
			  
          </div>
          </div>
        </div>
		
<!--提交按钮固定--> 
<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">

    <div style=" padding:10px;">
    
<table width="100%">
    <tr>
      <td style="padding-left:200px;">
        <input type="checkbox"  onClick='document.getElementById("agreed").disabled=!this.checked;' autocomplete="off"/>
        <strong><a href="<?php $xacd=ClassData($peifu_classid);echo pathLT($xacd['path']);?>" target="_blank"><?=LGtag($LG['yundan.form_65'],'<tag1>=='.cadd($xacd['name']))?></a></strong>
        <?=$ON_yundan_prepay?'<font class="gray_prompt red2" >('.$LG['yundan.save_19'].')</font>':''?>
        
        <!--费用显示--开始-->
        <span style=" float:right">
        <?=$LG['yundan.form_37'];//预估费用?>
        <span id="msg_fee" class="show_price">0</span><?=$XAmc?>
        &nbsp;&nbsp;
        (
            <span id="msg_fee_freight"></span><!--单运费-->
            
            <font class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['yundan.21'];//部分渠道需要清关时才能计算?>"><span id="msg_fee_tax"></span></font><!--单税费-->
    
            <span id="msg_fee_ware"></span><!--单仓储费-->
     
            <font class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['yundan.22'];//如增值服务费、体积费、手续费等?>"><span id="msg_fee_other"></span></font><!--其他费-->
        )
        <a href="javascript:void(0)" onClick="calc();" class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['recalculate']?>" style="margin-left:10px;"><i class="icon-list-alt"></i></a>
        </span>
        <!--费用显示--结束-->
      </td>
    </tr>
    <tr>
      <td style="padding-left:200px;">
    <button type="button" id="agreed" disabled class="btn btn-primary input-medium" 
    onClick="submit_chk();"
    > 
    <i class="icon-ok"></i> <font id="submit_name"><?=$LG['yundan.form_53']?></font>
    </button>
    
    <input  type="submit"  id="submit_none"  style="display: none;"  disabled/>
    <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>

      </td>
    </tr>
</table>
    </div>
            
            
            
            
            
            
</div>




         
      </div>
      
      
    
    </div>
    
  </form>
  <div class="xats">
	&raquo; <?=$LG['yundan.form_49'];//通过审核后将不可再修改，请检查清楚（特别是收件人信息）?><br>
 </div>
  
</div>


<?php
$CountryRequired=1;//yundanJS.php 参数:国家是否必选

//专用于申请商品备案,其他用途需要另外调用
$showbox=1;//是否用到 操作弹窗 (/public/showbox.php)
$SB_OFFRefresh=1;//1禁止返回刷新
$SB_JSEvent='gd_mosuda_list();';//点击关闭时触发JS事件
//-----------

$forForm=1;//来自表单(传值给yundanJS.php)
require_once($_SERVER['DOCUMENT_ROOT'].'/js/yundanJS.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
<script>

//分包相关-开始
function dis(obj)
{
	if (obj=='1')
	{
		document.getElementById("hide").style.display="block";
		document.getElementById("hide2").style.display="block";
	}else{
		document.getElementById("hide").style.display="none";
		document.getElementById("hide2").style.display="none";
	}
}


function divide()
{
	var weightEstimate=<?=spr($weightEstimate)?>;
	var fx_number=parseInt(document.getElementsByName("fx_number")[0].value);
	var fx_total=parseInt(document.getElementsByName("fx_total")[0].value);
	if(fx_total>fx_number){fx_number=fx_total;}
	

	//分包时平分重量
	var eless = document.getElementsByName("fx");//必须用Name
	   for(var i=0;i<eless.length;i++)
	  {
		 if(eless[i].checked)
		  {
		   var fx=eless[i].value;//必须加var全局变量 
		   break;//获取后退出，不再获取后面 
		  }
	  }
	if (typeof(fx) == "undefined"){var fx=0;}//判断
	fx=parseInt(fx);
	
	if(weightEstimate>0&&fx==1)
	{
		document.getElementsByName("weightEstimate")[0].value=decimalNumber(weightEstimate/fx_number,2);
	}else if(weightEstimate>0){
		document.getElementsByName("weightEstimate")[0].value=weightEstimate;//要预付时,不能为0,需要手工填写重量
	}else{
		document.getElementsByName("weightEstimate")[0].value='';//要预付时,不能为0,需要手工填写重量
	}
	
	//显示按钮和标题名称
	if(fx==1){
		<?php if($fx_count){?>
		var fx_count=<?=$fx_count?>;
		<?php }else{?>
		var fx_count=fx_total-fx_number;
		if(fx_count<=0){fx_count=1;}
		<?php }?>
	}
	
	if(fx_count>0)
	{
		document.getElementById("title_name").innerHTML='<?=LGtag($LG['yundan.form_66'],'<tag1>==\'+fx_count+\'')?>';
		document.getElementById("submit_name").innerHTML='<?=LGtag($LG['yundan.form_67'],'<tag1>==\'+fx_count+\'')?>';
	}else{
		document.getElementById("title_name").innerHTML='<?=$LG['yundan.form_10']?>';
		document.getElementById("submit_name").innerHTML='<?=$LG['yundan.form_53']?>';
	}
}
//分包相关-结束
</script>


<script>
	//要放FOOT后面
	$(function(){ country_show('<?=$Mgroupid?>','<?=spr($country)?>'); });//显示国家下拉
<?php if(have($addSource,'1,7')){?>
	$(function(){ CalcDeclareValue();});//物品价值
	$(function(){ calc_insurance();});//计算物品价值
	<?php if($lx=='add'){?>	$(function(){ divide(); }); //分包时平分重量	<?php }?>
<?php }?>

	$(function(){ channelPar(); });//渠道参数
	$(function(){ yundan_service(); });//渠道附加服务,必须要放在calc前面
	$(function(){ 	if($('#msg_fee').length>0){ calc(); }	 });
</script>
