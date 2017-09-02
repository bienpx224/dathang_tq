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
$pervar='daigou';require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['name.nav_23'];//代购管理
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

$callFrom='member';//member 会员中心

$where="1=1";
$status=par($_GET['status']);

//取出保存的ID
if($status=='delivery')
{
	//发货-------------------------------------
	$id_name='goid';
}else{
	$id_name='dgid';
}
if($_SESSION["old_status"]==$status){$id_checked=ToArr(par($_SESSION[$id_name]));}else{	$_SESSION[$id_name]='';}
$_SESSION["old_status"]=$status;

if(CheckEmpty($status))
{
	switch($status)
	{
		case 'all':
			$where.="";
		break;
		
		case 'memberContentReplyNew':
			$where.=" and status not in (10)  and memberContentReplyNew='1'";
		break;
		
		case 'storage':
			$where.=" and status in (3,5,6,7)";
		break;
	
		case 'inStorage':
			$where.=" and status in (8,9)";
		break;
		
		case 'delivery':
			$where.=" and status in (8,9) and dgid in (select dgid from daigou_goods where inventoryNumber>0)";
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
	
	$stime_add=par($_GET['stime_add']);
	$etime_add=par($_GET['etime_add']);
	$stime_payTime=par($_GET['stime_payTime']);
	$etime_payTime=par($_GET['etime_payTime']);
	
	
	   
	if($key)
	{
		//联表查询:查商品表
		$where_gd=" or dgid in (select dgid from daigou_goods where godh like '%{$key}%')";  
		
		$where.=" and (dgid='".CheckNumber($key,-0.1)."'  or whcod='{$key}' or dgdh like '%{$key}%' or name like '%{$key}%' or address like '%{$key}%' {$where_gd}  )";
		
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

	$search.="&so={$so}&key={$key}&warehouse={$warehouse}&addSource={$addSource}&source={$source}&types={$types}&brand={$brand}&stime_add={$stime_add}&etime_add={$etime_add}&stime_payTime={$stime_payTime}&etime_payTime={$etime_payTime}";
}

$order=' order by left(dgdh,10) desc,right(dgdh,10) asc';//默认特排
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$query="select * from daigou where {$where} {$Mmy} {$order}";

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
		  //$callFrom='member';
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
			<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/daigou/call/nav_num.php');?> 
			
			<li class="<?php if($status=='all'){echo 'active';$dgnum_status_all='<span class="badge badge-default">'.$num.'</span>';}?>" style="margin-right:30px;"><a href="?status=all"><?=$LG['all']?><?=$dgnum_status_all?></a></li>
			
            <?php if($dg_checked){?>
			<li class="<?php if($status=='0'){echo 'active';$dgnum_status_0='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=0"><?=daigou_Status(0)?><?=$dgnum_status_0?></a></li>
            <?php }?>
			
			<li class="<?php if($status=='1'){echo 'active';$dgnum_status_1='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=1"><?=daigou_Status(1)?><?=$dgnum_status_1?></a></li>
            
			<li class="<?php if($status=='memberContentReplyNew'){echo 'active';$dgnum_memberContentReplyNew='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=memberContentReplyNew"><?=$LG['daigou.164']?><?=$dgnum_memberContentReplyNew?></a></li>
			
			<li class="<?php if($status=='2'){echo 'active';$dgnum_status_2='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=2"><?=daigou_Status(2)?><?=$dgnum_status_2?></a></li>
            
			<li class="<?php if($status=='4'){echo 'active';$dgnum_status_4='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=4"><?=daigou_Status(4)?><?=$dgnum_status_4?></a></li>
            
            
            
			<li class="<?php if($status=='storage'){echo 'active';$dgnum_status_storage='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=storage"><?=$LG['baoguo.status0']?><?=$dgnum_status_storage?></a></li>
			
			<li class="<?php if($status=='inStorage'){echo 'active';$dgnum_status_inStorage='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=inStorage"><?=$LG['daigou.177']?><?=$dgnum_status_inStorage?></a></li>
			
            <li class="<?php if($status=='delivery'){echo 'active';$dgnum_status_delivery='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=delivery"><?=$LG['daigou.delivery'];//我要发货?><?=$dgnum_status_delivery?></a></li>
			
			<li class="<?php if($status=='9.5'){echo 'active';$dgnum_status_9_5='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=9.5"><?=daigou_Status(9.5)?><?=$dgnum_status_9_5?></a></li>
			
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
    <input type="text" name="key" class="form-control popovers" data-trigger="hover" data-placement="right"  data-content="<?=$LG['daigou.59']//代购单号/品名/电商网址/专柜地址/入库包裹单号/代购ID/主单ID/入库码?>" placeholder="<?=$LG['yundan.list_2'];//各类关键词?>"  value="<?=$key?>">
  </div>
  
  <div class="form-group">
    <select  class="form-control input-medium select2me" name="warehouse" data-placeholder="<?=$LG['warehouse'];//仓库?>">
      <option></option>
      <?=warehouse($warehouse,1)?>
    </select>
  </div>
  
  <div class="form-group">
    <select class="form-control input-small select2me" name="addSource" data-placeholder="<?=$LG['yundan.list_4'];//来源?>">
      <option></option>
      <?=daigou_addSource($addSource,1)?>
    </select>
  </div>
  
  <div class="form-group">
    <select class="form-control input-small select2me" name="source" data-placeholder="<?=$LG['daigou.source'];//货源?>">
      <option></option>
      <?php daigou_source($source,1)?>
    </select>
  </div>
  
  <div class="form-group">
    <select class="form-control input-small select2me" name="types" data-placeholder="<?=$LG['daigou.types'];//品类?>">
      <option></option>
      <?php ClassifyAll(4,$types)?>
    </select>
  </div>
  
  <div class="form-group">
    <select class="form-control input-small select2me" name="brand" data-placeholder="<?=$LG['brand'];//品牌?>">
      <option></option>
      <?php daigou_brand($brand,$Mgroupid,1)?>
    </select>
  </div>
  
   <button type="submit"  class="btn btn-info"><i class="icon-search"></i> <?=$LG['search'];//搜索?></button>
 
    <div style="margin-top:10px;">

        <div class="form-group">
          <div class="col-md-0">
            <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
              <input type="text" class="form-control input-small" name="stime_add" value="<?=$stime_add?>" placeholder="<?=$LG['yundan.Xcall_basic_show_21'];//下单时间?>">
              <span class="input-group-addon">-</span>
              <input type="text" class="form-control input-small" name="etime_add" value="<?=$etime_add?>"  placeholder="<?=$LG['yundan.Xcall_basic_show_21'];//下单时间?>">
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-md-0">
            <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
              <input type="text" class="form-control input-small" name="stime_payTime" value="<?=$stime_payTime?>" placeholder="<?=$LG['mall_order.Xcall_money_payment_7'];//支付时间?>">
              <span class="input-group-addon">-</span>
              <input type="text" class="form-control input-small" name="etime_payTime" value="<?=$etime_payTime?>"  placeholder="<?=$LG['mall_order.Xcall_money_payment_7'];//支付时间?>">
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
			   <?php if($status!='delivery'){?>
               <input type="checkbox"  id="aAll" onClick="chkAll(this);get_total_price();id_save();"  title="<?=$LG['checkAll'];//全选/取消?>"/>
               <?php }?>
              </th>
            
              
              <th align="center">
              <a href="?<?=$search?>&orderby=&orderlx=" class="<?=orac('dgdh')?>"><?=$LG['main.11'];//单号?></a><!--用默认排序,因此orderby=空-->

              </th>
              <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status'];//状态?></a></th>
              <th align="center"><?=$LG['daigou.61'];//处理?></th>
              <th align="center"><a href="?<?=$search?>&orderby=source&orderlx=" class="<?=orac('source')?>"><?=$LG['daigou.source'];//货源?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=name&orderlx=" class="<?=orac('name')?>"><?=$LG['mall_order.form_23'];//品名?></a></th>

              <th align="center"><a href="?<?=$search?>&orderby=brand&orderlx=" class="<?=orac('brand')?>"><?=$LG['brand']//品牌?></a></th>

              
              <th align="center"><a href="?<?=$search?>&orderby=freightFee&orderlx=" class="<?=orac('freightFee')?>"><?=$LG['daigou.50']//寄库运费?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=goodsFee&orderlx=" class="<?=orac('goodsFee')?>"><?=$LG['fee'];//费用?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=warehouse&orderlx=" class="<?=orac('warehouse')?>"><?=$LG['warehouse'];//仓库?></a></th>
             <?php if($status!='delivery'){?> <th align="center"><?=$LG['op'];//操作?></th><?php }?>
            </tr>
          </thead>
		  <tbody>
<?php
$tri=0;
while($rs=$sql->fetch_array())
{
	$tri+=1;
	$pay_money=0;
	if(have($rs['pay'],'0,2'))
	{
		$totalFee=daigou_totalFee($rs);//此单全部费用:价格币
		$totalPay=daigou_totalPay($rs);//已支付:价格币
		$pay_money=$totalFee-$totalPay;//要付的费用(总费用－已付费用)
		if($pay_money>0)
		{
			$pay_money*=daigou_exchangeBase(exchange($rs['priceCurrency'],$Mcurrency),$rs['userid']);//转支付币:不加spr,因为费用过小时,汇率后会小于2位小数,会显示0元
			if($pay_money>0&&$pay_money<0.01){$pay_money=0.01;}else{$pay_money=spr($pay_money);}
		}
	}
	
	//是否勾选
	$checked=0; if(have($id_checked,$rs['dgid'])){$checked=1;}
?>
<tr class="odd gradeX <?=$checked?'active':''?> <?=spr($rs['status'])==10?'gray2':''?>" onclick="TestBlack('<?=$tri?>');">
  <td align="center" valign="middle">
  
      <input id="infoid_<?=$rs['dgid']?>" type="hidden" value="<?=$pay_money?>"><!--JS要获取,不能没有-->

  <?php if($status!='delivery'){?>
      <input name="dgid[]" type="checkbox" id="a" onClick="chkColor(this);get_total_price();id_save();"  value="<?=$rs['dgid']?>" <?=$checked?'checked':''?> /><br>
  <?php }?>
      
      <font class=" tooltips gray2" data-container="body" data-placement="top" data-original-title="ID:<?=$rs['dgid']?> <?=$LG['yundan.list_28'];//本页排序号?>:<?=$tri?>"><?=$tri?></font>
      		
  </td>
  
  <td align="center" valign="middle">
  <a href="show.php?dgid=<?=$rs['dgid']?>" target="_blank"><?=cadd($rs['dgdh'])?></a>
</td>
  
  <td align="center" valign="middle" >
  <font class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.68'].DateYmd($rs['statusTime'],1)?>"><?=daigou_Status(spr($rs['status']),2)?></font><br>
  </td>
  
  <td align="center" valign="middle" >
<?php 
//操作状态
$memberStatus_show=daigou_memberStatus('',4,$rs); 
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
 </td>
  
  
  
  <td align="center" valign="middle">
<?php 
		if($rs['brand']){echo '<font class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['brand'].'">'.(daigou_brand($rs['brand'])).'</font>';}
		
		if($rs['color']){echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['front.86'].'">'.($rs['color']==0&&$rs['colorOther']?cadd($rs['colorOther']):classify($rs['color'],2)).'</font>';}
		
		if($rs['size']){echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['front.85'].'">'.($rs['size']==0&&$rs['sizeOther']?cadd($rs['sizeOther']):classify($rs['size'],2)).'</font>';}
?>
  </td>




  <td align="center" valign="middle"> <?=spr($rs['freightFee']).cadd($rs['priceCurrency'])?>  </td>
  <td align="center" valign="middle"> <?=daigou_showFee($rs)?> </td>
  <td align="center" valign="middle"><?=warehouse($rs['warehouse'])?></td>

  
  
 <?php if($status!='delivery'){?> 
  <td align="center" valign="middle">
	<?php  
    //操作菜单
	if(spr($rs['status'])!=10||!$off_delbak)
	{
		$callFrom_op=1;
		require($_SERVER['DOCUMENT_ROOT'].'/xamember/daigou/call/op_menu.php');
	}
    ?>	
   </td>
  <?php }?> 
   
</tr>						
		<tr id="trshow<?=$tri?>" 
		<?php if($status!='delivery'){?>
       	 target="iframe<?=$rs['dgid']?>"  url="op.php?dgid=<?=$rs['dgid']?>" 
		<?php }?> 
        style=" <?=$tri>1?'display:none':''?>">
        <!-- 
        target和 url 作用是展开后,在在框架打开网址,可节省资源
        另外style="display:none" 时iframe无法自动获取高宽 (需要用visibility:hidden;position: absolute)
        -->
        
        <td colspan="2" align="center" valign="top" >
        <?php EnlargeImg(ImgAdd($rs['img']),$rs['dgid'],2,100,100);?>
        </td>
        

		<td colspan="20" align="left">
        
        
        
<?php if($status!='delivery'){?>  
     
    <!----------------------------------------显示框架---------------------------------------->
    <iframe src="" id="iframe<?=$rs['dgid']?>" name="iframe<?=$rs['dgid']?>" width="100%" height="0" frameborder="0" scrolling="auto"></iframe>
    <script>
    $(function(){ iframeHeight('iframe<?=$rs['dgid']?>'); });
    </script>
    <!-- style="display:'none'" 时iframe无法自动获取高宽,因此先展示,在iframeHeight获取高度后再隐藏-->
    
<?php }else{
	
	//商品列表-开始
	$call_basic=1;//基本资料
	$call_content=1;//会员备注
	$call_memberContent=1;//会员留言
	$call_memberContentReply=1;//回复会员留言
	$call_sellerContent=0;//供应商留言
	$call_sellerContentReply=0;//回复供应商留言
	$callFrom_show=0;//显示全部留言文字内容
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/list_goods.php');
	//商品列表-结束
	
}?>



		</td>
		</tr>
						
		<!--分隔-开始-->
         <!--不要分隔-->
<!--		<tr>
			<td colspan="20" style="border: 0px none #ffffff;background-color:#ffffff; height:20px;"></td>
		</tr>
		<tr></tr>
-->	<!--分隔-结束-->
		
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

<!--************未付款-操作菜单*************-->	
<?php if($status=='pay'||$status==2||$status==4){?>

    <a class="btn btn-warning showdiv" target="XingAobox" href="../payment/?fromtable=daigou&field=">
    <i class="icon-signin"></i> <?=LGtag($LG['daigou.65'],'<tag1>==<font id="msg_total_payment">0</font>'.$Mcurrency)//支付费用 (共需付?>
    </a>

    <!--btn-danger--><button type="submit"  class="btn btn-grey" onClick="
    document.XingAoForm.target='';
    document.XingAoForm.action='save.php';
    document.XingAoForm.typ.value='cancel';
    return confirm('<?=$LG['daigou.54'];//确定要取消订购吗? 取消后会全部退回所扣费用?>');
    "><i class="icon-signin"></i> <?=$LG['daigou.55'];//取消订购?></button>

<?php }else{?>
    <font id="msg_total_payment" style="display:none"></font>
<?php }?>




	

<!--************下运单-操作菜单*************-->	
<?php if($status=='delivery'){ ?>
<!--btn-primary--><button type="submit"  class="btn btn-grey  tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['baoguo.list_14'];//如果多选则合箱发货 (同个仓库的包裹才能合箱)?>"
onClick="
document.XingAoForm.target='_blank';
document.XingAoForm.action='delivery.php';
document.XingAoForm.typ.value='1';
"><i class="icon-external-link"></i> <?=$LG['yundan.tanks'];//合箱发货?></button>
<?php }?>



<!--************导出************-->
<select class="form-control select2me input-msmall" data-placeholder="<?=$LG['daigou.66'];//报表类型?>" name="ex_tem">
<option></option>
<?php daigou_excel_export('',1)?>
</select>
<input type="hidden" name="callFrom" value="<?=$callFrom?>">
 
<button type="submit"  class="btn btn-grey" style="margin-right:20px;"
onClick="
document.XingAoForm.target='_blank';
document.XingAoForm.action='/xingao/daigou/excelExport/';
"><i class="icon-signin"></i> <?=$LG['daigou.67'];//导出所选?></button>


<!--************删除-操作菜单*************-->	
<?php if( have('0,1,2,10',$status,1) || (!$off_delbak&&$status==8)){ ?>
<!--btn-danger--><button type="submit"  class="btn btn-grey" onClick="
document.XingAoForm.target='';
document.XingAoForm.action='save.php';
document.XingAoForm.typ.value='del';
return confirm('<?=$LG['pptDelConfirm'];//确认要删除所选吗?此操作不可恢复!?>');
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
      <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/daigou/call/ppt.php');?>
  
	</div>
</div>

<script language="javascript">
function get_total_price()
{ 
	//获取多选的值
	var eless = document.getElementsByName("dgid[]");//必须用Name
	var total_price=0;
	var infoidFloat=0;
	var price=0;
	for(var i=0;i<eless.length;i++)
	  {
		 if(eless[i].checked)
		 {
			   total_price=parseInt(eless[i].value);
			   if(total_price>0)
			   { 
				   price=document.getElementById("infoid_"+total_price).value;
				   infoidFloat+=parseFloat(price);
			   }
		 }
	  }
	document.getElementById("msg_total_payment").innerHTML =decimalNumber(infoidFloat,2);
 }
$(function(){ get_total_price();});//算费用

</script>
 

<?php
$sql->free(); //释放资源
$showbox=1;//是否用到 操作弹窗 (/public/showbox.php)
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
$id_save=1;//是否用到id_save()
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/js/daigouJS.php');//要放foot.php的后面
?>
