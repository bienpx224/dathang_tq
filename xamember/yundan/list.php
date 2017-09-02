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


$headtitle=$LG['name.nav_17'];//运单管理
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

//处理:1125


$where="1=1";
$status=par($_GET['status']);
//取出保存的ID
$id_name='ydid';
if($_SESSION["old_status"]==$status)
{
	$id_checked=ToArr(par($_SESSION[$id_name]));
}else{
	$_SESSION[$id_name]='';
}
$_SESSION["old_status"]=$status;



if(CheckEmpty($status))
{
	switch($status)
	{
		case 'chuku':
			$where.=" and status>4 and status<30";
		break;
		
		case 'all':
			$where.="";
		break;
		
		default:
			$where.=" and status='{$status}'";
	}
}
$search.="&status={$status}";


//搜索
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$warehouse=par($_GET['warehouse']);
	$country=par($_GET['country']);
	$channel=par($_GET['channel']);
	$addSource=par($_GET['addSource']);
	$tally=par($_GET['tally']);
	
	$stime_add=par($_GET['stime_add']);
	$etime_add=par($_GET['etime_add']);
	$stime_chuku=par($_GET['stime_chuku']);
	$etime_chuku=par($_GET['etime_chuku']);
	     
	     
	if($key)
	{
		//联表查询:查包裹表	
		$query_table="select bgid from baoguo where bgydh like '%{$key}%'";
		$sql_table=$xingao->query($query_table);
		while($r=$sql_table->fetch_array())
		{
			$where_table.=" or find_in_set('{$r['bgid']}',bgid)";
		}

		//联表查询:查代购表	
		$query_table="select goid from daigou_goods where godh like '%{$key}%'";
		$sql_table=$xingao->query($query_table);
		while($r=$sql_table->fetch_array())
		{
			$where_table.=" or find_in_set('{$r['goid']}',goid)";
		}

		$where.=" and (ydid='".CheckNumber($key,-0.1)."' or lotno='{$key}' or whPlace='{$key}' or hscode like '%{$key}%' or ydh like '%{$key}%' or gwkdydh like '%{$key}%' or gnkdydh like '%{$key}%' or dsfydh like '%{$key}%' or s_name like '%{$key}%' or s_mobile like '%{$key}%'  {$where_table}  )";
	}
		
	if(CheckEmpty($warehouse)){$where.=" and warehouse='{$warehouse}'";}
	if(CheckEmpty($country)){$where.=" and country='{$country}'";}
	if(CheckEmpty($channel)){$where.=" and channel='{$channel}'";}
	if(CheckEmpty($addSource)){$where.=" and addSource='{$addSource}'";}
	if(CheckEmpty($tally)){$where.=" and tally='{$tally}'";}
	if($stime_add){$where.=" and addtime>='".strtotime($stime_add." 00:00:00")."'";}
	if($etime_add){$where.=" and addtime<='".strtotime($etime_add." 23:59:59")."'";}
	if($stime_chuku){$where.=" and chukutime>='".strtotime($stime_chuku." 00:00:00")."'";}
	if($etime_chuku){$where.=" and chukutime<='".strtotime($etime_chuku." 23:59:59")."'";}
	
	//筛选菜单
	$field=par($_GET['field']);
	$zhi=par($_GET['zhi']);
	if(CheckEmpty($field)&&CheckEmpty($zhi)){$where.=" and {$field}='{$zhi}' ";}

	$search.="&so={$so}&key={$key}&warehouse={$warehouse}&country={$country}&channel={$channel}&addSource={$addSource}&stime_add={$stime_add}&etime_add={$etime_add}&stime_chuku={$stime_chuku}&etime_chuku={$etime_chuku}";
}

$order=' order by ydh desc,status asc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$_SESSION['Mexport_yundan']=$where;
$query="select * from yundan where {$where}  {$Mmy} {$order}";

//分页处理
//$line=20;$page_line=15;//$line=-1则不分页(不设置则默认)
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
?>

<div class="page_ny"> 
	<!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title"> 
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a> 
        </h3>
	<ul class="page-breadcrumb breadcrumb">
	<?php 
		  $callFrom='member';
		  require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/options_menu.php'); 
	  ?>
	  <button type="button" class="btn btn-default" onClick="location.href='/public/idSave.php?lx=sc&id_name=<?=$id_name?>';"><i class="icon-trash"></i> <?=$LG['yundan.list_1'];//清空所有勾选?> </button>
	  
	 <button type="button" class="btn btn-default" onClick="AllTrOpen();" id="AllTrBlack"><i class="icon-resize-full" id="AllTrBlackIco"></i> <font id="AllTrBlackName"><?=$LG['allOpen']//全部展开?></font>  </button>
		</ul>
			<!-- END PAGE TITLE & BREADCRUMB--> 
		</div>
	</div>
	<!-- END PAGE HEADER--> 
	
	<!-- BEGIN PAGE CONTENT-->
	<div class="tabbable tabbable-custom boxless">
		<ul class="nav nav-tabs">
			<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/yundan/call/nav_num.php');?> 
			
			<li class="<?php if($status=='all'){echo 'active';$ydnum_status_all='<span class="badge badge-default">'.$num.'</span>';}?>" style="margin-right:30px;"><a href="?status=all"><?=$LG['all']?><?=$ydnum_status_all?></a></li>
			
			<li class="<?php if($status=='-1'){echo 'active';$ydnum_status_01='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=-1"><?=$status_01?><?=$ydnum_status_01?></a></li>
			
			<?php if($ON_yundan_prepay){?>
			<li class="<?php if($status=='-2'){echo 'active';$ydnum_status_02='<span class="badge badge-default">'.$num.'</span>';}?>  popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['yundan.27']//同个包裹只要有一个分包未支付,该包裹全部分包都会留在此处,全部支付后仓库才处理?>"><a href="?status=-2"><?=$status_02?><?=$ydnum_status_02?></a></li>
            <?php }?>
            
			<li class="<?php if($status=='0'){echo 'active';$ydnum_status_0='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=0"><?=$status_0?><?=$ydnum_status_0?></a></li>
			
			<li class="<?php if($status=='1'){echo 'active';$ydnum_status_1='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=1"><?=$status_1?><?=$ydnum_status_1?></a></li>
            
			<li class="<?php if($status=='2'){echo 'active';$ydnum_status_2='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=2"><?=$status_2?><?=$ydnum_status_2?></a></li>
			
			<li class="<?php if($status=='3'){echo 'active';$ydnum_status_3='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=3"><?=$status_3?><?=$ydnum_status_3?></a></li>

			<li class="<?php if($status=='4'){echo 'active';$ydnum_status_4='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=4"><?=$status_4?><?=$ydnum_status_4?></a></li>
			
			<li class="<?php if($status=='chuku'){echo 'active';$ydnum_status_chuku='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=chuku"><?=$LG['yundan.list_26']?><?=$ydnum_status_chuku?></a></li>
				
			<li class="<?php if($status=='30'){echo 'active';$ydnum_status_30='<span class="badge badge-default">'.$num.'</span>';}else{$ydnum_status_30=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi='30',$CN_where=$Xwh,$CN_userid=$Muserid,$CN_color='default');}?>"><a href="?status=30"><?=$status_30?><?=$ydnum_status_30?></a></li>
			
		</ul>
		<div class="tab-content" style="padding:10px;"> 
			<!--搜索-->
			<div class="navbar navbar-default" role="navigation">
				
				<div class="collapse navbar-collapse navbar-ex1-collapse">
					<form class="navbar-form navbar-left"  method="get" action="?">
						<input name="so" type="hidden" value="1">
						<input name="status" type="hidden" value="<?=$status?>">
						<div class="form-group">
							<input type="text" name="key" class="form-control popovers" data-trigger="hover" data-placement="right"  data-content="<?=$LG['yundan.list_3']?>" placeholder="<?=$LG['yundan.list_2'];//各类关键词?>"  value="<?=$key?>">
						</div>
                        
						<div class="form-group">
                          <select  class="form-control input-medium select2me" name="warehouse" data-placeholder="<?=$LG['warehouse'];//仓库?>" onChange="country_show('<?=$Mgroupid?>','<?=$country?>');">
                              <option></option>
                              <?=warehouse($warehouse,1)?>
                          </select>
						</div>
                        
                        
                  <?php if($ON_country){?>
                     <div class="form-group">
                        <span id="country"></span>
                     </div>
                  <?php }else{?>
                        <input type="hidden"  name="country" value="<?=$country?>">
                  <?php }?>
                  
                  <div class="form-group">
                        <span id='channel'></span>
                      </div>
                  <div class="form-group">
                        <select  class="form-control input-small select2me" name="addSource" data-placeholder="<?=$LG['yundan.list_4'];//来源?>">
                      <option></option>
                      <?=yundan_addSource($addSource,1)?>
                    </select>
                      </div>
                      
                <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="tally" data-placeholder="<?=$LG['yundan.list_5'];//月结?>" >
                     <option></option>
                       <?=Tally($tally,1)?>
                     </select>
               </div>
              </div>

						<button type="submit"  class="btn btn-info"><i class="icon-search"></i> <?=$LG['search'];//搜索?></button>
 
                        
<div style="margin-top:10px;">
        <div class="form-group">
          <div class="col-md-0">
            <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
              <input type="text" class="form-control input-small" name="stime_add" value="<?=$stime_add?>" placeholder="<?=$LG['yundan.Xcall_basic_show_21']?>">
              <span class="input-group-addon">-</span>
              <input type="text" class="form-control input-small" name="etime_add" value="<?=$etime_add?>"  placeholder="<?=$LG['yundan.Xcall_basic_show_21']?>">
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-md-0">
            <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
              <input type="text" class="form-control input-small" name="stime_chuku" value="<?=$stime_chuku?>" placeholder="<?=$LG['yundan.Xcall_basic_show_22']?>">
              <span class="input-group-addon">-</span>
              <input type="text" class="form-control input-small" name="etime_chuku" value="<?=$etime_chuku?>"  placeholder="<?=$LG['yundan.Xcall_basic_show_22']?>">
            </div>

          </div>
        </div>
      </div>                        
					</form>
				</div>
			</div>
			<form action="save.php" method="post" name="XingAoForm">
				<input name="lx" type="hidden">
				<input name="addclass" type="hidden">
				<!---->
				<table class="table table-striped table-bordered table-hover" style="border:0px solid #ddd;">
					<thead>
						<tr>
							<th align="center" class="table-checkbox">
								<input type="checkbox"  id="aAll" onClick="chkAll(this);id_save();get_total_price();"  title="<?=$LG['checkAll'];//全选/取消?>"/>
							</th>

							<th align="center"><a href="?<?=$search?>&orderby=ydh&orderlx=" class="<?=orac('ydh')?>"><?=$LG['yundan.list_6'];//运单号?></a></th>
							<th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status'];//状态?></a></th>
							
                            <?php if($ON_country){?>
							<th align="center"><a href="?<?=$search?>&orderby=country&orderlx=" class="<?=orac('country')?>"><?=$LG['yundan.country'];//寄往国家?></a></th>
                            <?php }?>
                            
                            <th align="center"><a href="?<?=$search?>&orderby=channel&orderlx=" class="<?=orac('channel')?>"><?=$LG['yundan.list_7'];//渠道?></a></th>
							<th align="center"><a href="?<?=$search?>&orderby=s_name&orderlx=" class="<?=orac('s_name')?>"><?=$LG['yundan.list_8'];//收件人?></a></th>
							<th align="center"><?=$LG['op'];//操作?></th>
						</tr>
					</thead>
					<tbody>
<?php
$tri=0;
while($rs=$sql->fetch_array())
{
	$tri+=1;
	$mr=FeData('member','groupid,CustomerService',"userid='{$rs['userid']}'");
	$groupid=$mr['groupid'];
	
	//是否勾选
	$checked=0; if(have($id_checked,$rs['ydid'])){$checked=1;}
?>
						<tr class="odd gradeX  <?=$checked?'active':''?> <?=spr($rs['status'])==30?'gray2':''?>" onclick="TestBlack('<?=$tri?>');">
						
							<td align="center" valign="middle">
							
								<?php
								$total_price=0;
								if(!$rs['pay']&&$rs['money']>0&&(spr($rs['status'])>1||$rs['addSource']==2||spr($rs['status'])==-2))
								{
									$total_price=spr($rs['money']-$rs['payment']); 
								}
								?>
								<input id="infoid_<?=$rs['ydid']?>" type="hidden" value="<?=$total_price?>"><!--JS要获取,不能没有-->
								<?php
								$total_price=0;
								if(!$rs['tax_pay']&&spr($rs['status'])>1)
								{
									$total_price=spr( spr($rs['tax_money'])-spr($rs['tax_payment'])); 
								}
								?>
								<input id="infoid_tax_<?=$rs['ydid']?>" type="hidden" value="<?=$total_price?>"><!--JS要获取,不能没有-->
								<input name="ydid[]" type="checkbox" id="a" onClick="chkColor(this);id_save();get_total_price();"  value="<?=$rs['ydid']?>" <?=$checked?'checked':''?> />
	<br><font class=" tooltips gray2" data-container="body" data-placement="top" data-original-title="<?=$LG['yundan.list_27'];//运单ID?>:<?=$rs['ydid']?> <?=$LG['yundan.list_28'];//本页排序号?>:<?=$tri?>"><?=$tri?></font>							
							</th>

							<td align="center" valign="middle"><a href="show.php?ydid=<?=$rs['ydid']?>" target="_blank"><?=cadd($rs['ydh'])?></a>

<?php if($rs['classid']||$rs['lotno']){?><br>
<font class="gray2 popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['yundan.list_29'];//航班/船运/托盘?>:<?=classify($rs['classid'])?>"><?=$LG['yundan.list_30'];//批号?>:<?=cadd($rs['lotno'])?></font>
<?php }?>

</td>
							<td align="center" valign="middle">
							<a href="/yundan/status.php?ydh=<?=$rs['ydh']?>" target="_blank"><?=status_name(spr($rs['status']),$rs['statustime'],$rs['statusauto'])?>
							<?php if(cadd($rs['gnkdydh'])){?><br>
							<font class="gray2"><?=cadd($expresses[$rs['gnkd']])?>：<?=cadd($rs['gnkdydh'])?></font><?php }?>
                            </a>
							</td>
                            
                            <?php if($ON_country){?>
							<td align="center" valign="middle"><?=yundan_Country(spr($rs['country']))?></td>
                            <?php }?>
                            
							<td align="center" valign="middle"><?=channel_name($groupid,$rs['warehouse'],$rs['country'],$rs['channel'])?></td>
							<td align="center" valign="middle"><?=cadd($rs['s_name'])?>
							<br>
							<font class="gray2"><?=cadd($rs['s_mobile_code'])?>-<?=cadd($rs['s_mobile'])?></font></td>
							
	
    						<td align="center" valign="middle">
<?php 
if($mr['CustomerService']){
	$r=CustomerService($mr['CustomerService']);
?>
	<?php if($r[6]){?>
    <a href="<?=urldecode($r[6])?>" class="btn btn-success" target="_blank" style="color:#ffffff"><i class="icon-comment"></i> <?=$LG['consulting']?></a>
    <?php }?>    
<?php }?>    
                       
                            
<li class="dropdown" style="list-style: none;display:inline;">
    <button type="button" class="btn btn-default dropdown-toggle"  data-hover="dropdown"  data-close-others="true"><?=$LG['op'];//操作?> </button>
    <ul class="dropdown-menu" style="text-align: center; top:-15px; left:-280px;width:350px;">


                            
	<?php 
	if( 
		( 
			!$rs['pay']&&$rs['money']>0 && 
			(spr($rs['status'])>1||$rs['addSource']==2&&spr($rs['status'])==-1)
		)
		|| 
		(spr($rs['status'])==-2)
	)
	{
	?>
          <a href="../payment/?fromtable=yundan&payid=<?=$rs['ydid']?>&field=money" class="btn btn-xs btn-warning showdiv"  target="XingAobox"><i class="icon-money"></i> <?=$LG['yundan.list_9'];//支付运费?></a>
    <?php }?>
    
    <?php if(!$rs['tax_pay']&&spr($rs['status'])>1&&spr($rs['tax_money'])>0){?>
          <a href="../payment/?fromtable=yundan&payid=<?=$rs['ydid']?>&field=tax_money" class="btn btn-xs btn-warning showdiv"  target="XingAobox"><i class="icon-money"></i> <?=$LG['yundan.list_10'];//支付税费?></a>
    <?php }?>
    
    <?php if(spr($rs['status'])>=20&&spr($rs['status'])!=30){?>
          <a href="save.php?lx=attr&status=30&ydid=<?=$rs['ydid']?>" class="btn btn-xs btn-info" onClick="return confirm('{$LG['yundan.list_12']}');"><i class="icon-foursquare"></i> <?=$LG['yundan.list_11'];//确认签收?></a>
    <?php }?>

      <?php if(spr($rs['status'])<=1){?>
         <a href="form.php?lx=edit&ydid=<?=$rs['ydid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> <?=$LG['edit']?></a> 
       <?php }?>
      
    <?php
    $signday=channelPar($rs['warehouse'],$rs['channel'],'signday');
    if($signday>0&&$rs['chukutime']&&$rs['chukutime']<=strtotime('-'.$signday.' days')&&spr($rs['status'])>4&&spr($rs['status'])<30){?>
          <a href="save.php?lx=sign&ydid=<?=$rs['ydid']?>" class="btn btn-xs btn-info"   onClick="return confirm('<?=LGtag($LG['yundan.list_31'],'<tag1>=='.cadd($rs['ydh']))?>');"><i class="icon-edit"></i> <?=$LG['yundan.list_13'];//已签收?></a>
    <?php }?>
           
           
      <?php if(spr($rs['status'])<=1||(!$off_delbak&&spr($rs['status'])==30)){?>
          <a href="save.php?lx=del&ydid=<?=$rs['ydid']?>" class="btn btn-xs btn-danger" onClick="return confirm('<?=$LG['pptDelConfirm']?>');"><i class="icon-remove"></i> <?=$LG['del']?></a>
      <?php }?>
                            
                            
                            

 </ul>
</li>
                            
                            
            
            
            
            
				
							</td>
						</tr>
						
						<tr id="trshow<?=$tri?>" style="display:<?=$tri>1?'none':''?>">
							<td colspan="10" align="left">
		<?php  
		//基本资料
		$callFrom='member';//member 会员中心
		$call_payment=1;//费用及付款情况
		$call_basic=1;//基本资料
		$call_op=1;//操作要求
		$call_baoguo=1;//包裹
		$call_goodsdescribe=1;//货物
		$call_content=1;//备注
		$call_reply=1;//回复
		$callFrom_show=0;//显示全部文字内容
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/basic_show.php');
		?>	
		</td>
		</tr>
						
		<!--分隔-开始-->
		<!--<tr>
			<td colspan="10" style="border-left: 0px none #ffffff;	border-right: 0px none #ffffff; background-color:#ffffff; height:30px;"></td>
		</tr>
		<tr></tr>-->
		<!--分隔-结束-->
		
<?php
}
?>
					</tbody>
				</table>
				<!---->			

     
            
<!--底部操作按钮固定--> 
<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">



<font class="gray">【<?=$LG['selected']?><span id="IDNumber" class="red">0</span>】</font>

<!--************支付按钮************-->	
<?php if($status==-1||$status=='-2'||$status==3){?>
    <a class="btn btn-warning showdiv" target="XingAobox" href="../payment/?fromtable=yundan&field=money">
    <i class="icon-signin"></i> <?=$LG['yundan.list_9'];//支付运费?> <span title="<?php if ($off_integral){ echo $LG['yundan.list_33']; } ?>">(<?=$LG['yundan.list_32']?><font id="msg_total_payment">0</font><?=$XAmc?>)</span>
    </a>
<?php }else{?>
    <font id="msg_total_payment" style="display:none"></font>
<?php }?>


<?php if($status=='chuku'&&$status_on_14){?>
    <a class="btn btn-warning showdiv" target="XingAobox" href="../payment/?fromtable=yundan&field=tax_money">
    <i class="icon-signin"></i> <?=$LG['yundan.list_15'];//支付税费 (共约需付?><font id="msg_total_payment_tax">0</font><?=$XAmc?>)
    </a>
<?php }else{?>
	<font id="msg_total_payment_tax" style="display:none"></font>
<?php }?>


<!--************打印和其他************-->
<?php if($status=='chuku'){?>
	<input type="hidden" name="status" value=''>
	<button type="submit"  class="btn btn-grey" 
	onClick="
	document.XingAoForm.action='save.php';
	document.XingAoForm.lx.value='attr';
	document.XingAoForm.status.value='30';
	return confirm('{$LG['yundan.list_12']}');
	"><i class="icon-signin"></i> <?=$LG['yundan.list_11'];//确认签收?></button>
<?php }?>

<?php if($member_per[$Mgroupid]['off_print']&&$status!='chuku'&&$status!=30){?>
	<select class="form-control select2me input-msmall" data-placeholder="<?=$LG['yundan.list_16'];//打印模板?>" name="print_tem">
	<option></option>
	<?php yundan_print('',1,'',1)?>
	</select>
	<input name="print_dh" type="checkbox" value="1"><font class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['yundan.list_17'];//用第三方转运单号生成条码?>"><?=$LG['yundan.list_34'];//第三方单号?></font>
	
	<button type="submit"  class="btn btn-grey" style="margin-right:20px;"
	onClick="
	document.XingAoForm.target='_blank';
	document.XingAoForm.action='print.php';
	" title="<?=$LG['yundan.list_18'];//打开后按ctrl+p打印?>"><i class="icon-signin"></i> <?=$LG['yundan.list_19'];//打印所选?></button>
<?php }?>
	

	
<!--************导出************-->
	<select class="form-control select2me input-small" data-placeholder="<?=$LG['daigou.66'];//报表类型?>" name="ex_tem">
	<option></option>
	<?php yundan_excel_export('',1,1)?>
	</select>
	
    <font  class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['yundan.2'];//不勾选时则导出所选?>">
	<input name="use_where" type="checkbox" value="1"><?=$LG['yundan.1'];//导出搜索结果?>
    </font>

	 
	<button type="submit"  class="btn btn-grey" style="margin-right:20px;"
	onClick="
	document.XingAoForm.target='_blank';
	document.XingAoForm.action='excelExport/';
	"><i class="icon-signin"></i> <?=$LG['excelExport'];//导出?></button>



				
<!--************未入库时和记录的按钮************-->	
<?php if(($status<=1||(!$off_delbak&&$status==30))&&$status!='chuku'){ //必须&&$status!='chuku'否则默认会认为是0也会显示?>
<!--btn-danger--><button type="submit"  class="btn btn-grey"
onClick="
document.XingAoForm.action='save.php';
document.XingAoForm.lx.value='del';
return confirm('<?=$LG['pptDelConfirm'];//确认要删除所选吗?此操作不可恢复!?>\r<?=$LG['yundan.list_20']?>');
"><i class="icon-signin"></i> <?=$LG['delSelect'];//删除所选?></button>
<?php }?>



				</div>
				<div class="row">
					<?=$listpage?>
				</div>
			</form>
		</div>
		<!--表格内容结束--> 
		
		  
          <!--提示内容必须放这个位置并且要很长,否则最后一个包裹的操作菜单显示不全-->
		  <div class="xats"> 
          <br>

        <strong> <?=$LG['pptInfo']?></strong><br />
        
          <?php if($ON_yundan_prepay){?>
          &raquo; <font class="red"><?=$LG['yundan.save_20'];//所有分包都需要先按预估重量支付费用后才能为您处理,到时打包称重后再多还少补！?> </font><br>
          <?php }?>
          
          &raquo; <?=$LG['yundan.list_21'];//已审核的运单不可再修改或删除(在未审核前请尽快检查)?> <br>
		  <?php if ($off_integral){?>
		  &raquo; <?=$LG['yundan.list_22'];//税费不能用积分抵消，并且不送积分?><br>	
		  <?php }?>	  
          
		  <?php if ($member_per[$Mgroupid]['off_print']){?>
		  &raquo; <?=$LG['yundan.list_18']?> (<a href="/doc/Print.doc" target="_blank"><?=$LG['yundan.list_23'];//打印设置说明?></a>)<br>	
		  <?php }?>	 
           
		  &raquo; <?=$LG['yundan.list_24'];//只能删除待审、无效(未通过审核)、完成的运单(删除完成的运单也会同时删除相关包裹)?><br>		  
		  &raquo; <?=$LG['yundan.list_25'];//如果未看到某个运单，请在“全部运单”分类中查看！?><br>		
	</div>
	</div>
</div>

<script language="javascript">
function get_total_price()
{ 
	//获取多选的值
	var eless = document.getElementsByName("ydid[]");//必须用Name
	var total_price=0;
	var infoidFloat=0;
	var infoidFloat_tax=0;
	var price=0;
	var price_tax=0;
	   for(var i=0;i<eless.length;i++)
	  {
		 if(eless[i].checked)
		 {
			   total_price=parseInt(eless[i].value);
			   if(total_price>0)
			   { 
				   //计算运费
				   price=document.getElementById("infoid_"+total_price).value;
				   infoidFloat=infoidFloat+parseFloat(price);
				
				   //计算税费
				   price_tax=document.getElementById("infoid_tax_"+total_price).value;
				   infoidFloat_tax=infoidFloat_tax+parseFloat(price_tax); 
				   
			   }
		 }
	  }
	
	document.getElementById("msg_total_payment").innerHTML =decimalNumber(infoidFloat,2);
	document.getElementById("msg_total_payment_tax").innerHTML =decimalNumber(infoidFloat_tax,2);
 }
</script>
 
 


<script src="/xingao/yundan/call/update.php"></script>


<?php
$sql->free(); //释放资源
$showbox=1;//是否用到 操作弹窗 (/public/showbox.php)
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
$id_save=1;//是否用到id_save()

$CountryRequired=0;//yundanJS.php 参数:国家是否必选
require_once($_SERVER['DOCUMENT_ROOT'].'/js/yundanJS.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
<script language="javascript">
//显示渠道下拉
function channel_show() 
{
	var warehouse=document.getElementsByName("warehouse")[0].value;
	var country=document.getElementsByName("country")[0].value;
	var xmlhttp_channel=createAjax(); 
	if (xmlhttp_channel) 
	{  
		xmlhttp_channel.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp_channel.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp_channel.send('lx=channel&callFrom=member&channel=<?=$channel?>&warehouse='+warehouse+'&country='+country+'');

		xmlhttp_channel.onreadystatechange=function() 
		{  
			if (xmlhttp_channel.readyState==4 && xmlhttp_channel.status==200) 
			{ 
				document.getElementById('channel').innerHTML='<select  class="form-control input-medium select2me" data-placeholder="<?=$LG['yundan.list_7'];//渠道?>" name="channel">'+unescape(xmlhttp_channel.responseText)+'</select>'; 
			}
		}
	}
}



//单独分开,要放在foot.php后面
$(function(){  
	country_show('<?=$Mgroupid?>','<?=$country?>');  //显示国家下拉
});

$(function(){       
	 get_total_price();//算费用
});

$(function(){       
	 channel_show();//渠道输出
});
</script>
