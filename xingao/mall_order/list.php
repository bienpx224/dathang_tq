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
$pervar='mall_order';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

if(!$off_mall)
{
	exit ("<script>alert('商城系统未开启,无法使用！');goBack('uc');</script>");
}

//处理:1125

$where="1=1";
$pay=par($_GET['pay']);//$pay=1 订单;$pay=0购物车
if(!CheckEmpty($pay)){$pay=1;}
if(CheckEmpty($pay)){$where.=" and pay='{$pay}'";}
$search.="&pay={$pay}";

$headtitle="订单管理";
if(!$pay){$headtitle="购物车管理";}
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//搜索
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$status=par($_GET['status']);
	$time=par($_GET['time']);
	$warehouse=par($_GET['warehouse']);

	if($key){$where.=" and (title like '%{$key}%' or category like '%{$key}%' or coding='{$key}' or username='{$key}'  or userid='".CheckNumber($key,-0.1)."' or odid='".CheckNumber($key,-0.1)."' )";}
	
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


$query="select * from mall_order where {$where} {$Xwh} {$order}";

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
      
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="tabbable tabbable-custom boxless">
    <ul class="nav nav-tabs" style="margin-top:10px;">
      <li class="<?=$pay==1?'active':'';?>"><a href="?pay=1">订单</a></li>
      <li class="<?=$pay==0?'active':'';?>"><a href="?pay=0">购物车</a></li>
    </ul>
    <div class="tab-content" style="padding:10px;"> 
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="标题/会员名/会员ID/订单ID/商品编号/类别 (可留空)" value="<?=$key?>">
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="time" data-placeholder="订购时间">
                  <option></option>
                  <option value="86400" <?=$time=='86400'?' selected':''?>>1天内</option>
                  <option value="172800" <?=$time=='172800'?' selected':''?>>2天内</option>
                  <option value="604800" <?=$time=='604800'?' selected':''?>>1周内</option>
                  <option value="2592000" <?=$time=='2592000'?' selected':''?>>1个月内</option>
                  <option value="7948800" <?=$time=='7948800'?' selected':''?>>3个月内</option>
                  <option value="15897600" <?=$time=='15897600'?' selected':''?>>6个月内</option>
                  <option value="31536000" <?=$time=='31536000'?' selected':''?>>1年内</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control select2me" name="status" data-placeholder="状态" style="width:160px;">
                  <option></option>
                  <?=mall_order_Status($status,1)?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-medium select2me" name="warehouse" data-placeholder="仓库">
                  <option></option>
                  <?=warehouse($warehouse,1)?>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
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
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="全选/取消"/>
              </th>
              <th align="center"><a href="?<?=$search?>&orderby=price&orderlx=" class="<?=orac('price')?>">金额</a>/<a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">时间</a></th>
              <th align="center">支付状态</th>
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>">订单状态</a></th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php
while($rs=$sql->fetch_array())
{
	$bg=FeData('baoguo','bgydh,status,bgid',"bgid='{$rs['bgid']}'");
?>
            <tr class="odd gradeX">
              <td rowspan="2" align="center" valign="middle">
               
                <?php EnlargeImg(ImgAdd($rs['titleimg']),$rs['odid'],2)?>
                
                <input type="checkbox" id="a" onClick="chkColor(this);" name="odid[]" value="<?=$rs['odid']?>" />
                <font class="gray tooltips" data-container="body" data-placement="top" data-original-title="订单ID"><?=$rs['odid']?></font>
                <font class="gray tooltips" data-container="body" data-placement="top" data-original-title="商品编号"><?=cadd($rs['coding'])?></font> 
                
                </td>
              <td align="center" valign="middle">
			    <?php require($_SERVER['DOCUMENT_ROOT'].'/xingao/mall_order/call/money_payment.php');?>
              </td>
              <td align="center" valign="middle"><?=$pay_status?></td>
              <td align="center" valign="middle"><?=cadd($rs['username'])?>
                <br>
                <font class="gray">
                <?=$rs['userid']?>
                </font></td>
              <td align="center" valign="middle">
					 <?php if($rs['bgid']){?>
					  <a href="/xingao/baoguo/show.php?bgid=<?=$bg['bgid']?>" target="_blank" title="包裹ID: <?=$rs['bgid']?>"> 
					  <?=mall_order_Status(spr($rs['status']));?>                     
					  </a>
					 <?php 
					 }else{ 
						 echo mall_order_Status(spr($rs['status']));
					 }
					 ?>
			  </td>
              <td align="center" valign="middle">
              <a href="form.php?lx=edit&odid=<?=$rs['odid']?>" class="btn btn-xs btn-info" target="_blank"><i class="icon-edit"></i> 查看/编辑</a>
                <?php if(!$rs['pay']||spr($rs['status'])==3){?>
                <a href="save.php?lx=del&odid=<?=$rs['odid']?>&pay=<?=$pay?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a>
                <?php }?>
                
              
              <?php if($bg['bgid']&&spr($bg['status'])<=3){?>
              <a href="/xingao/baoguo/form.php?lx=edit&bgid=<?=$rs['bgid']?>#bgydh" class="btn btn-xs btn-default" target="_blank"><i class="icon-edit"></i> 编辑包裹</a>
              <?php }?>

             <?php if($bg['bgid']&&spr($bg['status'])<=1){?>
              <a href="/xingao/baoguo/inStorage.php?bgid=<?=cadd($bg['bgid'])?>" class="btn btn-xs btn-default" target="_blank"><i class="icon-download-alt"></i> 包裹入库</a>
              <?php }?>
                
                </td>
            </tr>
            <!---->
            <tr>
              <td colspan="7" align="left"><div class="gray modal_border"> <a href="<?=$rs['url']?cadd($rs['url']):'/mall/show.php?mlid='.$rs['mlid'];?>" target="_blank">
                  <?=cadd($rs['title'])?>
                  </a> </div>
                <div class="gray modal_border">
                  <?php if($rs['warehouse']){ echo '仓库:'.warehouse($rs['warehouse']).' &nbsp; ';}?>
                  <?php if($rs['brand']){ echo '品牌:'.classify($rs['brand'],2).' &nbsp; ';}?>
                  <?php if($rs['size']){ echo '尺寸:'.cadd($rs['size']).' &nbsp; ';}?>
                  <?php if($rs['color']){ echo '颜色:'.cadd($rs['color']).' &nbsp; ';}?>
                  <?php if($rs['package']){ echo '套餐:'.cadd($rs['package']).' &nbsp; ';}?>
                  <?php if($rs['weight']){ echo '重量:'.spr($rs['weight']).$XAwt.'*'.$rs['number'].classify($rs['unit'],2).'='.(spr($rs['weight'])*$rs['number']).$XAwt.' &nbsp; ';}?>
                </div>
                
                <!---->
                
                <?php
         $zhi=cadd($rs['content']);
         if($zhi){
		 ?>
                <div class="gray modal_border"> <strong>备注：</strong>
                  <?php 
			echo leng($zhi,100,"...");
			Modals($zhi,$title='说明备注',$time=$rs['addtime'],$nameid='content'.$rs['odid'],$count=100);
			?>
                </div>
                <?php }?>
                
                <!---->
                
                <?php
         $zhi=cadd($rs['reply']);
         if($zhi){
		 ?>
                <div class="gray modal_border"> <strong>回复：</strong>
                  <?php 
			echo leng($zhi,100,"...");
			Modals($zhi,$title='回复',$time=$rs['replytime'],$nameid='reply'.$rs['odid'],$count=100);
			?>
                </div>
                <?php }?>
                
                <!----></td>
            </tr>
		<!--分隔-开始-->
		<tr>
			<td colspan="10" style="border-left: 0px none #ffffff;	border-right: 0px none #ffffff; background-color:#ffffff; height:30px;"><!--放在CSS文件无效--></td>
		</tr>
		<tr></tr>
		<!--分隔-结束-->

<?php
}
?>
          </tbody>
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<?php if($pay){?>
          <button type="submit" class="btn btn-grey"
    onClick="
  document.XingAoForm.lx.value='attr';
  document.XingAoForm.status.value='0'; 
  "><i class="icon-signin"></i> 设为待生成包裹</button>
  
          <button type="submit" class="btn btn-grey"
    onClick="
  document.XingAoForm.lx.value='attr';
  document.XingAoForm.status.value='1'; 
  "><i class="icon-signin"></i> 设为已生成包裹</button>
  
          <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='attr';
  document.XingAoForm.status.value='3'; 
  "><i class="icon-signin"></i> 设为失效订单</button>
          <?php }?>
          
<?php if(!$off_delbak||$off_delbak&&$pay){?>
          <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='del';
  return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');
  "><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>
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
		&raquo;  购物车内商品有效时间: <font class="red2"><?=$mall_order_time?></font>小时,超过该时间将失效!<br>
		<?php }?>
   	&raquo;  购物车一般不用管理，当有会员恶意订购使商品数量减少时，才需进行删除管理!<br>
   <?php }else{?>
    &raquo;  只能删除失效的订单<br>
    &raquo;  失效订单：会自动还原商品数量(不自动 退回已付费用)，并且不能再修改状态！<br>
    
    &raquo;  批量订购操作流程：审核订单 (查看数量，支付情况) → 无库存时批量订购 → 有库存后，取出商品 → 点击“包裹入库”正常操作入库 (称重>贴标签>存放至会员个人仓位)<br>
    
    &raquo;  单个订购操作流程：审核订单 (查看数量，支付情况) → 转至电商外订购 → 电商发货后，点击“编辑包裹”，修改为真正的快递单号 → 等待入库 (跟会员的包裹预报入库流程一样)<br>
  <?php }?>

 </div> 
  </div>
</div>
<?php
$sql->free(); //释放资源
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
