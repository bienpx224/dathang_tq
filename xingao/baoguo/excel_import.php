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
$pervar='baoguo_ad';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="导入入库";
$alonepage=2;//1单页形式;2框架形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
@set_time_limit(0);//如果有大量数据导入,需要设置长些

if(!$off_baoguo){exit ("<script>alert('包裹系统已关闭！');goBack();</script>");}
//获取,处理
$lx=par($_POST['lx']);
$file=par($_POST['file'],'',1);
$tokenkey=par($_POST['tokenkey']);
$upType=par($_POST['upType']);


//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("baoguo_excel_import");
?>

<div class="alert alert-block fade in " style="margin-top:0px;">
	<h3 class="page-title"><?php if($alonepage!=1){?>
<a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=kuwai" class="gray" target="_parent"><?=$LG['backList']?></a> > 
    <?php }?>
    <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
		<?=$headtitle?>
		</a> </h3>
	<form action="?" method="post" class="form-horizontal form-bordered" name="xingao">
		<input name="lx" type="hidden" value="tj">
		<input name="tokenkey" type="hidden" value="<?=$tokenkey?>">

		<div class="portlet">
			<div class="portlet-body form" style="display: block;"> 
				<!--表单内容-->
				
				<div class="form-group">
					<label class="control-label col-md-2"><?=$LG['file']//文件?></label>
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
					<label class="control-label col-md-2">更新方式</label>
					<div class="col-md-10">
                    
<div class="radio-list">
   <label>
   <input type="radio" name="upType" value="0" <?=$upType==0?'checked':''?>> 单号相同/号段相同:不导入更新
   </label>
   
   <label>
   <input type="radio" name="upType" value="1" <?=$upType==1?'checked':''?>> 单号相同:导入更新;删除原来的物品;
   </label>
   
   <label>
   <input type="radio" name="upType" value="2" <?=$upType==2?'checked':''?>> 单号相同/号段相同:导入更新;不删除原来的物品;
   </label>
   
   <label>
   <input type="radio" name="upType" value="3" <?=$upType==3?'checked':''?>> 单号相同/号段相同:导入更新;删除原来的物品;
   </label>

</div>
<span class="help-block">号段长度请在系统设置中设置,越长越准确,太短时可能会更新到错误的包裹</span>

					</div>
				</div>
                
                
				<div class="form-group">
					<label class="control-label col-md-2"><?=$LG['explain']//说明?></label>
					<div class="col-md-10"> <span class="help-block">&raquo;  <a href="/doc/Import_baoguo_manage.xls" target="_blank" class="red"><strong><?=$LG['excelFormat']//Excel格式?></strong></a><?=$LG['excelFormatExplain']//，请做成跟此表一样!?><br>
						&raquo;  重量单位：<?=$XAwt?><br>
						&raquo;  仓库名称与仓库编号：<br>
						<?=TextareaToBr($warehouse)?>
						<br>
						</span> </div>
				</div>
			</div>
		</div>
        <div align="center" >
      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
			<button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
		</div>
	</form>
<?php
//处理部分-开始**************************************************************************************************
//必须有$file 文件
if ($lx=="tj")
{ 
	if(!$tokenkey){exit ("<script>alert('来源错误！');goBack();</script>");}//同一个页面里提交,不能用"验证令牌密钥"

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
		if($ok && par($strs[0]) ) 
		{
			//验证
			if($ok && (!par($strs[0])||!par($strs[1])||!par($strs[2])  ) ) 
			{
				$ok=0;
				$error_result+=1;
				echo '<strong>'.$strs[0].'</strong>：必填内容未填写完整。此行没导入！<br>';
			}
			if($ok && (!CheckEmpty($strs[3])  ) ) 
			{
				$ok=0;
				$error_result+=1;
				echo '<strong>'.$strs[0].'</strong>：必填内容不能空，至少要填写0。此行没导入！<br>';
			}
			
			
			if($ok) 
			{
				if(!warehouse(par($strs[1])))
				{
					$ok=0;
					$error_result+=1;
					echo '<strong>'.$strs[0].'</strong>：仓库编号错误。此行没导入！<br>';
				}
			}
	
			if($ok) 
			{
				$warehouse=warehouse_per('ts',par($strs[1]),1);
				if($warehouse)
				{
					$ok=0;
					$error_result+=1;
					echo '<strong>'.$strs[0].'</strong>：您无权添加到该仓库。此行没导入！<br>';
				}
			}
			
			if($ok&&add($strs[2])) 
			{
				$user=FeData('member','username,userid,useric',"username='".add($strs[2])."' or userid='".CheckNumber($strs[2],-0.1)."' or useric='".add($strs[2])."' ");
				if(!$user['userid'])
				{
					$ok=0;
					$error_result+=1;
					echo '<strong>'.$strs[0].'</strong>：'.$strs[2].' 会员入库码/会员ID/会员登录名错误。此行没导入！<br>';
				}else{
					$userid=$user['userid'];
					$useric=$user['useric'];
					$username=$user['username'];
				}
			}
			
			//验证完成---
			
			
		
		
		
		
		
		
		
		
		
		
		
		
			//更新包裹:导入方式
			if($ok) 
			{
				$where_search=" and userid='".spr($userid)."'";//有其他条件时,以空格 and 开头
				$id='bgid';
				$table='baoguo';
				$field='bgydh';
				$smlx=0;//1只精确搜索;0全部搜索
				$bgydh=par($strs[0]);
				$rsid='';//重要,因此在此页再多加一个
				$rsid=searchNumber($id,$table,$field,$bgydh,$smlx,$where_search);//搜索处理
				if($rsid)
				{
					$rs=FeData('baoguo','bgydh,bgid,status',"bgid='{$rsid}'");
					if(spr($rs['status'])==4||spr($rs['status'])==9)
					{
						$ok=0;
						$error_result+=1;
						echo '<strong>'.$strs[0].'</strong>：单号已经存在并且状态【'.baoguo_Status(spr($rs['status'])).'】。此行没导入也没更新！<br>';
					}else{
						
						$save="warehouse='".spr($strs[1])."',status='".spr($strs[3])."',weight='".spr($strs[5])."'";
						
						if($upType==0 && cadd($rs['bgydh'])==par($strs[0]) )
						{
							$ok=0;
							$error_result+=1;
							echo '<strong>'.$strs[0].'</strong>：单号已经存在。此行没导入！<br>';
						}elseif($upType==1 && cadd($rs['bgydh'])==par($strs[0]) ){
							$save_up=1;
						}elseif($upType==2||$upType==3){
							$save_up=1;
							$save.=",bgydh='".par($strs[0])."'";
						}
						
						if($save_up)
						{
							$xingao->query("update baoguo set {$save} where  bgid='{$rs['bgid']}'");
							SQLError('更新包裹');
							$succ_result+=1;
							$fromtable='baoguo';$fromid=$rs['bgid'];
							echo '<strong>'.$strs[0].'</strong>：单号已经存在，已更新！<br>';
							
							//删除物品,以便后面添加更新
							if($upType!=2)
							{
								$xingao->query("delete from wupin where fromtable='{$fromtable}' and fromid='{$fromid}'");
							}
						}
						
					}//if(spr($rs['status'])==4&&spr($rs['status'])==9)
					
				}//if($rsid)
			}//if($ok) 
			
				
				
			//无包裹时新加包裹
			if($ok&&!$save_up)
			{
				if((int)$strs[3]>1){$rukutime=time();}else{$rukutime=0;}
				$xingao->query("insert into baoguo (
				`bgydh`,`warehouse`, `status`,weight,whPlace,
				`kuaidi`, `fahuotime`,`wangzhan`, `wangzhan_other`,content,unclaimedContent,
				addSource,`addtime`,`rukutime`, `username`,`userid`,useric
				
				)values(
				
				'".par($strs[0])."','".spr($strs[1])."','".spr($strs[3])."','".spr($strs[4])."','".add($strs[5])."',
				'".add($strs[6])."','".ToStrtotime($strs[7])."','other','".add($strs[8])."','".html($strs[18])."','".html($strs[19])."',
				'2','".time()."','".$rukutime."','".$username."','".spr($userid)."','".$useric."'
				
				)");
				
				SQLError('保存包裹');
				$succ_result+=1;
				$fromtable='baoguo';$fromid=mysqli_insert_id($xingao);
			}else{
				$fromtable='';$fromid='';
			}
			$save_up=0;
		
		}
		//包裹-结束
		
		
		
		//物品-开始
		if($ok&&$fromtable&&$fromid)	
		{		
			$xingao->query("insert into wupin (fromtable,fromid,wupin_type,wupin_name,wupin_brand,wupin_spec,wupin_weight,wupin_number,wupin_unit,wupin_price,wupin_total) values ('".add($fromtable)."','".spr($fromid)."','".add($strs[9])."','".add($strs[10])."','".add($strs[11])."','".add($strs[12])."','".spr($strs[13])."','".spr($strs[14])."','".add($strs[15])."','".spr($strs[16])."','".spr($strs[17])."' )");
			SQLError('保存物品');
		}
		//物品-结束
		
		
		
		
	}//for ($row=2;$row<=$highestRow;$row++)
	//导入部分-结束----------------------------------------------------------------------------------
	
	
	
	
	
	
	echo '<br><hr size="1" width="100%" />';
	echo $LG['importSuccess'].":<strong>{$succ_result}</strong><br>";
	echo $LG['importFailure'].":<strong>{$error_result}</strong><br>";

	DelFile($file);//删除文件
	//$token->drop_token("baoguo_excel_import"); //同一个页面不能删除-处理完后删除密钥
	
	//Import_call.php 文件中有2个div开头
	echo ' 
	</div>
    </div>
	';
	
}
echo '<script src="/public/PHPExcel/PHPExcel/CachedObjectStorage/SQLiti.php" type="text/javascript"></script>';

//处理部分-结束**************************************************************************************************
?>
</div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
