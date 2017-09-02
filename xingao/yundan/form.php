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
$pervar='yundan_ed,yundan_ad';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="运单";$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$ydid=par($_GET['ydid']);
$copy=par($_GET['copy']);
$sm=par($_GET['sm']);
$addSource=spr($_GET['addSource']);
$warehouse=spr($_GET['warehouse']);
$country=spr($_GET['country']);
$channel=spr($_GET['channel']);
$username=cadd($_GET['username']);
$userid=spr($_GET['userid'],0,0);
$callFrom='manage';//manage member

if($_GET['tag']){$_SESSION[$_GET['tag']]='';}
if(!$lx){$lx='add';}


if($lx=='edit'||$copy)//修改==============
{
	if(!$ydid){exit("<script>alert('ydid{$LG['pptError']}');goBack('c');</script>");}
	
	$rs=FeData('yundan','*',"ydid='{$ydid}'");
	warehouse_per('ts',$rs['warehouse']);//验证可管理的仓库
	
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
	if(!$username){$username=cadd($rs['username']);}
	if(!$userid){$userid=$rs['userid'];}
		
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
	$addSource=4;
	$rs['status']=0;
}




//变更渠道时,显示不同物品表单
$status="lx={$lx}&ydid={$ydid}&addSource={$addSource}&copy={$copy}&sm={$sm}&warehouse={$warehouse}&country={$country}&channel={$channel}&username={$username}&userid={$userid}";


if(!$copy)
{
	unset($_SESSION['tem_ydh']);//清空临时复制的单号
	//生成令牌密钥(为安全要放在所有验证的最后面)
	$token=new Form_token_Core();
	$tokenkey= $token->grante_token("yundan".$ydid);
}
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <!--<a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > -->
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
<small>
	<?php if($lx=='edit'){?>
	<?=cadd($rs['ydh'])?> <span class="xa_sep"> | </span>
	来源:<?=yundan_addSource($rs['addSource'])?> <span class="xa_sep"> | </span>
	下单:<?=DateYmd($rs['addtime'],1)?>
	<?php }?>					
</small>
        </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
	<form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
	<input name="lx" type="hidden" value="<?=add($lx)?>">
	<input name="ydid" type="hidden" value="<?=$rs['ydid']?>">
	<input name="bgid" type="hidden" value="<?=$bgid?>">
	<input name="goid" type="hidden" value="<?=$goid?>">

	<input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
	<input name="copy" type="hidden" value="<?=$copy?>">
	
 	<input name="addSource" type="hidden" value="<?=$addSource?>" />
  <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
			
            <!--版块-->
			 <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>基本信息</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a></div><!--默认关闭:class="expand"-->
                </div>
                <div class="portlet-body form" style="display: block;"> <!--默认关闭:display: none;-->
                  <!--表单内容-->

                  <div class="form-group">
                    <label class="control-label col-md-2">会员</label>
                    <div class="col-md-10 has-error">
					 <input type="text" class="form-control input-small" name="userid" autocomplete="off"   value="<?=$userid?>" title="会员ID"  placeholder="会员ID" onBlur="getUsernameId('userid');country_show('','<?=spr($country)?>');">
                    
                     <input type="text" class="form-control input-medium" name="username" autocomplete="off"  value="<?=$username?>" title="会员名"  placeholder="会员名(可不填)" onBlur="getUsernameId('username');country_show('','<?=spr($country)?>');">
                     
                      <a href="javascript:void(0)" class=" tooltips" data-container="body" data-placement="top" data-original-title="可以只填写一项,使用优先从右到左"> <i class="icon-info-sign"></i> </a>
                 
					
                    </div>
                  </div>


				<div class="form-group" <?=$warehouse_more?'':'style="display:none"'?>>
                    <label class="control-label col-md-2">所在仓库</label>
                    <div class="col-md-4 has-error">
                     <select name="warehouse" class="form-control input-medium select2me" required  data-placeholder="请选择"  onChange="refresh_form();"><!--country_show('','<?=spr($country)?>');-->
					 <?php warehouse($warehouse,1,1);?>
					 </select>
                    </div>

					<label class="control-label col-md-2">仓位</label>
					<div class="col-md-4">
					  <input type="text" class="form-control input-medium" name="whPlace" value="<?=cadd($rs['whPlace'])?>">
				  </div>
                  

                  
				 </div>
                  
                  
				  <?php if($ON_country){?>
                     <div class="form-group">
                     <label class="control-label col-md-2">寄往国家</label>
                    <div class="col-md-10 has-error">
                       <span id="country"></span>
                    </div>
                    </div>
                  <?php }else{?>
                        <input type="hidden"  name="country" value="<?=$country?spr($country):$openCountry;?>">
                  <?php }?>

                
                  <div class="form-group">
                    <label class="control-label col-md-2">运输渠道</label>
                    <div class="col-md-10 has-error">
					  <span id='channel'></span>
                    
                      <span class="help-block">
                      <font class="red">&raquo; 请先认真选择渠道再填写后面内容，变更渠道时清关要求也会变更，因此后面所填写内容可能会清空。</font><br>

                      <font id="channel_content"></font>
                      </span>

                    </div>
                    
                    
                  </div>



<div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['status']//状态?></label>
                    <div class="col-md-4 has-error">
                  <select  class="form-control input-medium select2me" data-placeholder="请选择" name="status" required >
                    <option></option>
                   <?php yundan_Status(spr($rs['status']))?>
                  </select>
                  
				  <?php 
					$statusauto=$rs['statusauto'];
					if($off_statusauto&&$yd_statusauto&&$lx=='add'){$statusauto=1;}
				  ?>
                  <div class="make-switch tooltips" data-container="body" data-placement="top" data-original-title="自动更新状态" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="statusauto" value="1"  <?php if($statusauto){echo 'checked';}?> />
                  </div>
                  <a href="javascript:void(0)" class=" tooltips" data-container="body" data-placement="top" data-original-title="更新时间:<?=DateYmd($rs['statustime'],1)?>"> <i class="icon-info-sign"></i> </a>
                  
                  
				
                    </div>
                    
                    <label class="control-label col-md-2">支付状态</label>
                    <div class="col-md-4">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="pay" value="1"  <?php if($rs['pay']){echo 'checked';}?> />
                      </div>
                      <a href="javascript:void(0)" class=" tooltips" data-container="body" data-placement="top" data-original-title="如果要会员补付费用,则设为未支付,否则一般不用修改"> <i class="icon-info-sign"></i> </a>
					 
                    </div>
                    
                    
                  </div>

				  <div class="form-group">
                    <label class="control-label col-md-2">派送快递</label>
                    <div class="col-md-10">
                  <select  class="form-control input-medium select2me" data-placeholder="请选择" name="gnkd">
                    <option></option>
                   <?php yundan_gnkd(cadd($rs['gnkd']))?>
                  </select>
               	  <input type="text" class="form-control input-medium" name="gnkdydh" value="<?=cadd($rs['gnkdydh'])?>" placeholder="快递单号">
                    </div>
                    </div>
                    




				  
				  <div class="form-group">
                    <label class="control-label col-md-2">本站运单号</label>
                    <div class="col-md-4 has-error">
               		<input type="text" class="form-control input-medium  tooltips" data-container="body" data-placement="top" data-original-title="留空则自动生成 （不可重复）" name="ydh" value="<?=$copy?copyOrderNo('yundan',$rs['ydh'],$copy_typ=2):cadd($rs['ydh'])?>" >
				
                    </div>

                    <label class="control-label col-md-2">第三方转运单号</label>
                    <div class="col-md-4">
               		<input type="text" class="form-control input-medium" name="dsfydh" value="<?=cadd($rs['dsfydh'])?>">
                    </div>

				 </div>
				  
				 <div class="form-group">
					<label class="control-label col-md-2">批次号</label>
					<div class="col-md-4">
					  <input type="text" class="form-control input-medium" name="lotno" value="<?=cadd($rs['lotno'])?>">
				  </div>

					<label class="control-label col-md-2">HG码/HS码</label>
					<div class="col-md-4">
					  <input type="text" class="form-control input-medium" name="hscode" value="<?=cadd($rs['hscode'])?>">
				  </div>

                  
				 </div>
					  
                  <div class="form-group">  
					<label class="control-label col-md-2">寄库快递单号</label>
					<div class="col-md-4">
					  <input type="text" class="form-control input-medium" name="gwkdydh" value="<?=cadd($rs['gwkdydh'])?>">
				  </div>

                <label class="control-label col-md-2">预估重量</label>
					<div class="col-md-4">
					  <input type="text"  class="form-control input-small  popovers" data-trigger="hover" data-placement="top"  data-content="可填写大概重量以便检查是否超过所选渠道的重量限制"  name="weightEstimate" id="weightEstimate" value="<?=spr($rs['weightEstimate'])?>"  onafterpaste="value=value.replace(/[^\d\.]/g,'');" onKeyUp="value=value.replace(/[^\d\.]/g,'');"/><?=$XAwt?>
				  </div>
                  
                  </div>
					  
				 

				  

                  
                  
                    

                
				<div class="form-group">
					<label class="control-label col-md-2">托盘号</label>
					<div class="col-md-10">
<?php 
    //$classtag='1';//标记:同个页面,同个$classtype时,要有不同标记
    $classtype=3;//分类类型
    $classid=$rs['classid'];//已保存的ID
    require($_SERVER['DOCUMENT_ROOT'].'/public/classify.php');
?>
				  </div>
				 </div>
                  
				
                 
                 <div class="form-group">
					<label class="control-label col-md-2">出库时间</label>
					<div class="col-md-10">
						<input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="chukutime_date" value="<?=DateYmd($rs['chukutime'],2)?>">
					
						<input type="text" id="clockface_2"  name="chukutime_time" value="<?=DateYmd($rs['chukutime'],'H:i');?>" class="form-control input-xsmall" readonly style="margin-right:0px;">
						<button class="btn btn-default" type="button" id="clockface_2_toggle"><i class="icon-time"></i></button>
					</div>
				</div>

                  
				 
				  
				  
				 <div class="form-group">
					<label class="control-label col-md-2">尺寸</label>
					<div class="col-md-10">
					  长<input name="cc_chang" type="text"  value="<?=cadd($rs['cc_chang'])?>" class="form-control input-xsmall"  /><?=$XAsz?>
					  *
					  宽<input name="cc_kuan" type="text" value="<?=cadd($rs['cc_kuan'])?>" class="form-control input-xsmall" /><?=$XAsz?>
					  *
					 高 <input name="cc_gao" type="text" value="<?=cadd($rs['cc_gao'])?>" class="form-control input-xsmall" /><?=$XAsz?>
	 
				  </div>
				 </div>
                 

				  
                  
			
				
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
	
<?php $yundan_bg=yundan_bg_list($bgid,$callFrom='manage');?>

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

             <?php if($rs['bgid']){?>
                  <div class="form-group">
                    <label class="control-label col-md-2">所选包裹是否有分包<a class="tooltips" data-container="body" data-placement="top" data-original-title="只是为了在打包时方便了解情况"><i class="icon-info-sign"></i></a></label>
                    <div class="col-md-10">
                     <select name="fx" class="form-control input-small select2me" data-placeholder="请选择" >
                       <option value="0" <?=!$rs['fx']?'selected':'';?>>无分包</option>
                       <option value="1" <?=$rs['fx']||$copy?'selected':'';?>>有分包</option>
					 </select>
                    </div>
                  </div>
					  
					<div class="form-group">
						<div class="control-label col-md-2 right">分包要求</div>
						<div class="col-md-10">
                            <textarea  class="form-control" rows="3" name="fx_content"><?=cadd($rs['fx_content'])?></textarea>
						</div>
					</div>
                    
					<span class="help-block" style="padding:10px; ">
						 <?php 
						 //$bgid=$rs['bgid'];
						 $groupid=FeData('member','groupid',"userid='{$rs['userid']}'");
						 require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/bg_hx_fh.php');
						 ?>
					 </span>				  
            <?php }?>
					 		  				  
                </div>	
              </div>
			  
			
            <!--其他要求版块-->
			 <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>服务要求</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a></div>
                </div>
                <div class="portlet-body form" style="display: block;">
                  <!--表单内容-->
					
					 <div class="form-group">
						<label class="control-label col-md-2">物品价值</label>
						<div class="col-md-10">
						  <input type="text"  class="form-control input-small"  name="declarevalue" id="declarevalue" value="<?=$declarevalue?><?=spr($rs['declarevalue'])?>"  onafterpaste="value=value.replace(/[^\d\.]/g,'');" onKeyUp="value=value.replace(/[^\d\.]/g,'');limitInput(this);calc_insurance();onlyNum();"/><?=$XAsc?>
						  <span class="help-block red" id="text"></span>
						   </div>
					  </div>
					  
                      <?php 
					  if($rs['userid']){$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");}
					  $off_insurance=$member_per[$groupid]['off_insurance'];
					  ?>
					 <div class="form-group" style=" <?=!$off_insurance&&$lx!='add'?'display:none;':''?>">
						<label class="control-label col-md-2">物品保价</label>
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
                               <span id="baoxian_4"><?=$baoxian_4?></span>～<span id="baoxian_5" style="display:none;"><?=$baoxian_5?></span> <span id="lblinsureamounte">0</span> <?=$XAsc?>
                               
                              (<a href="<?php $xacd=ClassData($peifu_classid);echo pathLT($xacd['path']);?>" target="_blank"><?=cadd($xacd['name'])?></a>)
                           </span>
						    </div>
					  </div>
					 

				<div class="form-group"><br></div>		

				
 
                
                
                <!--运单服务-->	
                <span id="yundan_service"></span>
				
				
				<div class="form-group">
					<label class="control-label col-md-2">使用优惠</label>
					<div class="col-md-10">
						<div class="radio-list">

<label class="radio-inline">
<input type="radio" name="prefer" value="1" <?=$rs['prefer']==1?'checked':''?> > 优先用优惠券<br>
</label>  

<label class="radio-inline">
<input type="radio" name="prefer" value="2" <?=$rs['prefer']==2?'checked':''?>> 优先用折扣券<br>
</label>  

<?php if($off_integral){?>
<label class="radio-inline">
<input type="radio" name="prefer" value="3" <?=$rs['prefer']==3?'checked':''?>> 积分抵消
<a onMouseOver="show('div1')" onMouseOut="hide('div1')"><i class="icon-info-sign"></i></a><br>
</label>  
<?php }?>

<label class="radio-inline">
<input name="prefer" type="radio" value="0" <?=!$rs['prefer']?'checked':''?>/>
不使用 
</label>  
						</div>
						<div id="div1" style="display:none;" class="help-block"> 
						<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/integral/ts_call.php');?>
						</div>
					</div>
				</div>
				

				<div class="form-group">
					<label class="control-label col-md-2">自动扣费</label>
					<div class="col-md-10">
						<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="kffs" value="1"  <?php if($rs['kffs']){echo 'checked';}?> /><!--||$lx=='add'-->
                      </div>
					
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-2">备注说明</label>
					<div class="col-md-10">
<textarea  class="form-control tooltips" data-container="body" data-placement="top" data-original-title="会员和后台都可见并且都可修改" rows="3" name="content"><?=$copy?"操作员从".cadd($rs['ydh'])."运单号分包出来；":''?>
<?=cadd($rs['content'])?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-2">回复内容</label>
					<div class="col-md-10">
						<textarea  class="form-control tooltips" data-container="body" data-placement="top" data-original-title="会员和后台都可见" rows="3" name="reply"><?=cadd($rs['reply'])?>
</textarea>
					</div>
				</div>
                
				<div class="form-group">
					<label class="control-label col-md-2">管理备注</label>
					<div class="col-md-10">
						<textarea  class="form-control tooltips" data-container="body" data-placement="top" data-original-title="只有后台可见" rows="3" name="manage_content"><?=$rs['manage_content']?cadd($rs['manage_content']):'审核员'.$Xuserid?></textarea>
					
					</div>
				</div>
				

			</div>
			</div>
			
			 		  
						  
<?php 
//地址资料-----------------------------------------------------------------------------------
$call_table='yundan';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_form.php');
?>





			
           
			 
			  
          </div>
          </div>
        </div>
        
        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">




		<input type="hidden" name="calc">
        <input style="display: none;"  disabled type="submit" id="submit_none"/>
        
		<button type="button" class="btn btn-primary input-small" id="openSmt1" disabled
		  onClick="
		  document.xingao.calc.value='0';
          submit_chk();
		  ">
		 <i class="icon-ok"></i> <?=$LG['submit']?> </button>
		  
		<button type="button" class="btn btn-warning input-small" id="openSmt2" disabled style="margin-right:30px;"
		  onClick="
		  document.xingao.calc.value='1';
          submit_chk();
		  ">
		 <i class="icon-ok"></i> 提交后计费 </button>
         
          <?php if($lx=='edit'){?>
          <a  href="?lx=add&copy=1&ydid=<?=$ydid?>"
           class="btn btn-default tooltips" data-container="body" data-placement="top" data-original-title="复制这个运单 (如有分包，建议把当前表单设置为【有分包】)" target="_blank"><i class="icon-copy"></i>  分包 </a>
        <!--onClick="document.getElementsByName('fx')[0].focus();"-->		
		<?php }?>
          
		  <button type="reset" class="btn btn-default" > <?=$LG['reset']?> </button>
            
          <button type="button" class="btn btn-default" onClick="goBack('c');"  style="margin-left:30px;"><i class="icon-remove"></i> <?=$LG['close']?> </button>
 		 </div>
         
         
      </div>
    
    </div>
    
  </form>
    
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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>


<script language="javascript">
	$(function(){ country_show('','<?=spr($country)?>');	});//显示国家下拉
	$(function(){ channelPar(); });//渠道参数
	$(function(){ yundan_service(); });//渠道附加服务
</script>




<?php 
$edit_ppt='';
if(!$sm&&spr($rs['status'])==-1)
{
	$edit_ppt='该运单未入库，确定要编辑吗?\r如果确实未入库请勿修改状态！';
}

if($rs['pay'])
{
	//$edit_ppt='该运单已支付费用，确定要编辑吗?';
}

if($edit_ppt){
?>
<script language="javascript">
	$(function(){ 
	if(confirm("<?=$edit_ppt?>"))
	   {
		 //点确定:可以执行其他程序
		 return true;
	   }else{
		 //点取消:可以执行其他程序
		 goBack('c');
		 return false;
	   }
	});
</script>
<?php }?>

