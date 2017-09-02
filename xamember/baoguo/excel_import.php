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
$headtitle=$LG['baoguo.excel_import_1'];//预报批量导入
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

if(!$off_baoguo||!$member_per[$Mgroupid]['ON_Mbaoguo']){exit ("<script>alert('{$LG['baoguo.add_form_2']}');goBack();</script>");}
if(!$off_baoguo_yubao){exit ("<script>alert('{$LG['baoguo.add_form_3']}');goBack();</script>");}

//获取,处理
$lx=par($_POST['lx']);
$file=par($_POST['file'],'',1);
$tokenkey=par($_POST['tokenkey']);


//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("baoguo_excel_import");
?>

<div class="alert alert-block fade in " style="margin-top:0px;">
	<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=kuwai" class="gray"><?=$LG['baoguo.excel_import_9']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
		<?=$headtitle?>
		</a> </h3>
	<form action="" method="post" class="form-horizontal form-bordered" name="xingao">
		<input name="lx" type="hidden" value="tj">
		<input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
		
		<ul class="nav nav-tabs">
			<li><a href="add_form.php"><?=$LG['baoguo.add_form_25'];//在线预报?></a></li>
			<li class="active"><a href="javascript:void(0)"><?=$LG['name.nav_20'];//批量导入?></a></li>
		</ul>			

		<div class="portlet">
			<div class="portlet-body form" style="display: block;"> 
				<!--表单内容-->
				
				<div class="form-group">
					<label class="control-label col-md-2"><?=$LG['file'];//文件?></label>
					<div class="col-md-10">
            <?php 
			//文件上传配置
			$uplx='file';//img,file
			$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
			$inputname='file';//保存字段名，多个时加[]
			$Pathname='import';//指定存放目录分类
			include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
			?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2"><?=$LG['explain']?></label>
					<div class="col-md-10"> <span class="help-block">&raquo;  <a href="/doc/Import_baoguo.xls" target="_blank" class="red"><strong><?=$LG['excelFormat'];//Excel格式?></strong></a><?=$LG['excelFormatExplain'];//，请做成跟此表一样!?><br>
						&raquo;  <?=$LG['baoguo.excel_import_10'];//仓库名称与仓库编号?>：<br>
						<?=TextareaToBr($warehouse)?>
						<br>
						</span> </div>
				</div>
			</div>
		</div>        
        <!--提交按钮固定--> 
		<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
 
        <div align="center" class="fixed_btn" id="Autohidden">
        
        
<button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
			<button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
		</div>
	</form>
	<?php
//处理部分-开始**************************************************************************************************
//必须有$file 文件
if ($lx=="tj")
{ 
	if(!$tokenkey){exit ("<script>alert('{$LG['baoguo.excel_import_4']}');goBack();</script>");}//同一个页面里提交,不能用"验证令牌密钥"
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/Import_call.php');
	
	//导入部分-开始-------------------------------------------------------------------------------------
	for ($row=2;$row<=$highestRow;$row++) //$row =2; 从第2行读取(第一行是标题) 
	{
		$strs=array();
		for ($col = 0;$col < $highestColumnIndex;$col++)
		{
			$strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
		}    
		
		$ok=1;
		//包裹-开始
		if($ok && (par($strs[0])&&par($strs[2])) ) 
		{
			//验证
			if($ok && (!$strs[0]||!$strs[2]) ) 
			{
				$ok=0;
				$error_result+=1;
				echo '<strong>'.$strs[0]."</strong>：{$LG['baoguo.excel_import_5']}<br>";
			}
			
			if($ok) 
			{
				if(!warehouse(par($strs[2])))
				{
					$ok=0;
					$error_result+=1;
					echo '<strong>'.$strs[0]."</strong>：{$LG['baoguo.excel_import_6']}<br>";
				}
			}
	
			if($ok) 
			{
				$num=mysqli_num_rows($xingao->query("select bgydh from baoguo where bgydh='".par($strs[0])."' "));
				if($num)
				{
					$ok=0;
					$error_result+=1;
					echo '<strong>'.$strs[0]."</strong>：{$LG['baoguo.excel_import_7']}<br>";
				}
			}
	
			//保存包裹
			if ($ok)
			{
				$status=0;
				$xingao->query("insert into baoguo (`bgydh`,   `kuaidi`, `warehouse`, `fahuodiqu`, `fahuotime`, `wangzhan`, `wangzhan_other`, `content`,`addSource`, `status`,`addtime`, `username`, `userid`,useric) values('".par($strs[0])."','".add($strs[1])."','".add($strs[2])."','".add($strs[3])."','".ToStrtotime($strs[4])."','other','".add($strs[5])."','".html($strs[15])."','1','{$status}','".time()."','{$Musername}','{$Muserid}','{$Museric}')");
				
				SQLError('保存包裹');
				$fromtable='baoguo';$fromid=mysqli_insert_id($xingao);
				$succ_result+=1;
			}else{
				$fromtable='';$fromid='';
			}
		
		}
		//包裹-结束
		
		
		//物品-开始
		if($ok&&$fromtable&&$fromid)	
		{		
			$xingao->query("insert into wupin (fromtable,fromid,wupin_type,wupin_name,wupin_brand,wupin_spec,wupin_weight,wupin_number,wupin_unit,wupin_price,wupin_total) values ('".add($fromtable)."','".spr($fromid)."','".add($strs[6])."','".add($strs[7])."','".add($strs[8])."','".add($strs[9])."','".spr($strs[10])."','".spr($strs[11])."','".add($strs[12])."','".spr($strs[13])."','".spr($strs[14])."' )");
			SQLError('保存物品');
		}
		//物品-结束


	}//for ($row=2;$row<=$highestRow;$row++)
	//导入部分-结束-------------------------------------------------------------------------------------
	
	echo '<br><hr size="1" width="100%" />';
	echo $LG['importSuccess'].":<strong>{$succ_result}</strong><br>";
	echo $LG['importFailure'].":<strong>{$error_result}</strong><br>";

	DelFile($file);//删除文件
	//Import_call.php 文件中有2个div开头
	echo ' 
	</div>
    </div>
	';
	
}
//处理部分-结束**************************************************************************************************
?>
</div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
