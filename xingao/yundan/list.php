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


require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="运单管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//处理:1125

$classtag='so';//标记:同个页面,同个$classtype时,要有不同标记
$classtype=3;//分类类型

$where="1=1";
$status=par($_GET['status']);
//取出保存的ID
$id_name='Xydid';
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
		case 'all':
			$where.="";
		break;
		
		case 'calc_fee':
			$where.=" and money=0 and pay=0 and status>1 and status<5";
		break;
		
		case 'chuku':
			$where.=" and status>4 and status<30";
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
	$tally=par($_GET['tally']);
	$other=par($_GET['other']);
	$ydh=$_GET['ydh'];//文本域处理不能加par

	$addSource=par($_GET['addSource']);
	$stime_add=par($_GET['stime_add']);
	$etime_add=par($_GET['etime_add']);
	$stime_chuku=par($_GET['stime_chuku']);
	$etime_chuku=par($_GET['etime_chuku']);
	     
	$classid=GetEndArr($_GET['classid'.$classtag.$classtype]);
	if(!CheckEmpty($classid)){$classid=par($_GET['classid']);}
	

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

		$where.=" and (ydid='".CheckNumber($key,-0.1)."' or lotno='{$key}' or whPlace='{$key}' or hscode like '%{$key}%' or ydh like '%{$key}%' or gwkdydh like '%{$key}%' or gnkdydh like '%{$key}%' or dsfydh like '%{$key}%' or s_name like '%{$key}%' or s_mobile like '%{$key}%' or userid='".CheckNumber($key,-0.1)."' or username like '%{$key}%'  {$where_table}  )";
	}
	
	
	if(CheckEmpty($ydh)){$where.=" and ydh in ('".ToArr($ydh,1,1,"','")."')";}	
	if(CheckEmpty($warehouse)){$where.=" and warehouse='{$warehouse}'";}
	if(CheckEmpty($country)){$where.=" and country='{$country}'";}
	if(CheckEmpty($channel)){$where.=" and channel='{$channel}'";}
	if(CheckEmpty($addSource)){$where.=" and addSource='{$addSource}'";}
	if($stime_add){$where.=" and addtime>='".strtotime($stime_add." 00:00:00")."'";}
	if($etime_add){$where.=" and addtime<='".strtotime($etime_add." 23:59:59")."'";}
	if($stime_chuku){$where.=" and chukutime>='".strtotime($stime_chuku." 00:00:00")."'";}
	if($etime_chuku){$where.=" and chukutime<='".strtotime($etime_chuku." 23:59:59")."'";}
	if(CheckEmpty($tally)){$where.=" and tally='{$tally}'";}
	if(CheckEmpty($other))
	{
		if($other=='gd_0'){$where.=" and (goodsdata is null or goodsdata='')";}
		elseif($other=='gd_1'){$where.=" and (goodsdata is not null and goodsdata<>'')";}
		elseif($other=='lotno_0'){$where.=" and lotno=''";}
		elseif($other=='lotno_1'){$where.=" and lotno<>''";}
		elseif($other=='cert_0'){$where.=" and (s_shenfenhaoma='' or s_shenfenimg_z='' or s_shenfenimg_b='')";}
		elseif($other=='cert_1'){$where.=" and s_shenfenhaoma<>'' and s_shenfenimg_z<>'' and s_shenfenimg_b<>''";}
		elseif($other=='pay_0'){$where.=" and pay='0'";}
		elseif($other=='memberpay_0'){$where.=" and memberpay='0'";}
		elseif($other=='memberpay_1'){$where.=" and memberpay='1'";}
		elseif($other=='record_1'){$where.=" and ydid in (select fromid from wupin where fromtable='yundan' and record='1' and gdid>0) ";}
		
	}
	if(CheckEmpty($classid))
	{
		$classid_all=$classid.SmallClassID($classid,'classify');
		$where.=" and classid in ({$classid_all})";
	}
	
	
	//筛选菜单
	$field=par($_GET['field']);
	$zhi=par($_GET['zhi']);
	if(CheckEmpty($field)&&CheckEmpty($zhi)){$where.=" and {$field}='{$zhi}' ";}

	$search.="&so={$so}&key={$key}&warehouse={$warehouse}&country={$country}&channel={$channel}&addSource={$addSource}&tally={$tally}&other={$other}&stime_add={$stime_add}&etime_add={$etime_add}&stime_chuku={$stime_chuku}&etime_chuku={$etime_chuku}&classid={$classid}&ydh={$ydh}";
}

$order=' order by ydh desc,status asc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$query="select * from yundan where {$where}  ".whereCS()."  {$Xwh}  {$order}";

//分页处理
$line=30;$page_line=10;//不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');


?>

<div class="page_ny"> 
	<!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
			<h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
				<?=$headtitle?>
				</a> </h3>
	<ul class="page-breadcrumb breadcrumb">
	<?php 
		  $callFrom='manage';
		  require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/options_menu.php'); 
	  ?>
	  <button type="button" class="btn btn-default" onClick="location.href='/public/idSave.php?lx=sc&id_name=<?=$id_name?>';"><i class="icon-trash"></i> 清空所有勾选 </button>
      
 	  <button type="button" class="btn btn-default" onClick="AllTrOpen();" id="AllTrBlack"><i class="icon-resize-full" id="AllTrBlackIco"></i> <font id="AllTrBlackName"><?=$LG['allOpen']//全部展开?></font>  </button>
     
	  
		
		</ul>
			<!-- END PAGE TITLE & BREADCRUMB--> 
		</div>
	</div>
	<!-- END PAGE HEADER--> 
	
	<!-- BEGIN PAGE CONTENT-->
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/nav_num.php');?> 
	<div class="tabbable tabbable-custom boxless">
		<ul class="nav nav-tabs">
			
			<li class="<?php if($status=='all'){echo 'active';$ydnum_status_all='<span class="badge badge-default">'.$num.'</span>';}?>" style="margin-right:30px;"><a href="?status=all">全部<?=$ydnum_status_all?></a></li>
			<li class="<?php if($status=='-1'){echo 'active';$ydnum_status_01='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=-1"><?=$status_01?><?=$ydnum_status_01?></a></li>
			
			<?php if($ON_yundan_prepay){?>
			<li class="<?php if($status=='-2'){echo 'active';$ydnum_status_02='<span class="badge badge-default">'.$num.'</span>';}?> popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['yundan.27']//同个包裹只要有一个分包未支付,该包裹全部分包都会留在此处,全部支付后仓库才处理?>"><a href="?status=-2"><?=$status_02?><?=$ydnum_status_02?></a></li>
            <?php }?>
            
			<li class="<?php if($status=='0'){echo 'active';$ydnum_status_0='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=0"><?=$status_0?><?=$ydnum_status_0?></a></li>
			
			<li class="<?php if($status=='1'){echo 'active';$ydnum_status_1='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=1"><?=$status_1?><?=$ydnum_status_1?></a></li>
			
			<li class="<?php if($status=='2'){echo 'active';$ydnum_status_2='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=2"><?=$status_2?><?=$ydnum_status_2?></a></li>
			
			<li class="<?php if($status=='calc_fee'){echo 'active';$ydnum_status_calc_fee='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=calc_fee">待计费<?=$ydnum_status_calc_fee?></a></li>
			
			<li class="<?php if($status=='3'){echo 'active';$ydnum_status_3='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=3"><?=$status_3?><?=$ydnum_status_3?></a></li>
			
			<li class="<?php if($status=='4'){echo 'active';$ydnum_status_4='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=4"><?=$status_4?><?=$ydnum_status_4?></a></li>
			
			<li class="<?php if($status=='chuku'){echo 'active';$ydnum_status_chuku='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=chuku">已出库<?=$ydnum_status_chuku?></a></li>
				
			<li class="<?php if($status=='30'){echo 'active';$ydnum_status_30='<span class="badge badge-default">'.$num.'</span>';}else{$ydnum_status_30=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi='30',$CN_where="{$Xwh} ".whereCS(),$CN_userid='',$CN_color='default');}?>"><a href="?status=30"><?=$status_30?><?=$ydnum_status_30?></a></li>
			
		</ul>
		<div class="tab-content" style="padding:10px;"> 
			<!--搜索-->
            
            
<div class="navbar navbar-default" role="navigation">
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <form class="navbar-form navbar-left"  method="get" action="?">
      <input name="so" type="hidden" value="1">
      <input name="status" type="hidden" value="<?=$status?>">
      
 <table>
  <tbody>
    <tr>
      <td width="200">
        <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="运单号/第三方单号/寄库快递单号/派送快递单号/包裹单号/代购商品单号/仓位/HS/HG/航班船名/航次/批次号/收件姓名/收件手机/运单ID/会员ID/会员名"  placeholder="各类关键词" value="<?=$key?>">
        
        <textarea  class="form-control input-msmall" rows="3" name="ydh" placeholder="批量搜索:一行一个单号" style="margin-top:10px;"><?=$ydh?></textarea>
      
      </td>
      <td width="100%">
      


      <div class="form-group">
        <select  class="form-control input-msmall select2me" name="warehouse" data-placeholder="仓库" onChange="channel_show();">
          <option></option>
          <?=warehouse($warehouse,1)?>
        </select>
      </div>

      <?php if($ON_country){?>
      <div class="form-group">
        <select  class="form-control input-msmall select2me" name="country" data-placeholder="国家">
          <option></option>
          <?=Country($country,2)?>
        </select>
      </div>

      <?php }?>
      <div class="form-group">
        <span id='channel'></span>
      </div>

      <div class="form-group">
        <select  class="form-control input-small select2me" name="addSource" data-placeholder="来源">
          <option></option>
          <?=yundan_addSource($addSource,1)?>
        </select>
      </div>

      <div class="form-group">
        <select  class="form-control input-small select2me" name="tally" data-placeholder="月结" >
          <option></option>
          <?=Tally($tally,1)?>
        </select>
      </div>

      <div class="form-group">
        <select  class="form-control input-msmall select2me" name="other" data-placeholder="其他" >
          <option></option>
          <option value="lotno_0"  <?=$other=='lotno_0'?' selected':''?>>无批次号</option>
          <option value="lotno_1"  <?=$other=='lotno_1'?' selected':''?>>有批次号</option>
          <option value="cert_0"  <?=$other=='cert_0'?' selected':''?>>无证件</option>
          <option value="cert_1"  <?=$other=='cert_1'?' selected':''?>>有证件</option>
          <?php if($ON_gd_japan){?>
          <option value="gd_0"  <?=$other=='gd_0'?' selected':''?>>未扫描商品</option>
          <option value="gd_1"  <?=$other=='gd_1'?' selected':''?>>已扫描商品</option>
          <?php }?>
          <option value="pay_0"  <?=$other=='pay_0'?' selected':''?>>未支付</option>
          <option value="memberpay_0"  <?=$other=='memberpay_0'?' selected':''?>>最后由后台扣费</option>
          <option value="memberpay_1"  <?=$other=='memberpay_1'?' selected':''?>>最后由会员支付</option>
          
          <option value="record_1"  <?=$other=='record_1'?' selected':''?>>未备案商品</option>
       </select>
      </div>


      <div style="margin-top:10px;">

        <div class="form-group">
          <div class="col-md-0">
            <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
              <input type="text" class="form-control input-small" name="stime_add" value="<?=$stime_add?>" placeholder="下单时间">
              <span class="input-group-addon">-</span>
              <input type="text" class="form-control input-small" name="etime_add" value="<?=$etime_add?>"  placeholder="下单时间">
            </div>

          </div>

        </div>

        <div class="form-group">
          <div class="col-md-0">
            <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
              <input type="text" class="form-control input-small" name="stime_chuku" value="<?=$stime_chuku?>" placeholder="出库时间">
              <span class="input-group-addon">-</span>
              <input type="text" class="form-control input-small" name="etime_chuku" value="<?=$etime_chuku?>"  placeholder="出库时间">
            </div>

          </div>
        </div>
      </div>


      <div style="margin-top:10px;">
        <div class="form-group">
          <div class="col-md-0"> 
<?php 
    //$classtag='so';//标记:同个页面,同个$classtype时,要有不同标记
    //$classtype=1;//分类类型
    //$classid=8;//已保存的ID
    require($_SERVER['DOCUMENT_ROOT'].'/public/classify.php');
?>

			</div>

        </div>
      </div>      
      
      </td>
      <td width="80" align="right">
<button type="submit" class="btn btn-default" style="height:120px; width:100%"><i class="icon-search"></i> <strong><?=$LG['search']//搜索?></strong></button>      
      </td>
    </tr>
  </tbody>
</table>
     



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
								<input type="checkbox"  id="aAll" onClick="chkAll(this);id_save();"  title="全选/取消"/>
							</th>

							<th align="center"><a href="?<?=$search?>&orderby=ydh&orderlx=" class="<?=orac('ydh')?>">运单号</a>/<a href="?<?=$search?>&orderby=lotno&orderlx=" class="<?=orac('lotno')?>">批次号</a></th>
							<th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status']//状态?></a></th>
                            
 							<th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">下单</a>/<a href="?<?=$search?>&orderby=printPickTime&orderlx=" class="<?=orac('printPickTime')?>" title="最后打印拣货单的时间">拣货</a>/<a href="?<?=$search?>&orderby=printPackTime&orderlx=" class="<?=orac('printPackTime')?>" title="最后打印打包单的时间">打包</a>/<a href="?<?=$search?>&orderby=printExpTime&orderlx=" class="<?=orac('printExpTime')?>" title="最后打印面单的时间">面单</a></th>
                           
                            <?php if($ON_country){?>
							<th align="center"><a href="?<?=$search?>&orderby=country&orderlx=" class="<?=orac('country')?>">寄往国家</a></th>
                            <?php }?>
                            
							<th align="center"><a href="?<?=$search?>&orderby=channel&orderlx=" class="<?=orac('channel')?>">渠道</a></th>
							<th align="center"><a href="?<?=$search?>&orderby=s_name&orderlx=" class="<?=orac('s_name')?>">收件人</a></th>
							 <th align="center">
							 <a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a> 
							 
							 (<a href="?<?=$search?>&orderby=special_userid&orderlx=" class="<?=orac('special_userid')?>" title="按会员ID最后位数排序">特排</a>)
							 </th>
							<th align="center">操作</th>
						</tr>
					</thead>
					<tbody>
<?php
$tri=0;
while($rs=$sql->fetch_array())
{
	$tri+=1;
	$mr=FeData('member','groupid,truename,enname',"userid='{$rs['userid']}'");
	$groupid=$mr['groupid'];
	
	//是否勾选
	$checked=0; if(have($id_checked,$rs['ydid'])){$checked=1;}
?>
						<tr class="odd gradeX <?=$checked?'active':''?>  <?=spr($rs['status'])==30?'gray2':''?>" onclick="TestBlack('<?=$tri?>');">
						
							<td align="center" valign="middle">
								<input name="ydid[]" type="checkbox" id="a" onClick="chkColor(this);id_save();" value="<?=$rs['ydid']?>"  <?=$checked?'checked':''?> /><br>
                                
                                <font class=" tooltips gray2" data-container="body" data-placement="top" data-original-title="运单ID:<?=$rs['ydid']?> 本页排序号:<?=$tri?>"><?=$tri?></font>
							</th>

							<td align="center" valign="middle">
<a href="show.php?ydid=<?=$rs['ydid']?>" target="_blank"><?=cadd($rs['ydh'])?></a>
<?php if($rs['dsfydh']){echo '<br><font class="gray2" title="第三方运单号">'.$rs['dsfydh'].'</font>';}?>

<?php if($rs['classid']||$rs['lotno']){?><br>
<font class="gray2 popovers" data-trigger="hover" data-placement="top"  data-content="航班/船运/托盘:<?=classify($rs['classid'])?>">批号:<?=cadd($rs['lotno'])?></font>
<?php }?>

</td>
							<td align="center" valign="middle">
                            
<a href="/yundan/status.php?ydh=<?=$rs['ydh']?>" target="_blank"><?=status_name(spr($rs['status']),$rs['statustime'],$rs['statusauto'])?></a>

<?php if(cadd($rs['gnkdydh'])){?><br><font class="gray2"><?=cadd($expresses[$rs['gnkd']])?>：<?=cadd($rs['gnkdydh'])?></font><?php }?>

							</td>
                            
							<td align="center" valign="middle">
                            
<font class="tooltips" data-container="body" data-placement="top" data-original-title="下单时间"><?=DateYmd($rs['addtime'],'m-d H:i')?></font>
<span class="xa_sep"> | </span>
<font class="tooltips gray2" data-container="body" data-placement="top" data-original-title="最后打印各类面单的时间"><?=DateYmd($rs['printExpTime'],'m-d H:i')?></font><br>

<font class="tooltips gray2" data-container="body" data-placement="top" data-original-title="最后打印拣货单的时间"><?=DateYmd($rs['printPickTime'],'m-d H:i')?></font>
<span class="xa_sep"> | </span>
<font class="tooltips gray2" data-container="body" data-placement="top" data-original-title="最后打印打包单的时间"><?=DateYmd($rs['printPackTime'],'m-d H:i')?></font>
						
							</td>
                            
                            <?php if($ON_country){?>
							<td align="center" valign="middle"><?=yundan_Country($rs['country'])?></td>
                            <?php }?>
                            
							<td align="center" valign="middle">
							<?=warehouse($rs['warehouse'])?><br>
							<?=channel_name($groupid,$rs['warehouse'],$rs['country'],$rs['channel'])?>
                            </td>
							<td align="center" valign="middle">
							<?php 
							//基本资料
							$zhi=cadd($rs['s_name']).'<br>
								'.cadd($rs['s_mobile_code']).'-'.cadd($rs['s_mobile']).'<br>
								'.cadd($rs['s_tel']).'<br>
								'.cadd($rs['s_zip']).'<br>';
								$zhi.=yundan_add_all($rs,'s');
							
							//身份证
							if($off_shenfenzheng&&channelPar($rs['warehouse'],$rs['channel'],'shenfenzheng'))
							{
								$zhi.='<br><br>证件号码：'.cadd($rs['s_shenfenhaoma']).'<br>';
								
								if($rs['s_shenfenimg_z']){
									$zhi.='<a href="'.cadd($rs['s_shenfenimg_z']).'" target="_blank"><img src="'.cadd($rs['s_shenfenimg_z']).'" width="250" height="150"/></a> ';
								}
								
								if($rs['s_shenfenimg_b']){
									$zhi.='<a href="'.cadd($rs['s_shenfenimg_b']).'" target="_blank"><img src="'.cadd($rs['s_shenfenimg_b']).'" width="250" height="150"/></a>';
								}
							}
							
								Modals($zhi,$title=cadd($rs['s_name']),$time='',$nameid='s_'.$rs['ydid'],$count=0,$html=1,$link_name=cadd($rs['s_name']));
							?>
							<br>
							<font class="gray2"><?=cadd($rs['s_mobile_code'])?>-<?=cadd($rs['s_mobile'])?></font>
							
							</td>
							<td align="center" valign="middle">
<!--显示会员账号--> 
<?=showUsername($rs['username'],$rs['userid'],$showUseric='',$mr)?>                           
<?php $r=CustomerService($rs['userid']);echo $r[0]."({$r[1]})";?>
							</td>

	
							<td align="center" valign="middle">
                            
  
<li class="dropdown" style="list-style: none;display:inline;">
    <button type="button" class="btn btn-default dropdown-toggle"  data-hover="dropdown"  data-close-others="true"><?=$LG['op'];//操作?> </button>
    <ul class="dropdown-menu" style="text-align: center; top:-15px; left:-280px;width:350px;">

	<?php if(permissions('yundan_fe','','manage',1)){?>	
        <a href="calc_fee.php?ydid=<?=$rs['ydid']?>" class="btn btn-xs btn-<?=spr($rs['money'])>0?'default':'warning';?>" target="_blank"><i class="icon-money"></i> 运费</a>
    <?php }?>	  
    
    <?php if(permissions('yundan_ta','','manage',1)&&$status_on_14){?>  
        <a href="calc_tax.php?ydid=<?=$rs['ydid']?>" class="btn btn-xs btn-<?=spr($rs['tax_money'])>0?'default':'warning';?>" target="_blank"><i class="icon-money"></i> 补税</a>
    <?php }?>
    
    <?php if(permissions('yundan_st','','manage',1)){?>
        <a href="scan.php?lx=pass&ydid=<?=$rs['ydid']?>" class="btn btn-xs btn-info" target="_blank"  title="修改状态"><i class="icon-map-marker"></i> 状态</a> 
    <?php }?>      
    
    
    <?php if(permissions('yundan_ed','','manage',1)){?>
    
        <a href="form.php?lx=edit&ydid=<?=$rs['ydid']?>" class="btn btn-xs btn-info" target="_blank"><i class="icon-edit"></i> <?=$LG['showedit']?></a> 
        
        <?php if(spr($rs['status'])<=1||(!$off_delbak&&spr($rs['status'])==30)){?>
        <a href="save.php?lx=del&ydid=<?=$rs['ydid']?>" class="btn btn-xs btn-danger" onClick="return confirm('确认要删除吗?此操作不可恢复!\r删除完成的运单也会删除相关包裹');"><i class="icon-remove"></i> <?=$LG['del']?></a>
        <?php }?>
    
    <?php }?>
    
    
    <br>
    
    <?php if($rs['op_overweight']==1){?>
        <a href="form.php?lx=add&copy=1&ydid=<?=$rs['ydid']?>" class="btn btn-xs btn-default tooltips" data-container="body" data-placement="top" data-original-title="<?=op_overweight('name')?>:<?=op_overweight($rs['op_overweight'])?>" target="_blank"><i class="icon-copy"></i> 分包(复制)</a> 
    <?php }?>
    
    <!--模板太长,不适合显示,停用-->
    <!--<?php if(permissions('yundan_pr','','manage',1)&&1==2){?>
        <div class="btn-group"> 
        <a class="btn btn-xs btn-default dropdown-toggle" href="#" data-toggle="dropdown"> 打印 <i class="icon-angle-down"></i> </a>
        <ul class="dropdown-menu" style="min-width:130px;">
        <?php yundan_print('',2,$rs['ydid'])?>
        </ul>
        </div>
    <?php }?>-->
    
    
    

 </ul>
</li>
                            

<br>                          
<?php memberMenu($rs['username'],$rs['userid'],'yundan',$rs['ydid'],$rs['ydh']);//会员账户操作菜单:不能放ajax里面,[联系]按钮会失效 ?>
            
            
            
							</td>
						</tr>
						
						<tr id="trshow<?=$tri?>" style="display:<?=$tri>1?'none':''?>">
							<td colspan="10" align="left">
		<?php  
		//基本资料
		$callFrom='manage';//member 会员中心
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
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<font class="gray">【已选<span id="IDNumber" class="red">0</span>个运单】</font>

<!--************打印************-->
<?php if(permissions('yundan_pr','','manage',1) ){?>
	<select class="form-control select2me input-msmall" data-placeholder="打印模板" name="print_tem">
	<option></option>
	<?php yundan_print('',1)?>
	</select>
    
	<font class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="用第三方转运单号生成条码"><input name="print_dh" type="checkbox" value="1">第三方单号</font>
    
    <?php if($ON_cardInstead){?>
	<font class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="用代替证件人的姓名作为收件人姓名"><input name="print_op1" type="checkbox" value="1">代替证件</font>
    <?php }?>
	
	<button type="submit" class="btn btn-grey" style="margin-right:20px;"
	onClick="
	document.XingAoForm.target='_blank';
	document.XingAoForm.lx.value='pr';
	document.XingAoForm.action='print.php';
	" title="打开后按ctrl+p打印"><i class="icon-signin"></i> 打印所选</button>
<?php }?>

<!--************导出************-->
<?php if(permissions('yundan_ex','','manage',1) ){?>
	<select class="form-control select2me input-msmall" data-placeholder="报表类型" name="ex_tem">
	<option></option>
	<?php yundan_excel_export('',1)?>
	</select>
	<input type="hidden" name="call_lx" value="manage">
	 
	<button type="submit" class="btn btn-grey" style="margin-right:20px;"
	onClick="
	document.XingAoForm.target='_blank';
	document.XingAoForm.action='excelExport/';
	"><i class="icon-signin"></i> 导出所选</button>
<?php }?>
	

<!--************其他按钮************-->	
<?php if(permissions('yundan_ad','','manage',1) ){?>
	<input type="text"  class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="分包数量" name="copy_number" value="1">
	<button type="submit" class="btn btn-grey tooltips" data-container="body" data-placement="top" data-original-title="复制所选的运单" 
	onClick="
	document.XingAoForm.target='';
	document.XingAoForm.lx.value='copy';
	document.XingAoForm.action='save.php';
	return confirm('确认要对 '+document.getElementById('IDNumber').innerHTML+' 个运单进行分包吗? \r每个运单分 '+document.getElementsByName('copy_number')[0].value+' 个分包\r分包的运单号后面会自动加字母以便识别');
	"><i class="icon-signin"></i> 分包 </button>
<?php }?>


<?php if(permissions('yundan_st','','manage',1) ){?>
    <input type="hidden" name="forList" value="1">
	<button type="submit" class="btn btn-grey"
	onClick="
	document.XingAoForm.target='_blank';
	document.XingAoForm.lx.value='';
	document.XingAoForm.action='batch.php';
	"><i class="icon-signin"></i> 修改</button>

<!--	return confirm('确认要对 '+document.getElementById('IDNumber').innerHTML+' 个运单进行修改吗? ');
-->
<?php }?>	

<!--************删除************-->	
<?php 
if(($status<=1||(!$off_delbak&&$status==30))&&$status!='chuku'&&$status!='calc_fee'){ //必须&&$status!='chuku'否则默认会认为是0也会显示?>
<!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
document.XingAoForm.target='';
document.XingAoForm.lx.value='del';
document.XingAoForm.action='save.php';
return confirm('确认要删除所选吗?此操作不可恢复!\r删除完成的运单也会删除相关包裹');
"><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>
<?php }?>



				</div>
				<div class="row">
					<?=$listpage?>
				</div>
			</form>
		</div>
		<!--表格内容结束--> 
		
		  
	<div class="xats" style="min-height:200px"> <!--设置最小高度,否则下拉菜单显示不全-->
          <br>

        <strong> 提示：</strong><br />
		  <?php if(permissions('yundan_pr','','manage',1) ){?>
		  &raquo; 打印:打开打印页面后按CTRL+P (<a href="/doc/Print.doc" target="_blank">打印设置说明</a>)<br>	
		  <?php }?>	 
		   
		  &raquo; 只能删除待入库、待审、无效(未通过审核)、完成的运单，如要删除正常运单请先修改状态为其中一项！<font class="red2">(只要运单不是【<?=$status_30?>】,删除后会自动退回已扣款)</font><br>		  
		  &raquo; 如果未看到某个运单，请在“全部运单”分类中查看！<br>		  
		  &raquo; 操作流程建议：进入“修改”审核运单→打印“打包清单”→打包并贴上“打包清单”纸片→进入“扫描称重”→扫描之前贴上纸片的运单号→放上电子称称重及计费→扣费或等待会员支付→打印面单贴上包裹→扫描出库（如果是直邮也可以批量导入快递单号批量出库）<br>		  
	</div>
    
    
	</div>
</div>





<script src="/xingao/yundan/call/update.php"></script>
<?php
$sql->free(); //释放资源
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
$id_save=1;//是否用到id_save()
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
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
		xmlhttp_channel.send('lx=channel&callFrom=manage&channel=<?=$channel?>&warehouse='+warehouse+'&country='+country+'');

		xmlhttp_channel.onreadystatechange=function() 
		{  
			if (xmlhttp_channel.readyState==4 && xmlhttp_channel.status==200) 
			{ 
				document.getElementById('channel').innerHTML='<select  class="form-control input-msmall select2me" data-placeholder="渠道" name="channel">'+unescape(xmlhttp_channel.responseText)+'</select>'; 
			}
		}
	}
}


$(function(){       
	 channel_show();//渠道输出
});
</script>
