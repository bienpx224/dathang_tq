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
if(!$off_mall)
{
	exit ("<script>alert('{$LG['mall_order.form_2']}');goBack('uc');</script>");
}

//处理:1125

$where="1=1";
$pay=par($_GET['pay']);//$pay=1 订单;$pay=0购物车
if(!CheckEmpty($pay)){$pay=1;}
if(CheckEmpty($pay)){$where.=" and pay='{$pay}'";}
$search.="&pay={$pay}";

//取出保存的ID
$id_name='odid';
if($_SESSION["old_pay"]==$pay)
{
	$id_checked=ToArr(par($_SESSION[$id_name]));
}else{
	$_SESSION[$id_name]='';
}
$_SESSION["old_pay"]=$pay;


$headtitle=$LG['mall_order.list_3'];//商城订单管理
if(!$pay){$headtitle=$LG['mall_order.list_4'];}
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

//搜索
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$status=par($_GET['status']);
	$time=par($_GET['time']);
	$warehouse=par($_GET['warehouse']);

	if($key){$where.=" and (title like '%{$key}%' or category like '%{$key}%' or coding='{$key}' or odid='".CheckNumber($key,-0.1)."' )";}
	
	if(CheckEmpty($status)){$where.=" and status='{$status}'";}
	if(CheckEmpty($warehouse)){$where.=" and warehouse='{$warehouse}'";}
	if($time)
	{
		$nowtime=time()-$time;
		$where.=" and addtime>='$nowtime'";
	}
	$search.="&so={$so}&key={$key}&status={$status}&time={$time}&warehouse={$warehouse}";
}

$order=' order by status asc,odid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$query="select * from mall_order where {$where} {$Mmy} {$order}";

$line=10;$page_line=15;//分页处理，不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
      <h3 class="page-title"><a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
      <ul class="page-breadcrumb breadcrumb">
	  <button type="button" class="btn btn-default" onClick="location.href='/public/idSave.php?lx=sc&id_name=<?=$id_name?>"><i class="icon-trash"></i> <?=$LG['mall_order.list_5'];//清空所有勾选?> </button>
	  
		
		</ul>
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="tabbable tabbable-custom boxless">
    <ul class="nav nav-tabs" style="margin-top:10px;">
      <li class="<?=$pay==1?'active':'';?>"><a href="?pay=1"><?=$LG['mall_order.list_34'];//订单?></a></li>
      <li class="<?=$pay==0?'active':'';?>"><a href="?pay=0"><?=$LG['name.nav_69'];//购物车?></a></li>
    </ul>
	
	
    <div class="tab-content" style="padding:10px;"> 
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="<?=$LG['mall_order.list_1']?>" value="<?=$key?>">
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="time" data-placeholder="<?=$LG['mall_order.list_6'];//订购时间?>">
                  <option></option>
                  <option value="86400" <?=$time=='86400'?' selected':''?>><?=$LG['mall_order.list_7'];//1天内?></option>
                  <option value="172800" <?=$time=='172800'?' selected':''?>><?=$LG['mall_order.list_9'];//2天内?></option>
                  <option value="604800" <?=$time=='604800'?' selected':''?>><?=$LG['mall_order.list_11'];//1周内?></option>
                  <option value="2592000" <?=$time=='2592000'?' selected':''?>><?=$LG['mall_order.list_13'];//1个月内?></option>
                  <option value="7948800" <?=$time=='7948800'?' selected':''?>><?=$LG['mall_order.list_15'];//3个月内?></option>
                  <option value="15897600" <?=$time=='15897600'?' selected':''?>><?=$LG['mall_order.list_17'];//6个月内?></option>
                  <option value="31536000" <?=$time=='31536000'?' selected':''?>><?=$LG['mall_order.list_19'];//1年内?></option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control select2me" name="status" data-placeholder="<?=$LG['status'];//状态?>" style="width:160px;">
                  <option></option>
                  <?=mall_order_Status($status,1)?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-medium select2me" name="warehouse" data-placeholder="<?=$LG['warehouse'];//仓库?>">
                  <option></option>
                  <?=warehouse($warehouse,1)?>
                </select>
              </div>
            </div>
            <button type="submit"  class="btn btn-info"><i class="icon-search"></i> <?=$LG['search'];//搜索?></button>
          </form>
        </div>
      </div>
	  
	  
	  
	  
	  
	  
	  
	  
	  
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <input name="status" type="hidden">
        <input name="pay" type="hidden" value="<?=$pay?>">
		 
       <table class="table table-striped table-bordered table-hover" style="border:0px solid #ddd;">
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this);get_total_price();id_save();"  title="<?=$LG['checkAll'];//全选/取消?>"/>
              </th>
              <th align="center"><a href="?<?=$search?>&orderby=price&orderlx=" class="<?=orac('price')?>"><?=$LG['mall_order.form_8'];//金额?></a>/<a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>"><?=$LG['time'];//时间?></a></th>
              <th align="center"><?=$LG['mall_order.list_23'];//支付状态?></th>
              <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['mall_order.list_24'];//订单状态?></a></th>
              <th align="center"><?=$LG['op'];//操作?></th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
	
	//是否勾选
	$checked=0; if(have($id_checked,$rs['odid'])){$checked=1;}
?>
            <tr class="odd gradeX <?=$checked?'active':''?>">
              <td rowspan="2" align="center" valign="middle">
                <?php EnlargeImg(ImgAdd($rs['titleimg']),$rs['odid'],2)?>
              
				<?php
				$total_price=0;
				if(!$pay&&spr($rs['status'])!=3)//购物车并且未失效时才计算
				{
					if(!$rs['pay'])
					{
						$total_price=spr( spr($rs['price'])*$rs['number'] + spr($rs['price_other'])-spr($rs['payment'])); 
					}
				}//if(!$pay)
				?>
				
				<input id="infoid_<?=$rs['odid']?>" type="hidden" value="<?=$total_price?>"><!--JS要获取,不能没有-->
                <input type="checkbox" id="a" onClick="chkColor(this);id_save();get_total_price();"  name="odid[]" value="<?=$rs['odid']?>" <?=$checked?'checked':''?> />
               
         
                <font class="gray tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['mall_order.form_5'];//订单ID?>"><?=$rs['odid']?></font>
                <font class="gray tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['mall_order.list_26'];//商品编号?>"><?=cadd($rs['coding'])?></font> 
              
              </td>
              <td align="center" valign="middle">
			    <?php require($_SERVER['DOCUMENT_ROOT'].'/xingao/mall_order/call/money_payment.php');?>
              </td>
              <td align="center" valign="middle"><?=$pay_status?></td>
             
              <td align="center" valign="middle">
			  
					 <?php if($rs['bgid']){?>
					  <a href="/xamember/baoguo/show.php?bgid=<?=$rs['bgid']?>" target="_blank" title="ID:<?=$rs['bgid']?>"> 
					  <?=mall_order_Status(spr($rs['status']));?>                     
					  </a>
					 <?php 
					 }else{ 
						 echo mall_order_Status(spr($rs['status']));
					 }
					 ?>

			 </td>
              <td align="center" valign="middle">
              
              <?php if(!$rs['pay']&&spr($rs['status'])!='3'){?>
                    <a href="../payment/?fromtable=mall_order&payid=<?=$rs['odid']?>" id="autoClick<?=$rs['odid']?>" class="btn btn-xs btn-warning showdiv"  target="XingAobox"><i class="icon-money"></i> <?=$LG['mall_order.list_27'];//支付?></a>
              <?php }?>
              
			 
	
			 
			  <a href="form.php?lx=edit&odid=<?=$rs['odid']?>&pay=<?=$pay?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> <?=$LG['mall_order.list_28'];//查看/备注?></a>
                
                <?php if(!$rs['pay']||spr($rs['status'])==3){?>
                <a href="save.php?lx=del&odid=<?=$rs['odid']?>&pay=<?=$pay?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm'];//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a>
                <?php }?>
                
                </td>
            </tr>
            <!---->
            <tr>
              <td colspan="7" align="left"><div class="gray modal_border"> <a href="<?=$rs['url']?cadd($rs['url']):'/mall/show.php?mlid='.$rs['mlid'];?>" target="_blank">
                  <?=cadd($rs['title'])?>
                  </a> </div>
                <div class="gray modal_border">
                  <?php if($rs['warehouse']){ echo $LG['mall_order.list_41'].warehouse($rs['warehouse']).' &nbsp; ';}?>
                  <?php if($rs['brand']){ echo $LG['mall_order.list_42'].classify($rs['brand'],2).' &nbsp; ';}?>
                  <?php if($rs['size']){ echo $LG['mall_order.list_43'].cadd($rs['size']).' &nbsp; ';}?>
                  <?php if($rs['color']){ echo $LG['mall_order.list_44'].cadd($rs['color']).' &nbsp; ';}?>
                  <?php if($rs['package']){ echo $LG['mall_order.list_45'].cadd($rs['package']).' &nbsp; ';}?>
                  <?php if($rs['weight']){ echo $LG['mall_order.list_46'].spr($rs['weight']).$XAwt.'*'.$rs['number'].classify($rs['unit'],2).'='.(spr($rs['weight'])*$rs['number']).$XAwt.' &nbsp; ';}?>
                </div>
                
                <!---->
                
                <?php
         $zhi=cadd($rs['content']);
         if($zhi){
		 ?>
                <div class="gray modal_border"> <strong><?=$LG['mall_order.list_29'];//备注：?>：</strong>
                  <?php 
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['mall_order.list_29'],$time=$rs['addtime'],$nameid='content'.$rs['odid'],$count=100);
			?>
                </div>
                <?php }?>
                
                <!---->
                
                <?php
         $zhi=cadd($rs['reply']);
         if($zhi){
		 ?>
                <div class="gray modal_border"> <strong><?=$LG['mall_order.list_30'];//回复：?>：</strong>
                  <?php 
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['mall_order.list_30'],$time=$rs['replytime'],$nameid='reply'.$rs['odid'],$count=100);
			?>
                </div>
                <?php }?>
                
                <!----></td>
            </tr>
		<!--分隔-开始-->
		<tr>
			<td colspan="10" style="border-left: 0px none #ffffff;	border-right: 0px none #ffffff; background-color:#ffffff; height:30px;"></td>
		</tr>
		<tr></tr>
		<!--分隔-结束-->
<?php
}
?>
          </tbody>
        </table>			
            
           
<!--底部操作按钮固定--> 
<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
 
<div align="right" class="fixed_btn" id="Autohidden">



<font class="gray">【<?=$LG['mall_order.list_48']?><span id="IDNumber" class="red">0</span>】</font>	
	
				
		<?php if(!$pay){?>
		<a class="btn btn-warning showdiv"  target="XingAobox" href="../payment/?fromtable=mall_order">
		<i class="icon-signin"></i> <?=$LG['mall_order.list_32'];//支付所选?> <span title="<?php if ($off_integral){ echo $LG['mall_order.list_49']; } ?>">(<?=$LG['mall_order.list_33']?><font id="msg_total_payment">0</font><?=$XAmc?>)</span>
		</a>
		<?php }?>
        
<?php if(!$pay||!$off_delbak&&$pay){?>		
		<!--btn-danger--><button type="submit"  class="btn btn-grey" onClick="
		document.XingAoForm.lx.value='del';
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
<div class="xats">
    <?php if(!$pay){?>
    	<?php if($mall_order_time>0){?>
			&raquo;  <?=LGtag($LG['mall_order.list_50'],'<tag1>==<font class="red2">'.$mall_order_time.'</font>' )?><br>
		<?php }?>
 		&raquo; <?=$LG['mall_order.list_51']?><br>
  <?php }else{?>
    &raquo;  <?=$LG['mall_order.list_52']?><br>
  <?php }?>
 </div> 
  </div>
</div>

<script language="javascript">
function get_total_price()
{
	//获取多选的值
	var eless = document.getElementsByName("odid[]");//必须用Name
	var infoidFloat=0;
	var total_price=0;
	var price=0;
	   for(var i=0;i<eless.length;i++)
	  {
		 if(eless[i].checked)
		 {
			   total_price=parseInt(eless[i].value);
			   if(total_price>0)
			   { 
				   //计算费用
				   price=document.getElementById("infoid_"+total_price).value;
				   infoidFloat=infoidFloat+parseFloat(price);
				   
			   }
		 }
	  }
	document.getElementById("msg_total_payment").innerHTML =decimalNumber(infoidFloat,2);
 }
 </script>
 


<?php
$sql->free(); //释放资源
$showbox=1;//是否用到 操作弹窗 (/public/showbox.php)
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
$id_save=1;//是否用到id_save()
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
<script language="javascript">
	//单独分开,要放在foot.php后面
	$(function(){       
			get_total_price();
	});
	
	<?php if(spr($_GET['autoClick'])&&$_SESSION['autoClick']){?>
		$(function(){ document.getElementById("autoClick<?=spr($_GET['autoClick'])?>").click(); });
	<?php $_SESSION['autoClick']=0;}?>
</script>

