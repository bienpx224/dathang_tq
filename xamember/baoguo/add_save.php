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

$headtitle=$LG['baoguo.add_save_1'];//提交包裹预报
$bg_zxyd=par($_POST['bg_zxyd']);
if($bg_zxyd){$headtitle=$LG['baoguo.add_save_2'];}

require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
if(!$off_baoguo||!$member_per[$Mgroupid]['ON_Mbaoguo']){exit ("<script>alert('{$LG['baoguo.add_form_2']}');goBack();</script>");}
if($bg_zxyd&&!$off_baoguo_zxyd){exit ("<script>alert('{$LG['baoguo.add_form_3']}');goBack();</script>");}
elseif(!$off_baoguo_zxyd&&!$off_baoguo_yubao){exit ("<script>alert('{$LG['baoguo.add_form_4']}');goBack();</script>");}

?>
	<div class="page_ny"> 
	<div class="tabbable tabbable-custom boxless">
		<ul class="nav nav-tabs">
			<li class="active"><a href="javascript:void(0)"><?=$LG['baoguo.add_form_25'];//在线预报?></a></li>
			<li><a href="excel_import.php"><?=$LG['name.nav_20'];//批量导入?></a></li>
		</ul>			
	<div class="tab-content">
	<div class="tab-pane active" id="tab_1">
	<div class="form">
	<div class="form-body">
	<div class=""><!--class="portlet"-->
	<div class="portlet-body form" style="display: block;"> 
<?php	

//通用获取,处理-------------------------------------------------------------------------------------------
$lx=par($_REQUEST['lx']);
$tokenkey=par($_POST['tokenkey']);
$bgid='';
//添加=========================================================================================
if($lx=='add')
{
	
	$token=new Form_token_Core();
	$token->is_token('baoguo',$tokenkey); //验证令牌密钥

	//获取(数组不能加处理)***************************************************************
	$bgydh=$_POST['bgydh'];
	$kuaidi=$_POST['kuaidi'];
	$warehouse=$_POST['warehouse'];
	$fahuodiqu=$_POST['fahuodiqu'];
	$fahuotime=$_POST['fahuotime'];
	$wangzhan=$_POST['wangzhan'];
	$wangzhan_other=$_POST['wangzhan_other'];
	$content=$_POST['content'];
	
	$wupin_id_s=$_POST['wupin_id_s'];
	$wupin_id_b=$_POST['wupin_id_b'];
	$wupin_type=$_POST['wupin_type'];
	$wupin_name=$_POST['wupin_name'];
	$wupin_brand=$_POST['wupin_brand'];
	$wupin_spec=$_POST['wupin_spec'];
	$wupin_number=$_POST['wupin_number'];
	$wupin_unit=$_POST['wupin_unit'];
	$wupin_price=$_POST['wupin_price'];
	$wupin_total=$_POST['wupin_total'];
	$wupin_weight=$_POST['wupin_weight'];

	//不验证,因为返回会清空物品***************************************************************
		
	//处理***************************************************************
	$rc=0;$error_have=0;
	foreach($bgydh as $key=>$value)
	{
		$error_result=0;
		
		if(!$error_result&&!$bgydh[$key])
		{
			echo '&raquo; '.$LG['baoguo.add_save_7'].'<br>';
			$error_result=1;$error_have=1;
		}

		if(!$error_result&&!$warehouse[$key])
		{
			echo '&raquo; <strong>'.$bgydh[$key].'</strong>：'.$LG['baoguo.add_save_8'].'<br>';
			$error_result=1;$error_have=1;
		}

		//判断单号是否已经存在
		if(!$error_result)
		{
			$num=mysqli_num_rows($xingao->query("select bgydh from baoguo where bgydh='{$value}' "));
			if($num)
			{
				echo '&raquo; <strong>'.$bgydh[$key].'</strong>：'.$LG['baoguo.add_save_9'].'<br>';
				$error_result=1;$error_have=1;
			}
		}

		if(!$error_result)
		{
	    	//保存部分-开始***************************************************************
			
			//保存包裹-开始
			$status=0;
			$xingao->query("insert into baoguo (`bgydh`,   `kuaidi`, `warehouse`, `fahuodiqu`, `fahuotime`, `wangzhan`, `wangzhan_other`,  `content`,`addSource`, `status`,`addtime`, `userid`,`useric`, `username`) values('".add($bgydh[$key])."','".add($kuaidi[$key])."','".add($warehouse[$key])."','".add($fahuodiqu[$key])."','".ToStrtotime($fahuotime[$key])."','".add($wangzhan[$key])."','".add($wangzhan_other[$key])."','".html($content[$key])."','1','{$status}','".time()."','{$Muserid}','{$Museric}','{$Musername}')");
			SQLError('添加');
			$rc+=mysqli_affected_rows($xingao);
			$fromid=mysqli_insert_id($xingao);
			//保存包裹-结束
			
			
			
			
			
			if($fromid>0&&$_POST['wupin_id_s'])
			{
				//处理保存物品-开始
				$fromtable='baoguo';//$fromid=0;
				$wupin_id_b_now=$wupin_id_b[$key];//大框ID  $wupin_id_b=$wupin_id_b[$key]; 不能用这种
				foreach($_POST['wupin_id_s'] as $key_w=>$value_w)//$_POST["wupin_id_s"]必须重新获取，否则只执行一次
				{
					$wupin_id_s=$value_w;//小框ID
					if($wupin_id_b_now==$wupin_id_s)//$wupin_id_b_now==$wupin_id_s判断该物品属于哪个包裹
					{	
						 foreach($_POST['wupin_name'.$wupin_id_s] as $key_line=>$value_line)
						 {
							  $xingao->query("insert into wupin (fromtable,fromid,wupin_type,wupin_name,wupin_brand,wupin_spec,wupin_price,wupin_unit,wupin_weight,wupin_number,wupin_total) values ('".add($fromtable)."','".spr($fromid)."','".add($_POST['wupin_type'.$wupin_id_s][$key_line])."','".add($_POST['wupin_name'.$wupin_id_s][$key_line])."','".add($_POST['wupin_brand'.$wupin_id_s][$key_line])."','".add($_POST['wupin_spec'.$wupin_id_s][$key_line])."','".spr($_POST['wupin_price'.$wupin_id_s][$key_line])."','".add($_POST['wupin_unit'.$wupin_id_s][$key_line])."','".spr($_POST['wupin_weight'.$wupin_id_s][$key_line])."','".spr($_POST['wupin_number'.$wupin_id_s][$key_line])."','".spr($_POST['wupin_total'.$wupin_id_s][$key_line])."')");
							  SQLError('保存物品');
						  }
					}
				}
				//处理保存物品-结束

				$bgid.=$fromid.',';//用于下运单
			}

	    	//保存部分-结束***************************************************************
	
		}//if(!$error_result){
	}//foreach($bgydh as $key=>$value)
	
	if($rc>0)
	{
		$token->drop_token('baoguo'); //处理完后删除密钥
		echo '<br> <strong>'.LGtag($LG['baoguo.add_save_10'],'<tag1>=='.$rc).'</strong>';
		
		if($bg_zxyd&&$bgid)
		{
			$bgid=substr($bgid,0,-1);
			$url='/xamember/baoguo/delivery.php?typ=0&bg_zxyd=1&bgid='.$bgid.'';
			if(!$error_have){
				echo '<script language=javascript>';
				echo 'window.open("'.$url.'");';
				echo '</script>';
				XAtsto($url);
			}else{
				?>
				<div align="center">
				<button type="button" class="btn btn-info" onClick="location.href='<?=$url?>';"> <i class="icon-plus"></i> <?=$LG['baoguo.add_save_11'];//对已提交成功的包裹填写运单信息?> </button>
				<button type="button" class="btn btn-default" onClick="location.href='list.php?status=kuwai';"> <i class="icon-list"></i> <?=$LG['baoguo.add_save_12'];//从未入库包裹选择下运单?> </button>
				</div>
				<?php 
			}
		}

	}else{
		echo '<br> <strong>'.$LG['baoguo.add_save_13'].'</strong>';
	}
}//if($lx=='add')

?>
	</div>
	</div>
	</div>
	</div>
	</div>
	<div align="center">
		<?php if(!$bg_zxyd){?>
		<button type="button" class="btn btn-info input-small" onClick="location.href='add_form.php';"> <i class="icon-plus"></i> <?=$LG['baoguo.add_save_14'];//继续提交预报?> </button>
		
		<button type="button" class="btn btn-default input-small" onClick="location.href='list.php?status=kuwai';"> <i class="icon-list"></i> <?=$LG['baoguo.add_save_15'];//预报管理?> </button>
		<button type="button" class="btn btn-default input-small" onClick="location.href='list.php?status=ruku';"> <i class="icon-list"></i> <?=$LG['baoguo.add_save_16'];//入库管理?> </button>
		<?php }?>
	</div>
	</div>
	</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
