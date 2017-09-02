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
$pervar='baoguo_ed,baoguo_se,baoguo_ad,baoguo_ot';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="包裹管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

if(!$off_baoguo){exit ("<script>alert('包裹系统已关闭！');goBack();</script>");}

//处理:1125

$callFrom='manage';

$where="1=1";
$status=par($_GET['status']);
if(!$status){$status='kuwai';}

//取出保存的ID
$id_name='Xbgid';
if($_SESSION["Xold_status"]==$status)
{
	$id_checked=ToArr(par($_SESSION[$id_name]));
}else{
	$_SESSION[$id_name]='';
}
$_SESSION["Xold_status"]=$status;

switch($status)
{
	case 'all':
		$where.="";
	break;
	
	case 'kuwai':
		$where.=" and status in (0,1) and ware=0";
	break;
	
	case 'ruku':
		$where.=" and status in (2,3) and ware=0";
	break;
	
	case 'ware':
		$where.=" and ware=1"; 
	break;
	
	case 'unclaimed':
		$where.=" and userid=0"; 
	break;
	
	
	default:
		$where.=" and status='{$status}'";
}
$search.="&status={$status}";


//搜索
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$warehouse=par($_GET['warehouse']);
	
	$other=par($_GET['other']);
	$addSource=par($_GET['addSource']);
	$stime_add=par($_GET['stime_add']);
	$etime_add=par($_GET['etime_add']);
	$stime_ruku=par($_GET['stime_ruku']);
	$etime_ruku=par($_GET['etime_ruku']);
	
	if($key){$where.=" and (bgydh like '%{$key}%'  or bgid='".CheckNumber($key,-0.1)."' or kuaidi like '%{$key}%' or fahuodiqu like '%{$key}%' or userid='".CheckNumber($key,-0.1)."' or useric='{$key}' or username like '%{$key}%' or unclaimedContent like '%{$key}%' )";}
	if(CheckEmpty($warehouse)){$where.=" and warehouse='{$warehouse}'";}

	if(CheckEmpty($addSource)){$where.=" and addSource='{$addSource}'";}
	if($stime_add){$where.=" and addtime>='".strtotime($stime_add." 00:00:00")."'";}
	if($etime_add){$where.=" and addtime<='".strtotime($etime_add." 23:59:59")."'";}
	if($stime_ruku){$where.=" and rukutime>='".strtotime($stime_ruku." 00:00:00")."' and status>1";}
	if($etime_ruku){$where.=" and rukutime<='".strtotime($etime_ruku." 23:59:59")."' and status>1";}
	
	if(CheckEmpty($other))
	{
		if($other=='unclaimed_1'){$where.=" and (unclaimed='1' and userid='0')";}
		elseif($other=='unclaimed_2'){$where.=" and (unclaimed='1' and userid>0)";}
		elseif($other=='op_1'){$where.=baoguo_fahuo(3);}
	}
	
	//筛选菜单
	$field=par($_GET['field']);
	$zhi=par($_GET['zhi']);
	if(CheckEmpty($field)&&CheckEmpty($zhi)){$where.=" and {$field}='{$zhi}' ";}

	$search.="&so={$so}&key={$key}&warehouse={$warehouse}&addSource={$addSource}&stime_add={$stime_add}&etime_add={$etime_add}&stime_ruku={$stime_ruku}&etime_ruku={$etime_ruku}&other={$other}";
}

$order=' order by status asc,bgydh asc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页



$query="select * from baoguo where {$where} {$Xwh} {$order}";

//分页处理
//$line=1;$page_line=15;//不设置则默认
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
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/options_menu.php'); 
	?>
	  <button type="button" class="btn btn-default" onClick="location.href='/public/idSave.php?lx=sc&id_name=<?=$id_name?>';"><i class="icon-trash"></i> 清空所有勾选 </button>
	  
	 <?php if(permissions('baoguo_ad','','manage',1) ){?>
	 <button type="button" class="btn btn-default" onClick="window.open('/xingao/baoguo/form.php');"><i class="icon-plus"></i> 添加包裹 </button>	
	 <?php }?>
     
     
	 <button type="button" class="btn btn-default" onClick="AllTrOpen();" id="AllTrBlack"><i class="icon-resize-full" id="AllTrBlackIco"></i> <font id="AllTrBlackName"><?=$LG['allOpen']//全部展开?></font>  </button>
		</ul>
			<!-- END PAGE TITLE & BREADCRUMB--> 
		</div>
	</div>
	<!-- END PAGE HEADER--> 
	
	<!-- BEGIN PAGE CONTENT-->
	<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/nav_num.php');?> 
	<div class="tabbable tabbable-custom boxless">
		<ul class="nav nav-tabs">
			<li class="<?php if($status=='all'){echo 'active';$bgnum_status_all='<span class="badge badge-default">'.$num.'</span>';}?>" style="margin-right:30px;"><a href="?status=all">全部<?=$bgnum_status_all?></a></li>
				
			<li class="<?php if($status=='kuwai'){echo 'active';$bgnum_status_kuwai='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=kuwai"><?=baoguo_Status(0)?><?=$bgnum_status_kuwai?></a></li>
           
            <?php if($bg_shelves){?>
			<li class="<?php if($status=='1.5'){echo 'active';$bgnum_status_1_5='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=1.5"><?=baoguo_Status(1.5)?><?=$bgnum_status_1_5?></a></li>
			<?php }?>
            
			<li class="<?php if($status=='ruku'){echo 'active';$bgnum_status_ruku='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=ruku"><?=baoguo_Status(3)?><?=$bgnum_status_ruku?></a></li>
			
			<li class="<?php if($status=='4'){echo 'active';$bgnum_status_4='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=4"><?=baoguo_Status(4)?><?=$bgnum_status_4?></a></li>

			<li class="<?php if($status=='6'){echo 'active';$bgnum_status_6='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=6"><?=baoguo_Status(6)?><?=$bgnum_status_6?></a></li>
            
			<li class="<?php if($status=='7'){echo 'active';$bgnum_status_7='<span class="badge badge-success">'.$num.'</span>';}?>"><a href="?status=7"><?=baoguo_Status(7)?><?=$bgnum_status_7?></a></li>

			
			
			<li class="<?php if($status=='9'){echo 'active';$bgnum_status_9='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=9"><?=baoguo_Status(9)?><?=$bgnum_status_9?></a></li>
			
			<li class="<?php if($status=='10'){echo 'active';$bgnum_status_10='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=10"><?=baoguo_Status(10)?><?=$bgnum_status_10?></a></li>
			
			<?php if($ON_ware){?>
			<li class="<?php if($status=='ware'){echo 'active';$bgnum_status_ware='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=ware&orderby=ware_time&orderlx=desc">仓储<?=$bgnum_status_ware?></a></li>
			<?php }?>
            
            
			<li class="<?php if($status=='unclaimed'){echo 'active';$bgnum_status_unclaimed='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=unclaimed">待领包裹<?=$bgnum_status_unclaimed?></a></li>
			
		</ul>
		<div class="tab-content" style="padding:10px;"> 
        <!--搜索-->
        <div class="navbar navbar-default" role="navigation">
            <div class="collapse navbar-collapse navbar-ex1-collapse">
            <form class="navbar-form navbar-left"  method="get" action="?">
                <input name="so" type="hidden" value="1">
                <input name="status" type="hidden" value="<?=$status?>">
                <div class="form-group">
                <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="运单号/包裹ID/快递公司/发货点/会员ID/会员名/会员入库码/认领资料 (可留空)" value="<?=$key?>">
  
<script language="javascript">
//默认光标在某个INPUT停留,可不用放在foot.php后面,要确保有那个ID,否则会停止执行后面的其他JS
$(function(){       
	document.getElementsByName("key")[0].focus(); //停留
});
</script>  
              </div>
                <div class="form-group">
                <div class="col-md-0">
                    <select  class="form-control input-medium select2me" name="warehouse" data-placeholder="仓库">
                    <option></option>
                    <?=warehouse($warehouse,1)?>
                  </select>
                  </div>
              </div>
              
                <div class="form-group">
                <div class="col-md-0">
                    <select  class="form-control input-small select2me" name="addSource" data-placeholder="<?=$LG['source']//来源?>">
                    <option></option>
                    <?=baoguo_addSource($addSource,1)?>
                  </select>
                  </div>
              </div>


      <div class="form-group">
        <select  class="form-control input-msmall select2me" name="other" data-placeholder="其他" >
          <option></option>
          <option value="op_1"  <?=$other=='op_1'?' selected':''?>>待操作</option>
          
          <option value="unclaimed_1"  <?=$other=='unclaimed_1'?' selected':''?>>待领包裹</option>
          <option value="unclaimed_2"  <?=$other=='unclaimed_2'?' selected':''?>>已领包裹</option>
          
      </select>
      </div>

                <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
                <div style=" margin-top:10px;">
                <div class="form-group">
                    <div class="col-md-0">
                    <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control input-small" name="stime_add" value="<?=$stime_add?>" placeholder="<?=$LG['main.14']//预报时间?>">
                        <span class="input-group-addon">-</span>
                        <input type="text" class="form-control input-small" name="etime_add" value="<?=$etime_add?>"  placeholder="<?=$LG['main.14']//预报时间?>">
                      </div>
                  </div>
                  </div>
                <div class="form-group">
                    <div class="col-md-0">
                    <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control input-small" name="stime_ruku" value="<?=$stime_ruku?>" placeholder="<?=$LG['main.15']//入库时间?>">
                        <span class="input-group-addon">-</span>
                        <input type="text" class="form-control input-small" name="etime_ruku" value="<?=$etime_ruku?>"  placeholder="<?=$LG['main.15']//入库时间?>">
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
                <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this);id_save();"  title="全选/取消"/>
                  </th>
                <th align="center"> <a href="?<?=$search?>&orderby=bgydh&orderlx=" class="<?=orac('bgydh')?>" title="寄库快递单号">单号</a>/<a href="?<?=$search?>&orderby=kuaidi&orderlx=" class="<?=orac('kuaidi')?>"  title="寄库快递公司">公司</a> (<a href="?<?=$search?>&orderby=special_bgydh&orderlx=" class="<?=orac('special_bgydh')?>" title="按快递单号最后位数排序">特排</a>) </th>
                <th align="center"><a href="?<?=$search?>&orderby=warehouse&orderlx=" class="<?=orac('warehouse')?>">仓库</a>/<a href="?<?=$search?>&orderby=whPlace&orderlx=" class="<?=orac('whPlace')?>">仓位</a></th>
                <th align="center"><a href="?<?=$search?>&orderby=weight&orderlx=" class="<?=orac('weight')?>">重量 </a>/<a href="?<?=$search?>&orderby=addSource&orderlx=" class="<?=orac('addSource')?>">来源</a></th>
                <th align="center"> <a href="?<?=$search?>&orderby=rukutime&orderlx=" class="<?=orac('rukutime')?>">入库</a>/<a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">预报</a></th>
                <?php if($status=='ruku'||$status=='ware'){?>
                <th align="center">存放时间</th>
                <?php }?>
                <th align="center"> <?php  
		if($status=='ware')
		{
			echo '仓储费';
			echo '/<a href="?'.$search.'&orderby=ware_time&orderlx="  class="'.orac('ware_time').'">仓储时间</a>';
		}else{
			//状态显示
			echo '<a href="?'.$search.'&orderby=status&orderlx=" class="'.orac('status').'">'.$LG['status'].'</a>';
		}
		?> </th>
                <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a> (<a href="?<?=$search?>&orderby=special_userid&orderlx=" class="<?=orac('special_userid')?>" title="按会员ID最后位数排序">特排</a>) </th>
                <th align="center">操作</th>
              </tr>
              </thead>
            <tbody>
<?php
$tri=0;
while($rs=$sql->fetch_array())
{
	$tri++;
	$mr=FeData('member','groupid,truename,enname',"userid='{$rs['userid']}'");
	
	//是否可发货
	$fahuo=baoguo_fahuo(1);
	
	//显示勾选框
	$checkbox=1;
	
	//是否勾选
	$checked=0; if(have($id_checked,$rs['bgid'])){$checked=1;}
?>
                <tr class="odd gradeX  <?=$checked?'active':''?> <?=spr($rs['status'])==9||spr($rs['status'])==10?'gray2':''?>" onclick="TestBlack('<?=$tri?>');">
                <td align="center" valign="middle">
				    <?php if ($checkbox){?><input name="bgid[]" type="checkbox" id="a" onClick="chkColor(this);id_save()" value="<?=$rs['bgid']?>" <?=$checked?'checked':''?> /><?php }?><br>
                
                    <font class=" tooltips gray2" data-container="body" data-placement="top" data-original-title="包裹ID:<?=$rs['bgid']?> 本页排序号:<?=$tri?>"><?=$tri?></font>
                    </th>
                  <td align="center" valign="middle"><a href="show.php?bgid=<?=$rs['bgid']?>" target="_blank"><?=cadd($rs['bgydh'])?></a> <br>
                    <font class="gray2"> <?=cadd($rs['kuaidi'])?> </font></td>
                <td align="center" valign="middle"><?=warehouse($rs['warehouse'])?> <br>
                    <font class="gray2"> <?=cadd($rs['whPlace'])?> </font></td>
                <td align="center" valign="middle"><?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?> <br>
                    <font class="gray2"> <?=baoguo_addSource($rs['addSource'])?> </font></td>
                <td align="center" valign="middle"><font  title="入库时间"> <?=DateYmd($rs['rukutime']);?> </font> <br>
                    <font class="gray2" title="添加/预报时间"><?=DateYmd($rs['addtime']);?></font></td>
                <?php if($status=='ruku'||$status=='ware'){?>
                <td align="center" valign="middle"><?php bg_ware_days();?></td>
                <?php }?>
                <td align="center" valign="middle">
		<?php  
		if($status=='ware')
		{
			bg_ware_fee();
			echo '<br><font class="gray2" title="仓储时间">'.DateYmd($rs['ware_time']).'</font>';
		}else{
			//状态显示
			require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/status_show.php');
		}
		?>
        </td>
                <td align="center" valign="middle">
    <!--显示会员账号-->                            
    <?php 
	if($rs['userid']){
	
		echo showUsername($rs['username'],$rs['userid'],$showUseric='0');
		$r=CustomerService($rs['userid']);echo $r[0]."({$r[1]})";

	}else{
		if($rs['unclaimedContent']){
		?>
    		<font class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['unclaimedContent'])?>">待领包裹 <i class="icon-info-sign"></i> </font>
    <?php 
		}else{
			echo '待领包裹';
		}
	}
	?>
                    </td>
                <td align="center" valign="middle"><?php  
		//操作菜单
		$callFrom_op=1;
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/op_menu.php');
		?></td>
              </tr>
              
              
<tr id="trshow<?=$tri?>" style="display:<?=$tri>1?'none':''?>">
<td colspan="10" align="left">
<?php  
require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/basic_show.php');
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


<font class="gray">【已选<span id="IDNumber" class="red">0</span>个包裹】</font> 
            
            <!--************未入库时和记录的按钮************--> 
            <?php if(permissions('baoguo_ad','','manage',1) ){?> <?php if($status=='kuwai'){ ?>
            <button type="submit" class="btn btn-grey" 
    onClick="
    document.XingAoForm.lx.value='ruku';
    return confirm('确认要入库所选吗? ');
    "><i class="icon-signin"></i> 入库所选</button>
            <?php }?> <?php }?> <?php if(permissions('baoguo_ed','','manage',1) ){?> <?php if($status=='kuwai'||($status==9&&!$off_delbak)||$status==10){ ?>
            <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
    document.XingAoForm.lx.value='del';
    return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');
    "><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>
            <?php }?> <?php }?> 
            
            <!--************待发货时的按钮************--> 
            <?php if(permissions('baoguo_ed','','manage',1) ){?> <?php if($status=='ruku'){?>
            <select class="form-control select2me input-msmall" data-placeholder="操作" id='field'>
                <option></option>
                <?php  
            $callFrom_op=0;
            require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/op_menu.php');
            ?>
              </select>
            <a class="btn btn-default" href="" onClick="get_field();" id="msg_field"> <i class="icon-signin"></i> 提交操作 </a>
            <button type="submit" class="btn btn-grey" 
        onClick="
        document.XingAoForm.lx.value='allxd';
        "><i class="icon-external-link"></i> 设为“<?=baoguo_Status(4)?>”</button>
            <?php }elseif($status==4){?>
            <button type="submit" class="btn btn-grey" 
        onClick="
        document.XingAoForm.lx.value='noxd';
        "><i class="icon-external-link"></i> 设为“<?=baoguo_Status(3)?>”</button>
            <?php }?> 
            
            <!--************仓储时的按钮************--> 
            <?php if($status=='ware'){?> <a class="btn btn-primary" href="save.php?field=ware&value=0"> <i class="icon-eject"></i> 取出 </a> <?php }?> <?php }?> 
            
            <!--************打印************--> 
            <?php if(permissions('baoguo_ot','','manage',1) ){?>
            <select class="form-control select2me input-msmall" data-placeholder="打印模板" name="print_tem" style="margin-left:10px;">
                <option></option>
                <?php baoguo_print('',1)?>
              </select>
            <button type="submit" class="btn btn-grey" style="margin-right:20px;"
	onClick="
	document.XingAoForm.target='_blank';
	document.XingAoForm.lx.value='pr';
	document.XingAoForm.action='print.php';
	" title="打开后按ctrl+p打印"><i class="icon-signin"></i> 打印所选</button>
            <?php }?> </div>
            <div class="row"> <?=$listpage?> </div>
          </form>
      </div>
		<!--表格内容结束--> 
		
		  
          <!--提示内容必须放这个位置并且要很长,否则最后一个包裹的操作菜单显示不全-->
		  <div class="xats"> 
          <br>

        <strong> 提示：</strong><br />
         
	<!--************************************未入库时的-提示**********************************-->	
	<?php if($status=='kuwai'){?>
		  <?php if($off_mall){ ?>
		  &raquo; 商城订单付款后会自动生成的包裹，打包、称重、贴标签等操作就可扫描入库！<br />
		  <?php }?>
	<?php } ?>
	
	<!--************************************待下单-提示**********************************-->
	<?php if($status=='ruku'){?>
          <font  class="red">&raquo; 如果服务是处于申请中时，在本页选择拒绝操作会自动退费！<br /></font> 
		   
 	<?php }?>
	
	<!--************************************已全部下运单-提示**********************************-->
	<?php if($status==4){?>
		 &raquo; 这里是已下单发货还没有签收到的包裹（已签收包裹会更新到“<?=baoguo_Status(10)?>”分类）<br />
	<?php }?>
	
	<!--************************************仓储-提示**********************************-->
	<?php if($ON_ware){?>
		<?php if($status=='ware'){?>
			  &raquo; 取出仓储后，如未有申请服务操作或发货，第二天会自动再次转入仓储！<br />
		<?php }?>
	<?php }?>
	
	<!--************************************记录-提示**********************************-->
	<?php if($status==10){?>
	 &raquo; 这些包裹已经不存在仓库，可能是已退货、已合箱、已转移给其他会员等，用于查看记录，也可以删除！<br />
	<?php }?>
	
		
&raquo; 如果未看到包裹，可能是在其他分类里，请点击对应分类查看！<br />		  
&raquo; 已入库并且没有退货的包裹不能删除 (如要删除请先修改状态为“<?=baoguo_Status(1)?>”或“<?=baoguo_Status(10)?>”)！<br />		  
		  
	</div>
	</div>
</div>


<script language="javascript">
function get_field()
{
	var field=document.getElementById("field").value
	document.getElementById("msg_field").href ='save.php?field='+field;
}
</script>



<?php
$sql->free(); //释放资源
$id_save=1;//是否用到id_save()
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
