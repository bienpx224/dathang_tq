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
$headtitle=$LG['baoguo.op_1'];//包裹操作
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

if(!$off_baoguo||!$member_per[$Mgroupid]['ON_Mbaoguo']){exit ("<script>alert('{$LG['baoguo.add_form_2']}');goBack();</script>");}


//获取,处理-----------------------------------------------------------------------------------------------
$field=par($_REQUEST['field']);
$value=par($_REQUEST['value']);
$bgid=par(ToStr($_REQUEST['bgid']));

if(!$value)
{
	if(!is_array($field)&&$field){$field_now=explode(",",$field);}
	$field=par($field_now[0]);
	$value=par($field_now[1]);
}
if(!$bgid){$bgid=$_SESSION["bgid"];}
?>
<style>html{overflow-x:hidden;}</style>
<!----------------------------------------显示表单-开始------------------------------------------------>
<form action="op_save.php" method="post" class="form-horizontal form-bordered" name="xingao" style="width:620px;">
<input name="lx" type="hidden" value="pay">
<input name="bgid" type="hidden" value="<?=$bgid?>">
<input name="field" type="hidden" value="<?=$field?>">
<input name="value" type="hidden" value="<?=$value?>">
<?php
//验证
if(!$field){exit ($LG['baoguo.op_12']);}
if(!$bgid){exit ($LG['baoguo.hx_save_1']);}

$bg_number=arrcount($bgid);

//通用设置:
$ts_money=$LG['baoguo.op_2'];


switch($field)
{
	case 'status'://----------------------------------确认包裹---------------------------------------------
		op_name($LG['baoguo.call_op_menu_3']);
		$ts_money=$LG['baoguo.call_op_menu_2'];
		$ts='&raquo; '.LGtag($LG['baoguo.op_8'],'<tag1>=='.$bg_number).'<br>';
	break;
	
	case 'tra_user'://--------------------------------------转移会员-----------------------------------------
		op_name($LG['baoguo.call_op_menu_6']);
		if(!$off_tra_user){exit ($LG['baoguo.add_form_3']);}
		$ts_money=$LG['baoguo.op_6'];
		$ts='&raquo; '.LGtag($LG['baoguo.op_7'],'<tag1>=='.$bg_number).'<br>';
		?>
		<div class="form-group">
		<label class="control-label col-md-3"><?=$LG['baoguo.username'];//转到会员名?></label>
		<div class="col-md-9 has-error">
		   <input name="username" autocomplete="off" type="text" class="form-control input-medium input_txt_red"/>
		</div>
		</div>				  

		<div class="form-group">
		<label class="control-label col-md-3"><?=$LG['baoguo.userid'];//转到会员ID?></label>
		<div class="col-md-9 has-error">
		   <input name="userid" autocomplete="off"  type="text" class="form-control input-medium input_txt_red"/>
		</div>
		</div>
		<?php
	break;
	
	case 'ware'://---------------------------------仓储----------------------------------------------
		if($value){op_name($LG['baoguo.ware']);}else{op_name($LG['baoguo.op_14']);}		
		if(!$ON_ware){exit ($LG['baoguo.add_form_3']);}
		
		$ts_money=LGtag($LG['baoguo.op_15'],
	'<tag1>==<font class="show_price">'.$member_per[$Mgroupid]['bg_ware_freeDays'].'</font>||'.
	'<tag2>==<font class="show_price">'.$member_per[$Mgroupid]['bg_ware_price'].'</font>'.$XAmc
	 ).'<br>';
		$ts='&raquo; '.LGtag($LG['baoguo.op_8'],'<tag1>=='.$bg_number);
		
		if(!$value)
		{
			$total_money=0;
		?>
		<table class="table table-striped table-bordered table-hover" >
			<thead><tr>
				<td align="center"><strong><?=$LG['baoguo.op_9'];//单号?></strong></td>
				<td align="center"><strong><?=$LG['baoguo.list_9'];//入库时间?></strong></td>
				<td align="center"><strong><?=$LG['baoguo.list_7'];//仓储时间?></strong></td>
				<td align="center"><strong><?=$LG['baoguo.list_5'];//仓储费?></strong></td>
			</tr>
			</thead>
			<tbody>
			<?php
			$query="select * from baoguo where bgid in ({$bgid}) and ware='1' and status=3 and th<>2 {$Mmy}";
			$sql=$xingao->query($query);
			while($rs=$sql->fetch_array())
			{
				$total_money+=bg_ware_fee(1);
			?>
			<tr>
				<td align="center"><?=$rs['bgydh']?></td>
				<td align="center"><?=DateYmd($rs['rukutime']);?></td>
				<td align="center"><?=DateYmd($rs['ware_time']);?></td>
				<td align="center"><?=bg_ware_fee();?></td>
			</tr>
			<?php
			}
			?>
			</tbody>
			<thead>
			<tr>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"></td>
				<td align="center"><?=$LG['total']?><?=$total_money.$XAmc?></td>
			</tr>
			</thead>
		</table>
		<?php
			$ts.=$LG['baoguo.op_10'].$total_money.$XAmc.'<br>';
		}
	break;
	
	case 'op_04'://----------------------------------转移仓库---------------------------------------------
		op_name();
		if(!$off_baoguo_op_04){exit ($LG['baoguo.add_form_3']);}
		if(!$warehouse_more){exit ($LG['baoguo.op_16']);}
		$money=$member_per[$Mgroupid]['Price_04'];
		if($value==1)
		{
			$ts_money=$LG['baoguo.op_17'].'<font class="show_price">'.$money.'</font>'.$XAmc.'/'.$XAwt.'';
			$total_money=$bg_number*$money;
			$ts='&raquo; '.LGtag($LG['baoguo.op_18'],
				'<tag1>=='.$bg_number.'||'.
				'<tag2>=='.$total_money.$XAmc
				 ).'<br>';
		
		?>
		<div class="form-group">
		<label class="control-label col-md-2"><?=$LG['baoguo.warehouse'];//移到仓库?></label>
		<div class="col-md-10 has-error">
		  <select name="warehouse" class="form-control input-medium"  data-placeholder="<?=$LG['baoguo.add_form_12'];//选择?>" required>
			<?php warehouse('',1);?>
		  </select>
		</div>
		</div>
		<?php
		}
	break;
	
	case 'th'://--------------------------------------退货-----------------------------------------
		op_name();
		if(!$off_baoguo_th){exit ($LG['baoguo.add_form_3']);}
		$money=$member_per[$Mgroupid]['Price_th'];
		if($value==1)
		{
			$ts_money=LGtag($LG['baoguo.op_19'],'<tag1>==<font class="show_price">'.$money.'</font>'.$XAmc);
			$total_money=$bg_number*$money;
			$ts='&raquo; '.LGtag($LG['baoguo.op_18'],
				'<tag1>=='.$bg_number.'||'.
				'<tag2>=='.$total_money.$XAmc
				 ).'<br>';
			
			if(!stristr($bgid,',')&&$bgid)
			{
				$rs=FeData('baoguo','th_requ,th_img',"bgid={$bgid} {$Mmy}");
			}
		
		?>
		<div class="form-group">
		<label class="control-label col-md-2"><?=$LG['baoguo.th_img'];//Label文件?></label>
		<div class="col-md-10">
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='th_img';//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
         
		</div>
		</div>
        
		<div class="form-group">
		<label class="control-label col-md-2"><?=$LG['baoguo.th_requ'];//详细要求?></label>
		<div class="col-md-10 has-error">
		  <textarea name="th_requ" class="form-control" rows="5" placeholder="<?=$LG['baoguo.op_11'];//如退货的收件邮编、地址、姓名、电话及其他说明?>"><?=cadd($rs['th_requ'])?></textarea>
		</div>
		</div>
        <style>body{ height:570px;}</style>

		<?php
		}
	break;
	
	case 'op_02'://-----------------------------------验货--------------------------------------------
		op_name();
		if(!$off_baoguo_op_02){exit ($LG['baoguo.add_form_3']);}
		$money=$member_per[$Mgroupid]['Price_02'];
		if($value==1)
		{
			$ts_money=LGtag($LG['baoguo.op_20'],'<tag1>==<font class="show_price">'.$money.'</font>'.$XAmc);
			$total_money=$bg_number*$money;
			$ts='&raquo; '.LGtag($LG['baoguo.op_18'],
				'<tag1>=='.$bg_number.'||'.
				'<tag2>=='.$total_money.$XAmc
				 ).'<br>';
		}
	break;
	
	case 'op_09'://-------------------------------------清点------------------------------------------
		op_name();
		if(!$off_baoguo_op_09){exit($LG['baoguo.add_form_3']);}
		$money=$member_per[$Mgroupid]['Price_09'];
		if($value==1)
		{
			$ts_money=LGtag($LG['baoguo.op_20'],'<tag1>==<font class="show_price">'.$money.'</font>'.$XAmc);
			$total_money=$bg_number*$money;
			$ts='&raquo; '.LGtag($LG['baoguo.op_18'],
				'<tag1>=='.$bg_number.'||'.
				'<tag2>=='.$total_money.$XAmc
				 ).'<br>';
		}
	break;
	
	case 'op_06'://--------------------------------------拍照-----------------------------------------
		op_name();
		if(!$off_baoguo_op_06){exit($LG['baoguo.add_form_3']);}
		$money=$member_per[$Mgroupid]['Price_06'];
		if($value==1)
		{
			$ts_money=LGtag($LG['baoguo.op_20'],'<tag1>==<font class="show_price">'.$money.'</font>'.$XAmc);
			$total_money=$bg_number*$money;
			$ts='&raquo; '.LGtag($LG['baoguo.op_18'],
				'<tag1>=='.$bg_number.'||'.
				'<tag2>=='.$total_money.$XAmc
				 ).'<br>';
		}
	break;
	
	case 'op_07'://----------------------------------------减重---------------------------------------
		op_name();
		if(!$off_baoguo_op_07){exit($LG['baoguo.add_form_3']);}
		$money=$member_per[$Mgroupid]['Price_07'];
		if($value==1)
		{
			$ts_money=LGtag($LG['baoguo.op_20'],'<tag1>==<font class="show_price">'.$money.'</font>'.$XAmc);
			$total_money=$bg_number*$money;
			$ts='&raquo; '.LGtag($LG['baoguo.op_18'],
				'<tag1>=='.$bg_number.'||'.
				'<tag2>=='.$total_money.$XAmc
				 ).'<br>';
		}
	break;
	
	case 'op_10'://---------------------------------------复称----------------------------------------
		op_name();
		if(!$off_baoguo_op_10){exit($LG['baoguo.add_form_3']);}
		$money=$member_per[$Mgroupid]['Price_10'];
		if($value==1)
		{
			$ts_money=LGtag($LG['baoguo.op_20'],'<tag1>==<font class="show_price">'.$money.'</font>'.$XAmc);
			$total_money=$bg_number*$money;
			$ts='&raquo; '.LGtag($LG['baoguo.op_18'],
				'<tag1>=='.$bg_number.'||'.
				'<tag2>=='.$total_money.$XAmc
				 ).'<br>';
		}
	break;
	
	case 'op_11'://----------------------------------------空隙---------------------------------------
		op_name();
		if(!$off_baoguo_op_11){exit($LG['baoguo.add_form_3']);}
		$money=$member_per[$Mgroupid]['Price_11'];
		if($value==1)
		{
			$ts_money=LGtag($LG['baoguo.op_20'],'<tag1>==<font class="show_price">'.$money.'</font>'.$XAmc);
			$total_money=$bg_number*$money;
			$ts='&raquo; '.LGtag($LG['baoguo.op_18'],
				'<tag1>=='.$bg_number.'||'.
				'<tag2>=='.$total_money.$XAmc
				 ).'<br>';
		}
	break;
	
}

//显示标题(只对本页有效)
function op_name($op_name='')
{
	if(!$op_name)
	{
		global $field,$value;
		if($field&&$value)
		{	
			$op_name='baoguo_'.$field;	$op_name=$op_name($value);
		}
	}
	
	if($op_name)
	{
		echo '<h4 class="modal-title"><strong>'.$op_name.'：</strong></h4>';
	}

}

?>
<!----------------------------------------显示表单-结束------------------------------------------------>

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/baoguo/call/pay_show.php');?>
</form>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');?>
