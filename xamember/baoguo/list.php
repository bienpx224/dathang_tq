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
$headtitle=$LG['name.nav_66'];//包裹管理
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

if(!$off_baoguo||!$member_per[$Mgroupid]['ON_Mbaoguo']){exit ("<script>alert('{$LG['baoguo.add_form_2']}');goBack();</script>");}

//处理:1125

$where="1=1";
$status=par($_GET['status']);
if(!$status){$status='kuwai';}


//取出保存的ID
if($id_name!='gdid')
{
	$id_checked=ToArr(par($_SESSION[$id_name]));
	$gdid=par(ToStr($_REQUEST['gdid']));
}



//取出保存的ID
$id_name='bgid';
if($_SESSION["old_status"]==$status)
{
	$id_checked=ToArr(par($_SESSION[$id_name]));
}else{
	$_SESSION[$id_name]='';
}
$_SESSION["old_status"]=$status;

switch($status)
{
	case 'all':
		$where.="";
	break;
	
	case 'kuwai':
		$where.=" and status in (0,1,1.5) and ware=0";
	break;

	case 'ruku':
		$where.=" and status in (2,3) and ware=0";
	break;
	
	case 'ware':
		$where.=" and ware=1"; 
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
	
	$addSource=par($_GET['addSource']);
	$stime_add=par($_GET['stime_add']);
	$etime_add=par($_GET['etime_add']);
	$stime_ruku=par($_GET['stime_ruku']);
	$etime_ruku=par($_GET['etime_ruku']);
	
	if($key){$where.=" and (bgydh like '%{$key}%'  or bgid='".CheckNumber($key,-0.1)."' or kuaidi like '%{$key}%' or fahuodiqu like '%{$key}%')";}
	if(CheckEmpty($warehouse)){$where.=" and warehouse='{$warehouse}'";}

	if(CheckEmpty($addSource)){$where.=" and addSource='{$addSource}'";}
	if($stime_add){$where.=" and addtime>='".strtotime($stime_add." 00:00:00")."'";}
	if($etime_add){$where.=" and addtime<='".strtotime($etime_add." 23:59:59")."'";}
	if($stime_ruku){$where.=" and rukutime>='".strtotime($stime_ruku." 00:00:00")."' and status>1";}
	if($etime_ruku){$where.=" and rukutime<='".strtotime($etime_ruku." 23:59:59")."' and status>1";}
	
	//筛选菜单
	$field=par($_GET['field']);
	$zhi=par($_GET['zhi']);
	if(CheckEmpty($field)&&CheckEmpty($zhi)){$where.=" and {$field}='{$zhi}' ";}

	$search.="&so={$so}&key={$key}&warehouse={$warehouse}&addSource={$addSource}&stime_add={$stime_add}&etime_add={$etime_add}&stime_ruku={$stime_ruku}&etime_ruku={$etime_ruku}";
}

$order=' order by status asc,bgydh asc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from baoguo where {$where}  {$Mmy} {$order}";

//分页处理
//$line=20;$page_line=15;//$line=-1则不分页(不设置则默认)
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
		  $callFrom='member';
		  require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/options_menu.php'); 
	  ?>
	  <button type="button" class="btn btn-default" onClick="location.href='/public/idSave.php?lx=sc&id_name=<?=$id_name?>';"><i class="icon-trash"></i> <?=$LG['baoguo.list_1'];//清空所有勾选?> </button>
	  
 	  <button type="button" class="btn btn-default" onClick="AllTrOpen();" id="AllTrBlack"><i class="icon-resize-full" id="AllTrBlackIco"></i> <font id="AllTrBlackName"><?=$LG['allOpen']//全部展开?></font>  </button>
		
		</ul>
			<!-- END PAGE TITLE & BREADCRUMB--> 
		</div>
	</div>
	<!-- END PAGE HEADER--> 
	
	<!-- BEGIN PAGE CONTENT-->
	<div class="tabbable tabbable-custom boxless">
		<ul class="nav nav-tabs">
		<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/baoguo/call/nav_num.php');?> 

			<li class="<?php if($status=='all'){echo 'active';$bgnum_status_all='<span class="badge badge-default">'.$num.'</span>';}?>" style="margin-right:30px;"><a href="?status=all"><?=$LG['all']?><?=$bgnum_status_all?></a></li>
				
			<li class="<?php if($status=='kuwai'){echo 'active';$bgnum_status_kuwai='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=kuwai"><?=baoguo_Status(0)?><?=$bgnum_status_kuwai?></a></li>
			
			<li class="<?php if($status=='ruku'){echo 'active';$bgnum_status_ruku='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=ruku"><?=baoguo_Status(3)?><?=$bgnum_status_ruku?></a></li>
			
			<li class="<?php if($status=='4'){echo 'active';$bgnum_status_4='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=4"><?=baoguo_Status(4)?><?=$bgnum_status_4?></a></li>


			<li class="<?php if($status=='6'){echo 'active';$bgnum_status_6='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=6"><?=baoguo_Status(6)?><?=$bgnum_status_6?></a></li>
            
			<li class="<?php if($status=='7'){echo 'active';$bgnum_status_7='<span class="badge badge-success">'.$num.'</span>';}?>"><a href="?status=7"><?=baoguo_Status(7)?><?=$bgnum_status_7?></a></li>

			
			<li class="<?php if($status=='9'){echo 'active';$bgnum_status_9='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=9"><?=baoguo_Status(9)?><?=$bgnum_status_9?></a></li>
			
			<li class="<?php if($status=='10'){echo 'active';$bgnum_status_10='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=10"><?=baoguo_Status(10)?><?=$bgnum_status_10?></a></li>
			
			<?php if($ON_ware){?>
			<li class="<?php if($status=='ware'){echo 'active';$bgnum_status_ware='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=ware&orderby=ware_time&orderlx=desc"><?=$LG['baoguo.ware']?><?=$bgnum_status_ware?></a></li>
			<?php }?>
			

		</ul>
        <div class="tab-content" style="padding:10px;"> 
        <!--搜索-->
        <div class="navbar navbar-default" role="navigation">
            <div class="collapse navbar-collapse navbar-ex1-collapse">
            <form class="navbar-form navbar-left"  method="get" action="?">
                <input name="so" type="hidden" value="1">
                <input name="status" type="hidden" value="<?=$status?>">
                <div class="form-group">
                <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="<?=$LG['baoguo.list_2']?>" value="<?=$key?>">
              </div>
                <div class="form-group">
                <div class="col-md-0">
                    <select  class="form-control input-medium select2me" name="warehouse" data-placeholder="<?=$LG['warehouse'];//仓库?>">
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
                <button type="submit"  class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
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
                <th align="center" class="table-checkbox"> 
               
               <input type="checkbox"  id="aAll" onClick="chkAll(this);id_save();"  title="<?=$LG['checkAll'];//全选/取消?>"/><?php if($status=='ruku'&&$ON_daigou){?><br><a href="?<?=$search?>&orderby=addid&orderlx=" class="<?=orac('addid')?> tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['baoguo.addid_ppt']//按代购时所填写的地址排序<br>不同地址有不同颜色区分,方便知道哪些包裹是发同个地址?>"><?=$LG['baoguo.addid'];//地址排序?></a><?php }?>

                  </th>
                <th align="center">
                <a href="?<?=$search?>&orderby=bgydh&orderlx=" class="<?=orac('bgydh')?>"><?=$LG['baoguo.add_form_5'];//快递单号?></a>/<a href="?<?=$search?>&orderby=kuaidi&orderlx=" class="<?=orac('kuaidi')?>"><?=$LG['baoguo.add_form_6'];//快递公司?></a>
                
                </th>
                <th align="center"><a href="?<?=$search?>&orderby=warehouse&orderlx=" class="<?=orac('warehouse')?>"><?=$LG['warehouse'];//仓库?></a></th>
                <th align="center"><a href="?<?=$search?>&orderby=weight&orderlx=" class="<?=orac('weight')?>"><?=$LG['weight'];//重量?> </a>/<a href="?<?=$search?>&orderby=addSource&orderlx=" class="<?=orac('addSource')?>"><?=$LG['source'];//来源?></a></th>
                <th align="center"> <a href="?<?=$search?>&orderby=rukutime&orderlx=" class="<?=orac('rukutime')?>"><?=$LG['baoguo.list_3'];//入库?></a>/<a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>"><?=$LG['baoguo.list_22'];//预报?></a></th>
                <?php if($status=='ruku'||$status=='ware'){?>
                <th align="center"><?=$LG['baoguo.list_4'];//存放时间?></th>
                <?php }?>
                <th align="center"> <?php  
		if($status=='ware')
		{
			echo $LG['baoguo.list_5'];
			echo '/<a href="?'.$search.'&orderby=ware_time&orderlx=" class="'.orac('ware_time').'">'.$LG['baoguo.list_7'].'</a>';
		}else{
			//状态显示
			echo '<a href="?'.$search.'&orderby=status&orderlx=" class="'.orac('status').'">'.$LG['status'].'</a>';
		}
		?> </th>
                <th align="center"><?=$LG['op'];//操作?></th>
              </tr>
              </thead>
            <tbody>
<?php
$tri=0;
while($rs=$sql->fetch_array())
{
	$tri++;
	//是否可发货
	$fahuo=baoguo_fahuo(1);
	
	//显示勾选框
	$checkbox=0;
	if($status=='kuwai'&&$rs['addSource']!=3&&$rs['addSource']!=4){$checkbox=1;}//未入库时(用于:删除)
	elseif($status==9||$status==10){$checkbox=1;}//记录时(用于:删除)
	elseif($status=='ruku')//入库时(用于:确认,发货,操作)
	{
		//除非是已退货,否则都显示
		$checkbox=1;
		if($rs['th']==2){$checkbox=0;}
	}
	elseif($status=='ware'){$checkbox=1;}//入库时(用于:取出仓储)
	
	//按收件地址排序时
	if($orderby=='addid')
	{
		//按颜色显示:太乱,不使用
		//$addColor='';if($rs['addid']){$addColor='#'.Digit($rs['addid']*$rs['addid'],6,1,6);}
		$triShow=spr($rs['addid'],0,0);
		$triName=$LG['baoguo.addid_name'];//收件地址编号
	}else{
		$triShow=$tri;
		$triName=$LG['baoguo.list_24'];//本页排序号:
	}
	
	//是否勾选
	$checked=0; if(have($id_checked,$rs['bgid'])){$checked=1;}
?>
                <tr class="odd gradeX <?=$checked?'active':''?> <?=spr($rs['status'])==10?'gray2':''?>"  onclick="TestBlack('<?=$tri?>');">
                <td align="center" valign="middle" style="background-color:<?=$addColor?>;">
				<?php if ($checkbox){?><input name="bgid[]" type="checkbox" id="a" onClick="chkColor(this);id_save();"  value="<?=$rs['bgid']?>" <?=$checked?'checked':''?> /><?php }?><br>
                
                    <font class=" tooltips gray2" data-container="body" data-placement="top" data-original-title="<?=$LG['baoguo.list_23'];//包裹ID:?><?=$rs['bgid']?> <?=$triName?><?=$triShow?>"><?=$triShow?></font>
                    
                    </th>
                  <td align="center" valign="middle"><a href="show.php?bgid=<?=$rs['bgid']?>" target="_blank"><?=cadd($rs['bgydh'])?></a> <br>
                    <font class="gray2"> <?=cadd($rs['kuaidi'])?> </font></td>
                <td align="center" valign="middle"><?=warehouse($rs['warehouse'])?></td>
                <td align="center" valign="middle"><?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?> <br>
                    <font class="gray2" title="<?=$LG['source'];//来源?>"> <?=baoguo_addSource($rs['addSource'])?> </font></td>
                <td align="center" valign="middle"><font  title="<?=$LG['baoguo.list_9'];//入库时间?>"> <?=DateYmd($rs['rukutime']);?> </font> <br>
                    <font class="gray2" title="<?=$LG['baoguo.list_10'];//添加/预报时间?>"><?=DateYmd($rs['addtime']);?></font></td>
                <?php if($status=='ruku'||$status=='ware'){?>
                <td align="center" valign="middle"><?php bg_ware_days();?></td>
                <?php }?>
                <td align="center" valign="middle"><?php  
		if($status=='ware')
		{
			bg_ware_fee();
			echo '<br><font class="gray2" title="'.$LG['baoguo.list_7'].'">'.DateYmd($rs['ware_time']).'</font>';
		}else{
			//状态显示
			require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/status_show.php');
		}
		?></td>
                <td align="center" valign="middle">
                
                
 <?php 
 $mr=FeData('member','groupid,CustomerService',"userid='{$rs['userid']}'");
if($mr['CustomerService']){
	$r=CustomerService($mr['CustomerService']);
?>
	<?php if($r[6]){?>
    <a href="<?=urldecode($r[6])?>" class="btn btn-success" target="_blank" style="color:#ffffff"><i class="icon-comment"></i> <?=$LG['consulting']?></a>
    <?php }?>    
<?php }?>    

               
	<?php  
    //操作菜单
	if(spr($rs['status'])!=10||!$off_delbak)
	{
		$callFrom_op=1;
		require($_SERVER['DOCUMENT_ROOT'].'/xamember/baoguo/call/op_menu.php');
	}?>
    </td>
              </tr>
              
                <tr id="trshow<?=$tri?>" style="display:<?=$tri>1?'none':''?>">
                <td colspan="20" align="left">
		<?php  
		//底部通用内容调用
		$callFrom='member';
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/basic_show.php');
		?>
        
        </td>
              </tr>
              
              
                <!--分隔-开始-->
                <!--<tr>
                <td colspan="10" style="border-left: 0px none #ffffff;	border-right: 0px none #ffffff; background-color:#ffffff; height:30px;"></td>
                </tr>
                <tr>
                </tr>-->
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



<font class="gray">【<?=$LG['selected']?>
              <span id="IDNumber" class="red">0</span>
              】</font> 
            
            <!--************未入库时和记录的按钮************--> 
            <?php if($status=='kuwai'||($status==9&&!$off_delbak)||$status==10){ ?>
            <!--btn-danger--><button type="submit"  class="btn btn-grey" onClick="
document.XingAoForm.target='';            
document.XingAoForm.lx.value='del';
return confirm('<?=$LG['pptDelConfirm'];//确认要删除所选吗?此操作不可恢复!?>');
"><i class="icon-signin"></i> <?=$LG['delSelect'];//删除所选?></button>
            <?php }?> <?php if($off_baoguo_zxyd&&$status=='kuwai'){?>
            <button type="submit"  class="btn btn-grey"   style="margin-right:20px;"
	onClick="
	document.XingAoForm.action='save.php';
	document.XingAoForm.lx.value='zxyd';
    document.XingAoForm.target='';
	return confirm('<?=$LG['baoguo.list_11'];//确认要设置所选吗?此操作不可恢复!?> ');
	"><i class="icon-external-link"></i> <?=$LG['baoguo.list_12']?>【<?=baoguo_Status(1)?>】</button>
            <?php }?> 
            
            <!--************未入库时,待发货时的按钮************--> 
            <?php if($status=='ruku'||($off_baoguo_zxyd&&$status=='kuwai')){?>
<!--btn-primary--><button type="submit"  class="btn btn-grey  tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['baoguo.list_14'];//如果多选则合箱发货 (同个仓库的包裹才能合箱)?>"
onClick="
document.XingAoForm.action='/xamember/baoguo/delivery.php?typ=1<?=$status=='kuwai'?'&bg_zxyd=1':''?>';
document.XingAoForm.target='_blank';
"><i class="icon-external-link"></i> <?=$LG['yundan.tanks'];//合箱发货?></button>
            <?php }?> 
            
            <!--************待发货时的按钮************--> 
            <?php if($status=='ruku'){?>
            <button type="submit"  class="btn btn-grey"   style="margin-right:20px;"
	onClick="
    document.XingAoForm.target='';
	document.XingAoForm.action='save.php';
	document.XingAoForm.lx.value='allxd';
	return confirm('<?=$LG['baoguo.list_11']?> ');
	"><i class="icon-external-link"></i> <?=$LG['baoguo.list_12']?>【<?=baoguo_Status(4)?>】</button>
            <select class="form-control select2me input-msmall" data-placeholder="<?=$LG['op'];//操作?>" id='field'>
                <option></option>
                <?php  
	$callFrom_op=0;
	require($_SERVER['DOCUMENT_ROOT'].'/xamember/baoguo/call/op_menu.php');
	?>
              </select>
            <a class="btn btn-default showdiv" target="XingAobox" href="" onClick="get_field();" id="msg_field"> <i class="icon-signin"></i> <?=$LG['baoguo.list_25']//提交操作?> </a> <?php }?> 
            
            <!--************仓储时的按钮************--> 
            <?php if($status=='ware'){?> <a class="btn btn-primary showdiv" target="XingAobox" href="op.php?field=ware&value=0"> <i class="icon-eject"></i> <?=$LG['baoguo.list_26']//取出?> </a> <?php }?> </div>
            <div class="row"> <?=$listpage?> </div>
          </form>
      </div>
<!--表格内容结束--> 
		
		  
		  <div class="xats" style="min-height:300px"> <!--设置最小高度,否则下拉菜单显示不全-->
          <br>

        <strong> <?=$LG['pptInfo']?></strong><br />
         
	<!--************************************未入库时的-提示**********************************-->	
	<?php if($status=='kuwai'){?>
		  <?php if($off_mall||$ON_daigou){ ?>
		  &raquo; <?=$LG['baoguo.list_16']?><br />
		  <?php }?>
	<?php } ?>
	
	<!--************************************待下单-提示**********************************-->
	<?php if($status=='ruku'){?>
          <font  class="red">&raquo; <?=$LG['baoguo.list_17']?><br />
          </font> 
		  &raquo; <?=$LG['baoguo.list_27']?><a href="<?php $xacd=ClassData($price_classid);echo pathLT($xacd['path']);?>" target="_blank"><?=cadd($xacd['name'])?></a><br />
 	<?php }?>
	
	<!--************************************已全部下运单-提示**********************************-->
	<?php if($status==4){?>
		 &raquo; <?=$LG['baoguo.list_18']?><?=LGtag($LG['baoguo.list_18'],'<tag1>=='.baoguo_Status(10))?><br />
	<?php }?>
	
	<!--************************************仓储-提示**********************************-->
	<?php if($ON_ware){?>
		<?php if($status=='ware'){?>
			  &raquo; <?=$LG['baoguo.list_19']?><br />
		<?php }?>
	<?php }?>
	
	<!--************************************记录-提示**********************************-->
	<?php if($status==10){?>
	 &raquo; <?=$LG['baoguo.list_20']?><br />
	<?php }?>
	
	<!--************************************通用-提示**********************************-->
 		<?php if($ON_ware&&($status=='ruku'||$status=='ware')){?>
			  &raquo;
			  <?=LGtag($LG['baoguo.list_28'],
                '<tag1>=='.$member_per[$Mgroupid]['bg_ware_freeDays'].'||'.
                '<tag2>=='.$member_per[$Mgroupid]['bg_ware_price'].'||'.
                '<tag3>=='.$XAmc.'||'.
                '<tag4>=='.($member_per[$Mgroupid]['bg_ware_weight']>0?$member_per[$Mgroupid]['bg_ware_weight'].$XAwt.'/':'')
             );?><br />	
		<?php }?>
        
        <?php if($status=='ruku'&&$ON_daigou){echo "&raquo; {$LG['baoguo.addid_ppt']}<br />";}?>
        &raquo; <?=$LG['baoguo.list_21']?><br />
		  
	</div>
	</div>
</div>



<script language="javascript">
function get_field()
{
	var field=document.getElementById("field").value
	document.getElementById("msg_field").href ='op.php?field='+field;
}
</script>



<?php
$sql->free(); //释放资源
$showbox=1;//是否用到 操作弹窗 (/public/showbox.php)
$id_save=1;//是否用到id_save()
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
