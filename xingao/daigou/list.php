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
$pervar='daigou_ed,daigou_se,daigou_cg,daigou_th,daigou_zg,daigou_hh,daigou_ch,daigou_ck,daigou_ex,daigou_ot';
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="代购管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


$callFrom='manage';//member 会员中心

$where="1=1";
$status=par($_GET['status']);
//取出保存的ID
$id_name='Xdgid';
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

		case 'memberStatus':
			$where.=" and dgid in (select dgid from daigou_goods where memberStatus<>'0')";
		break;
		
		case 'memberContentNew':
			$where.=" and status not in (10)  and memberContentNew='1'";
		break;
		
		case 'storage':
			$where.=" and status in (3,5,6,7)";
		break;
	
		case 'inStorage':
			$where.=" and status in (8,9)";
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
	$addSource=par($_GET['addSource']);
	$source=par($_GET['source']);
	$types=par($_GET['types']);
	$brand=par($_GET['brand']);
	$timeoutBuy=par($_GET['timeoutBuy']);
	
	$stime_add=par($_GET['stime_add']);
	$etime_add=par($_GET['etime_add']);
	$stime_payTime=par($_GET['stime_payTime']);
	$etime_payTime=par($_GET['etime_payTime']);
	$stime_procurementTime=par($_GET['stime_procurementTime']);
	$etime_procurementTime=par($_GET['etime_procurementTime']);
	     
	

	if($key)
	{
		//联表查询:查商品表
		$where_gd=" or dgid in (select dgid from daigou_goods where godh like '%{$key}%')";  
		
		$where.=" and (dgid='".CheckNumber($key,-0.1)."' or whcod='{$key}' or dgdh like '%{$key}%' or name like '%{$key}%' or address like '%{$key}%' or userid='".CheckNumber($key,-0.1)."' or username like '%{$key}%'   {$where_gd} )";
	}
	
	if(CheckEmpty($warehouse)){$where.=" and warehouse='{$warehouse}'";}
	if(CheckEmpty($addSource)){$where.=" and addSource='{$addSource}'";}
	if(CheckEmpty($source)){$where.=" and source='{$source}'";}
	if(CheckEmpty($types)){$where.=" and types='{$types}'";}
	if(CheckEmpty($brand)){$where.=" and brand='{$brand}'";}
	
	if($stime_add){$where.=" and addtime>='".strtotime($stime_add." 00:00:00")."'";}
	if($etime_add){$where.=" and addtime<='".strtotime($etime_add." 23:59:59")."'";}
	if($stime_payTime){$where.=" and payTime>='".strtotime($stime_payTime." 00:00:00")."'";}
	if($etime_payTime){$where.=" and payTime<='".strtotime($etime_payTime." 23:59:59")."'";}
	if($stime_procurementTime){$where.=" and procurementTime>='".strtotime($stime_procurementTime." 00:00:00")."'";}
	if($etime_procurementTime){$where.=" and procurementTime<='".strtotime($etime_procurementTime." 23:59:59")."'";}

	if(CheckEmpty($timeoutBuy))
	{
		switch($timeoutBuy)
		{
			case '0'://未超24时
				$where.=" and timeoutBuy='0'";
			break;

			case '1':
				$where.=" and timeoutBuy='1'";
			break;

			case '2':
				$where.=" and timeoutBuy='1' and status in (3,5)";
			break;

			case '3':
				$where.=" and timeoutBuy='1' and status not in (3,5)";
			break;
		}
	}

	
	//筛选菜单
	$field=par($_GET['field']);
	$zhi=par($_GET['zhi']);
	if(CheckEmpty($field)&&CheckEmpty($zhi))
	{
		if(have($field,'memberStatus,manageStatus,lackStatus'))
		{
			$where.=" and dgid in (select dgid from daigou_goods where {$field}='{$zhi}')";
		}else{
			$where.=" and {$field}='{$zhi}' ";
		}
	}

	$search.="&so={$so}&key={$key}&warehouse={$warehouse}&addSource={$addSource}&source={$source}&types={$types}&brand={$brand}&stime_add={$stime_add}&etime_add={$etime_add}&stime_payTime={$stime_payTime}&etime_payTime={$etime_payTime}&stime_procurementTime={$stime_procurementTime}&etime_procurementTime={$etime_procurementTime}&timeoutBuy={$timeoutBuy}";
}


$order=' order by left(dgdh,10) desc,right(dgdh,10) asc';//默认特排
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$query="select * from daigou where {$where} {$Xwh} {$order}";

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
		  //$callFrom='manage';
		  require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/options_menu.php'); 
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
			<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/nav_num.php');?> 
			
			<li class="<?php if($status=='all'){echo 'active';$dgnum_status_all='<span class="badge badge-default">'.$num.'</span>';}?>" style="margin-right:30px;"><a href="?status=all"><?=$LG['all']?><?=$dgnum_status_all?></a></li>
			
            <?php if($dg_checked){?>
			<li class="<?php if($status=='0'){echo 'active';$dgnum_status_0='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=0"><?=daigou_Status(0)?><?=$dgnum_status_0?></a></li>
            <?php }?>

			<li class="<?php if($status=='1'){echo 'active';$dgnum_status_1='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=1"><?=daigou_Status(1)?><?=$dgnum_status_1?></a></li>
			
			<li class="<?php if($status=='memberContentNew'){echo 'active';$dgnum_memberContentNew='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=memberContentNew">会员新留言<?=$dgnum_memberContentNew?></a></li>
            
			<li class="<?php if($status=='3'){echo 'active';$dgnum_status_3='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=3"><?=daigou_Status(3)?><?=$dgnum_status_3?></a></li>
            
			<li class="<?php if($status=='5'){echo 'active';$dgnum_status_5='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=5"><?=daigou_Status(5)?><?=$dgnum_status_5?></a></li>
            
            
			<li class="<?php if($status=='memberStatus'){echo 'active';$dgnum_status_memberStatus='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=memberStatus">待操作<?=$dgnum_status_memberStatus?></a></li>
            
			<li class="<?php if($status=='storage'){echo 'active';$dgnum_status_storage='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=storage">待入库<?=$dgnum_status_storage?></a></li>
			
			<li class="<?php if($status=='inStorage'){echo 'active';$dgnum_status_inStorage='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=inStorage"><?=$LG['daigou.177']?><?=$dgnum_status_inStorage?></a></li>
			
			<li class="<?php if($status=='10'){echo 'active';$dgnum_status_10='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=10"><?=daigou_Status(10)?><?=$dgnum_status_10?></a></li>
			
		</ul>
		<div class="tab-content" style="padding:10px;"> 
			<!--搜索-->
			<div class="navbar navbar-default" role="navigation">
				
				<div class="collapse navbar-collapse navbar-ex1-collapse">
<form class="navbar-form navbar-left"  method="get" action="?">
  <input name="so" type="hidden" value="1">
  <input name="status" type="hidden" value="<?=$status?>">
  <div class="form-group">
    <input type="text" name="key" class="form-control popovers" data-trigger="hover" data-placement="right"  data-content="代购单号/商品单号/品名/电商网址/专柜地址/入库包裹单号/代购ID/入库码/会员ID/会员名" placeholder="<?=$LG['yundan.list_2'];//各类关键词?>"  value="<?=$key?>">
  </div>
  
  <div class="form-group">
    <select  class="form-control input-medium select2me" name="warehouse" data-placeholder="<?=$LG['warehouse'];//仓库?>">
      <option></option>
      <?php warehouse($warehouse,1,1);?>
    </select>
  </div>
  
  <div class="form-group">
    <select class="form-control input-small select2me" name="addSource" data-placeholder="<?=$LG['yundan.list_4'];//来源?>">
      <option></option>
      <?=daigou_addSource($addSource,1)?>
    </select>
  </div>
  
  <div class="form-group">
    <select class="form-control input-small select2me" name="source" data-placeholder="货源">
      <option></option>
      <?php daigou_source($source,1)?>
    </select>
  </div>
  
  <div class="form-group">
    <select class="form-control input-small select2me" name="types" data-placeholder="品类">
      <option></option>
      <?php ClassifyAll(4,$types)?>
    </select>
  </div>
  
  <div class="form-group">
    <select class="form-control input-small select2me" name="brand" data-placeholder="品牌">
      <option></option>
      <?php ClassifyAll(6,$brand)?>
    </select>
  </div>
   
  <div class="form-group">
    <select class="form-control input-small select2me" name="timeoutBuy" data-placeholder="采购超时">
      <option></option>
       <option value="0" <?=$timeoutBuy==0?'selected':''?>>未超时</option>
       <option value="1" <?=$timeoutBuy==1?'selected':''?>>已超24小时(全部)</option>
       
       <option value="2" <?=$timeoutBuy==2?'selected':''?>>已超24小时(未采购)</option>
       <option value="3" <?=$timeoutBuy==3?'selected':''?>>已超24小时(已采购)</option>
    </select>
  </div>
 
  <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search'];//搜索?></button>
 
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
              <input type="text" class="form-control input-small" name="stime_payTime" value="<?=$stime_payTime?>" placeholder="首付时间">
              <span class="input-group-addon">-</span>
              <input type="text" class="form-control input-small" name="etime_payTime" value="<?=$etime_payTime?>"  placeholder="首付时间">
            </div>

          </div>
        </div>
 
        <div class="form-group">
          <div class="col-md-0">
            <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
              <input type="text" class="form-control input-small" name="stime_procurementTime" value="<?=$stime_procurementTime?>" placeholder="采购时间">
              <span class="input-group-addon">-</span>
              <input type="text" class="form-control input-small" name="etime_procurementTime" value="<?=$etime_procurementTime?>"  placeholder="采购时间">
            </div>

          </div>
        </div>
       
      </div>
        
</form>
				</div>
			</div>
			<form action="save.php" method="post" name="XingAoForm">
				<input name="typ" type="hidden">
				<input name="addclass" type="hidden">
				<!---->
      <table class="table table-striped table-bordered" style="border:0px solid #ddd;"><!-- table-hover-->
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> 
			  <input type="checkbox"  id="aAll" onClick="chkAll(this);id_save();"  title="<?=$LG['checkAll'];//全选/取消?>"/>
              </th>
              <th align="center">
              <a href="?<?=$search?>&orderby=&orderlx=" class="<?=orac('dgdh')?>">单号</a><!--用默认排序,因此orderby=空-->
              </th>
              <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status']//状态?></a></th>
              <th align="center">处理</th>
              <th align="center"><a href="?<?=$search?>&orderby=source&orderlx=" class="<?=orac('source')?>">货源</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=name&orderlx=" class="<?=orac('name')?>"><?=$LG['mall_order.form_23'];//品名?></a></th>

              <th align="center"><a href="?<?=$search?>&orderby=brand&orderlx=" class="<?=orac('brand')?>"><?=$LG['brand']//品牌?></a></th>
              
              <th align="center">
              <a href="?<?=$search?>&orderby=freightFee&orderlx=" class="<?=orac('freightFee')?>">寄库运费</a>
              
              </th>
              <th align="center"><a href="?<?=$search?>&orderby=goodsFee&orderlx=" class="<?=orac('goodsFee')?>">费用</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=warehouse&orderlx=" class="<?=orac('warehouse')?>">仓库</a></th>
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

	$totalFee=daigou_totalFee($rs);//此单全部费用:价格币
	$totalPay=daigou_totalPay($rs);//已支付:价格币
	$totalPayTo=daigou_totalPay($rs,1);//已支付:支付币
	$toCurrency=cadd($rs['toCurrency']);//支付币
	$pay_money=spr($totalFee-$totalPay);//要付的费用(总费用－已付费用)
	
	//是否勾选
	$checked=0; if(have($id_checked,$rs['dgid'])){$checked=1;}
?>






<tr class="odd gradeX <?=$checked?'active':''?> <?=spr($rs['status'])==10?'gray2':''?>" onclick="TestBlack('<?=$tri?>');">
  <td align="center" valign="middle">

      <input name="dgid[]" type="checkbox" id="a" onClick="chkColor(this);id_save()" value="<?=$rs['dgid']?>"   <?=$checked?'checked':''?> />

<br><font class=" tooltips gray2" data-container="body" data-placement="top" data-original-title="代购ID:<?=$rs['dgid']?> <?=$LG['yundan.list_28'];//本页排序号?>:<?=$tri?>"><?=$tri?></font>	
	
  </td>
  
  <td align="center" valign="middle">
  <a href="show.php?dgid=<?=$rs['dgid']?>" target="_blank"><?=cadd($rs['dgdh'])?></a>
</td>
  
  <td align="center" valign="middle" >
  
  	<a data-target="#ajaxLog<?=$rs['dgid']?>" data-toggle="modal" href="/public/opLogModal.php?fromtable=daigou&fromid=<?=$rs['dgid']?>&callFrom=<?=$callFrom?>">
      <font class=" tooltips" data-container="body" data-placement="top" data-original-title="更新时间:<?=DateYmd($rs['statusTime'],1)?>"><?=daigou_Status(spr($rs['status']),2)?></font><br>
	</a>
    
	<div class="modal fade" id="ajaxLog<?=$rs['dgid']?>" tabindex="-1" role="basic" aria-hidden="true">
		<img src="/images/ajax-modal-loading.gif" class="loading">
	</div>

  
  </td>
  
<td align="center" valign="middle">
<?php 
//操作状态
$memberStatus_show=daigou_memberStatus('',3,$rs); 
if($memberStatus_show){echo $memberStatus_show.'<br>';}
?>

<?=daigou_ContentNew('',2,$rs)//新留言?>

  </td>
  
  <td align="center" valign="middle" ><?=daigou_source($rs['source'])?></td>
  <td align="center" valign="middle">
  <font class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['name'])?>">
  <?php if( $rs['source']==1 ){?>
 	 <a href="<?=cadd($rs['address'])?>" target="_blank"><?=leng($rs['name'],30)?></a>
  <?php }else{?>
  	<?=leng($rs['name'],30)?>
  <?php }?>
  </font>
  
  <a href="javascript:void(0)" class="btn btn-xs btn-default xacopy" data-clipboard-text="<?=cadd($rs['name'])?>" onClick="xacopy();">复制</a>
 </td>

  <td align="center" valign="middle">
<?php 
		if($rs['brand']){echo '<font class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['brand'].'">'.(daigou_brand($rs['brand'])).'</font>';}
		
		if($rs['color']){echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['front.86'].'">'.($rs['color']==0&&$rs['colorOther']?cadd($rs['colorOther']):classify($rs['color'],2)).'</font>';}
		
		if($rs['size']){echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['front.85'].'">'.($rs['size']==0&&$rs['sizeOther']?cadd($rs['sizeOther']):classify($rs['size'],2)).'</font>';}
?>
  </td>

  <td align="center" valign="middle"> <?=spr($rs['freightFee']).cadd($rs['priceCurrency'])?>  </td>
  <td align="center" valign="middle"><?=daigou_showFee($rs)?></td>
  <td align="center" valign="middle"><?=warehouse($rs['warehouse'])?></td>

  
 
  <td align="center" valign="middle">
    <!--显示会员账号-->                            
    <?=showUsername($rs['username'],$rs['userid'],$showUseric='0',$mr)?>                           
  </td>


  <td align="center" valign="middle">
  
<li class="dropdown" style="list-style: none;display:inline;">
    <button type="button" class="btn btn-default dropdown-toggle"  data-hover="dropdown"  data-close-others="true"  onMouseOver="show_op_xingao('<?=$rs['dgid']?>');"><?=$LG['op'];//操作?> </button>
    <ul class="dropdown-menu" style="text-align: center; top:-15px; left:-280px;width:350px;">
    	
        <span id="show_menu<?=$rs['dgid']?>"></span>
	 
 </ul>
</li>

<br>                          
<?php memberMenu($rs['username'],$rs['userid'],'daigou',$rs['dgid'],$rs['dgdh']);//会员账户操作菜单:不能放ajax里面,[联系]按钮会失效 ?>

  </td>
</tr>		
				
		
        
		<tr id="trshow<?=$tri?>" target="iframe<?=$rs['dgid']?>"  url="op.php?dgid=<?=$rs['dgid']?>" style=" <?=$tri>1?'display:none':''?>">
        <!-- 
        target和 url 作用是展开后,在在框架打开网址,可节省资源
        另外style="display:none" 时iframe无法自动获取高宽 (需要用visibility:hidden;position: absolute)
        -->
        
            <td colspan="2" align="center" valign="top" >
            <?php EnlargeImg(ImgAdd($rs['img']),$rs['dgid'],2,100,100);?>
            </td>
        
		<td colspan="20" align="left">
<!----------------------------------------修改框架---------------------------------------->
<iframe src="" id="iframe<?=$rs['dgid']?>" name="iframe<?=$rs['dgid']?>" width="100%" height="0" frameborder="0" scrolling="auto"></iframe>
<script>
$(function(){ iframeHeight('iframe<?=$rs['dgid']?>',20); });
</script>

		</td>
		</tr>
						
		<!--分隔-开始-->
        <!--不要分隔-->
<!--		<tr>
			<td colspan="20" style="border: 0px none #ffffff;background-color:#ffffff; height:20px;"></td>
		</tr>
		<tr>
        </tr>
-->	   <!--分隔-结束-->
		
<?php
}
?>
					</tbody>
				</table>
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<font class="gray">【<?=$LG['selected']?><span id="IDNumber" class="red">0</span>】</font>

<!--************导出************-->
<?php if(permissions('daigou_ex','','manage',1) ){?>
	<select class="form-control select2me input-msmall" data-placeholder="报表类型" name="ex_tem">
	<option></option>
	<?php daigou_excel_export('',1)?>
	</select>
	<input type="hidden" name="callFrom" value="<?=$callFrom?>">
	 
	<button type="submit" class="btn btn-grey" style="margin-right:20px;"
	onClick="
	document.XingAoForm.target='_blank';
	document.XingAoForm.action='/xingao/daigou/excelExport/';
	"><i class="icon-signin"></i> 导出所选</button>
<?php }?>
	

<!--************修改************-->	
<?php if(permissions('daigou_ed','','manage',1) ){?>
    <input type="hidden" name="forList" value="1">
	<button type="submit" class="btn btn-grey"
	onClick="
	document.XingAoForm.target='_blank';
	document.XingAoForm.typ.value='';
	document.XingAoForm.action='batch.php';
	"><i class="icon-signin"></i> 修改</button>

<!--	return confirm('确认要对 '+document.getElementById('IDNumber').innerHTML+' 个代购单进行修改吗? ');
-->
<?php }?>	

<!--************拒绝************-->	
<?php if(have('0,1,2,3,4,5,pay,cg,memberStatus',$status,1)&&permissions('daigou_cg','','manage',1)){?>
<!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
document.XingAoForm.target='';
document.XingAoForm.action='save.php';
document.XingAoForm.typ.value='cancel';
return confirm('确定要拒绝采购吗? 拒绝后会退回此单所扣费用');
"><i class="icon-signin"></i> 拒绝采购</button>
<?php }?>


<!--************删除************-->	
<?php if( have('0,1,2,10',$status,1) || (!$off_delbak&&$status==8)){ ?>
<!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
document.XingAoForm.target='';
document.XingAoForm.typ.value='del';
document.XingAoForm.action='save.php';
return confirm('确认要删除所选吗?此操作不可恢复!\r相关操作记录也会一并删除');
"><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>
<?php }?>
 		




				</div>
                
                
				<div class="row">
					<?=$listpage?>
				</div>
			</form>
		</div>
		<!--表格内容结束--> 
		
		  
      <!--提示内容必须放这个位置并且要很长,否则最后一个包裹的操作菜单显示不全-->
    <div class="xats"> <strong>提示：</strong><br>
       &raquo; 代购流程：会员下单并支付费用 → 工作人员审核(如实际价格不同则补款/退款) → 购买商品(填写仓库地址及代购单入库码) → 等待包裹寄到仓库  →  入库检查(检查商品尺寸/颜色/质量等) → 扫描入库单号 → 手工勾选代购单中到库的商品 → 入库 (之后会员下单发货，从运单系统中操作)<br>
       
         &raquo; <?=$LG['daigou.40']//不支持积分抵消费用；不支持使用优惠券/折扣券；?>
    
		<?php if ($status==9.5){ ?>
            &raquo; <?=$LG['daigou.43']//已经全部发货并签收的才会在此分类?><br> 
        <?php }?>

    </div>

  
	</div>
</div>



<?php 
$sql->free(); //释放资源
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
$showbox=1;//是否用到 操作弹窗 (/public/showbox.php)
$id_save=1;//是否用到id_save()
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/js/daigouJS.php');//要放foot.php的后面
?>
